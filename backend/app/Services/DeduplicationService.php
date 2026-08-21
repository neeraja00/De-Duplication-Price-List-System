<?php

namespace App\Services;

use App\Models\PriceList;
use App\Models\DuplicateGroup;
use App\Models\DuplicateGroupItem;
use App\Models\DeduplicationSetting;
use App\Models\UploadedFile;
use App\Models\DuplicateDetectionMetadata;
use App\Models\FuzzyMatchResult;

class DeduplicationService
{
    protected $normalizationService;

    public function __construct(NormalizationService $normalizationService)
    {
        $this->normalizationService = $normalizationService;
    }

    public function processUpload(UploadedFile $upload)
    {
        $settings = DeduplicationSetting::first();
        
        $newRecords = PriceList::where('uploaded_file_id', $upload->id)->get();
        
        foreach ($newRecords as $record) {
            $record->pl_number_normalized = $this->normalizationService->normalize($record->pl_number_original, $settings);
            $record->save();
        }

        $userFileIds = UploadedFile::where('user_id', $upload->user_id)->pluck('id');
        $allRecords = PriceList::whereIn('uploaded_file_id', $userFileIds)
                               ->whereIn('status', ['active', 'duplicate'])
                               ->get();

        $groupedByNormalized = $allRecords->groupBy('pl_number_normalized');
        $duplicateCount = 0;

        foreach ($groupedByNormalized as $normalizedPl => $records) {
            if ($records->count() > 1) {
                // Check if these records are already in a pending group
                $existingGroupItem = DuplicateGroupItem::whereIn('price_list_id', $records->pluck('id'))->first();
                if ($existingGroupItem) {
                    continue; // Skip if already grouped
                }

                $originals = $records->pluck('pl_number_original')->unique();
                $matchType = $originals->count() === 1 ? 'exact' : 'formatting';
                $confidenceScore = $matchType === 'exact' ? 100 : 98;

                $this->createDuplicateGroup($records, $matchType, $confidenceScore, $upload->user_id);
                $duplicateCount += $records->count();
            }
        }

        $this->detectFuzzyMatches($allRecords->unique('pl_number_normalized'), $settings, $upload->user_id);

        $upload->duplicate_records = $duplicateCount;
        $upload->status = 'completed';
        $upload->save();
    }

    protected function detectFuzzyMatches($uniqueRecords, $settings, $userId)
    {
        $threshold = ($settings && isset($settings->fuzzy_match_threshold)) ? $settings->fuzzy_match_threshold : 85;
        $processed = [];

        foreach ($uniqueRecords as $record1) {
            $processed[] = $record1->id;
            $similarGroup = collect([$record1]);

            foreach ($uniqueRecords as $record2) {
                if (in_array($record2->id, $processed)) continue;

                $similarity = 0;
                similar_text($record1->pl_number_normalized, $record2->pl_number_normalized, $similarity);

                if ($similarity >= $threshold && $similarity < 100) {
                    $similarGroup->push($record2);
                    $processed[] = $record2->id;
                    
                    FuzzyMatchResult::create([
                        'record_1_id' => $record1->id,
                        'record_2_id' => $record2->id,
                        'record_1_normalized' => $record1->pl_number_normalized,
                        'record_2_normalized' => $record2->pl_number_normalized,
                        'similarity_score' => $similarity,
                        'threshold_used' => $threshold,
                    ]);
                }
            }

            if ($similarGroup->count() > 1) {
                $allVariants = PriceList::whereIn('pl_number_normalized', $similarGroup->pluck('pl_number_normalized'))
                                        ->whereIn('status', ['active', 'duplicate'])
                                        ->get();
                
                $existingGroupItem = DuplicateGroupItem::whereIn('price_list_id', $allVariants->pluck('id'))->first();
                if (!$existingGroupItem) {
                    $this->createDuplicateGroup($allVariants, 'typo', (int) $similarGroup->avg(function($item) use ($record1) {
                        similar_text($record1->pl_number_normalized, $item->pl_number_normalized, $sim);
                        return $sim;
                    }), $userId);
                }
            }
        }
    }

    protected function createDuplicateGroup($records, $matchType, $confidenceScore, $userId)
    {
        $groupCode = 'DUP-' . strtoupper(uniqid());

        $group = DuplicateGroup::create([
            'group_code' => $groupCode,
            'match_type' => $matchType,
            'confidence_score' => $confidenceScore,
            'status' => 'pending',
            'user_id' => $userId,
        ]);

        $isFirst = true;
        foreach ($records as $record) {
            DuplicateGroupItem::create([
                'duplicate_group_id' => $group->id,
                'price_list_id' => $record->id,
                'role' => $isFirst ? 'canonical' : 'duplicate',
            ]);
            $isFirst = false;
        }

        DuplicateDetectionMetadata::create([
            'duplicate_group_id' => $group->id,
            'group_code' => $groupCode,
            'match_type' => $matchType,
            'confidence_score' => $confidenceScore,
            'records_involved' => $records->pluck('id')->toArray(),
            'created_at' => now(),
        ]);
    }
}

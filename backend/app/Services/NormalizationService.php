<?php

namespace App\Services;

use App\Models\DeduplicationSetting;

class NormalizationService
{
    public function normalize(string $plNumber, ?DeduplicationSetting $settings = null): string
    {
        $normalized = trim($plNumber);
        $normalized = strtoupper($normalized);

        $ignoreSpecial = $settings ? (bool)$settings->ignore_special_characters : true;
        $ignoreHyphens = $settings ? (bool)$settings->ignore_hyphens : true;
        $ignoreSpaces = $settings ? (bool)$settings->ignore_spaces : true;
        $ignoreLeadingZeros = $settings ? (bool)$settings->ignore_leading_zeros : true;

        if ($ignoreSpecial) {
            $normalized = preg_replace('/[^A-Z0-9\s]/', '', $normalized);
        }

        if ($ignoreHyphens && !$ignoreSpecial) {
            $normalized = str_replace('-', '', $normalized);
        }

        if ($ignoreSpaces) {
            $normalized = preg_replace('/\s+/', '', $normalized);
        }

        if ($ignoreLeadingZeros) {
            $normalized = preg_replace('/(?<=[A-Z])0+(?=\d)/', '', $normalized); 
            $normalized = ltrim($normalized, '0');
        }

        return $normalized;
    }
}

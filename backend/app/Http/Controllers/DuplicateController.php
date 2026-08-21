<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DuplicateGroup;
use App\Models\DuplicateGroupItem;
use App\Models\PriceList;
use App\Models\MergeLog;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class DuplicateController extends Controller
{
    public function index()
    {
        $groups = DuplicateGroup::where('user_id', auth()->id())->with('items')->orderBy('created_at', -1)->get()->map(function ($group) {
            $group->items_count = $group->items ? $group->items->count() : 0;
            return $group;
        });
        return view('duplicates.index', compact('groups'));
    }

    public function show($id)
    {
        $group = DuplicateGroup::where('user_id', auth()->id())->with(['items.priceList'])->findOrFail($id);
        return view('duplicates.show', compact('group'));
    }

    public function merge(Request $request, $id)
    {
        $request->validate([
            'canonical_id' => 'required|exists:price_lists,id'
        ]);

        $group = DuplicateGroup::where('user_id', auth()->id())->findOrFail($id);
        
        $canonicalId = $request->canonical_id;
        $items = DuplicateGroupItem::where('duplicate_group_id', $group->id)->get();
        $mergedIds = [];

        foreach ($items as $item) {
            $priceList = PriceList::find($item->price_list_id);
            if ($priceList->id == $canonicalId) {
                $priceList->is_canonical = true;
                $priceList->status = 'active';
            } else {
                $priceList->is_canonical = false;
                $priceList->status = 'merged';
                $priceList->duplicate_of_id = $canonicalId;
                $mergedIds[] = $priceList->id;
            }
            $priceList->save();
        }

        $group->status = 'resolved';
        $group->save();

        MergeLog::create([
            'duplicate_group_id' => $group->id,
            'canonical_price_list_id' => $canonicalId,
            'merged_price_list_ids' => json_encode($mergedIds),
            'merged_by' => auth()->id(),
            'notes' => 'Merged via Duplicate Review UI',
        ]);

        \App\Services\AuditLogger::log('duplicate_merged', 'deduplication', 'success', 'Duplicate group merged successfully', [
            'duplicate_group_id' => (string) $group->id,
            'group_code' => $group->group_code,
            'canonical_price_list_id' => $canonicalId,
            'merged_price_list_ids' => $mergedIds
        ]);

        $routePrefix = auth()->user()->role === 'admin' ? 'admin.' : 'user.';
        return redirect()->route($routePrefix . 'duplicates.index')->with('success', 'Merge successful');
    }

    public function reject(Request $request, $id)
    {
        $group = DuplicateGroup::where('user_id', auth()->id())->findOrFail($id);
        
        $group->status = 'ignored';
        $group->save();

        $items = DuplicateGroupItem::where('duplicate_group_id', $group->id)->get();
        foreach ($items as $item) {
            $priceList = PriceList::find($item->price_list_id);
            $priceList->status = 'active'; 
            $priceList->is_canonical = true;
            $priceList->save();
        }

        \App\Services\AuditLogger::log('duplicate_rejected', 'deduplication', 'success', 'Duplicate group rejected', [
            'duplicate_group_id' => (string) $group->id,
            'group_code' => $group->group_code
        ]);

        $routePrefix = auth()->user()->role === 'admin' ? 'admin.' : 'user.';
        return redirect()->route($routePrefix . 'duplicates.index')->with('success', 'Duplicates rejected successfully');
    }
}

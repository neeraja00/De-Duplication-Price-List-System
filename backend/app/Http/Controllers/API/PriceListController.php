<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PriceList;
use Illuminate\Http\Request;

class PriceListController extends Controller
{
    public function index(Request $request)
    {
        $query = PriceList::with('uploadedFile');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('pl_number_original', 'like', "%{$search}%")
                  ->orWhere('pl_number_normalized', 'like', "%{$search}%")
                  ->orWhere('item_name', 'like', "%{$search}%")
                  ->orWhere('vendor_name', 'like', "%{$search}%");
            });
        }

        return response()->json($query->paginate(50));
    }

    public function show($id)
    {
        return response()->json(PriceList::with(['uploadedFile', 'duplicates'])->findOrFail($id));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PriceList;

class PriceListController extends Controller
{
    public function index()
    {
        $userFileIds = \App\Models\UploadedFile::where('user_id', auth()->id())->pluck('id');
        $records = PriceList::whereIn('uploaded_file_id', $userFileIds)->orderBy('created_at', -1)->paginate(50);
        return view('price_lists.index', compact('records'));
    }
}

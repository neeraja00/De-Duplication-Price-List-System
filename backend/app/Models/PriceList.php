<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class PriceList extends Model
{
    use HasFactory;

    protected $fillable = [
        'uploaded_file_id',
        'pl_number_original',
        'pl_number_normalized',
        'item_name',
        'vendor_name',
        'price',
        'currency',
        'effective_date',
        'status',
        'is_canonical',
        'duplicate_of_id',
    ];

    public function uploadedFile()
    {
        return $this->belongsTo(UploadedFile::class);
    }

    public function duplicateOf()
    {
        return $this->belongsTo(PriceList::class, 'duplicate_of_id');
    }

    public function duplicates()
    {
        return $this->hasMany(PriceList::class, 'duplicate_of_id');
    }

    public function duplicateGroupItems()
    {
        return $this->hasMany(DuplicateGroupItem::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class MergeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'duplicate_group_id',
        'canonical_price_list_id',
        'merged_price_list_ids',
        'merged_by',
        'notes',
    ];

    protected $casts = [
        'merged_price_list_ids' => 'array',
    ];

    public function group()
    {
        return $this->belongsTo(DuplicateGroup::class, 'duplicate_group_id');
    }

    public function canonicalPriceList()
    {
        return $this->belongsTo(PriceList::class, 'canonical_price_list_id');
    }

    public function mergedBy()
    {
        return $this->belongsTo(User::class, 'merged_by');
    }
}

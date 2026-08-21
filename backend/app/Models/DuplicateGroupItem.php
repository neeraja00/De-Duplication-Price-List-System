<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class DuplicateGroupItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'duplicate_group_id',
        'price_list_id',
        'role',
    ];

    public function group()
    {
        return $this->belongsTo(DuplicateGroup::class, 'duplicate_group_id');
    }

    public function priceList()
    {
        return $this->belongsTo(PriceList::class);
    }
}

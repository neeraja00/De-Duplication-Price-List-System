<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class DuplicateGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_code',
        'match_type',
        'confidence_score',
        'status',
        'user_id',
    ];

    public function items()
    {
        return $this->hasMany(DuplicateGroupItem::class);
    }

    public function mergeLogs()
    {
        return $this->hasMany(MergeLog::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class DeduplicationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'ignore_hyphens',
        'ignore_spaces',
        'ignore_special_characters',
        'ignore_leading_zeros',
        'fuzzy_match_threshold',
    ];
}

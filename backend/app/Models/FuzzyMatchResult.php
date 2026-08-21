<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class FuzzyMatchResult extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'fuzzy_match_results';
    protected $guarded = [];
}

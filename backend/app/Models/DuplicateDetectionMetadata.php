<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class DuplicateDetectionMetadata extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'duplicate_detection_metadata';
    protected $guarded = [];
}

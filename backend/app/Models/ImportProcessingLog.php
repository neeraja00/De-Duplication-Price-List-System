<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ImportProcessingLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'import_processing_logs';
    protected $guarded = [];
}

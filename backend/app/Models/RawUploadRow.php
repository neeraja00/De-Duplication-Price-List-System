<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class RawUploadRow extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'raw_upload_rows';
    protected $guarded = [];

    // Relationship back to MySQL (if needed) but usually just storing ID
}

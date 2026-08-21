<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$uploads = \App\Models\UploadedFile::with('user')->get();
echo json_encode($uploads, JSON_PRETTY_PRINT);

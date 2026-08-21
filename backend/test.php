<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $user = \App\Models\User::first();
    auth()->login($user);
    
    $controller = app('App\Http\Controllers\DuplicateController');
    $response = $controller->index();
    
    print("Index Success!\n");
    
    $groups = \App\Models\DuplicateGroup::all();
    if ($groups->count() > 0) {
        $res = $controller->show($groups->first()->id);
        print("Show Success!\n");
    }
} catch (\Exception $e) {
    print("Exception thrown: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n");
}

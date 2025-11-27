<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Simulate authenticated request
$tutor = App\Models\User::where('email', 'tentor@gmail.com')->first();
Auth::login($tutor);

// Test the controller method directly
$package = App\Models\Package::find(1);

if ($package) {
    echo "Testing Package ID: {$package->id}\n";
    echo "Package Title: {$package->detail_title}\n\n";
    
    // Simulate what the controller does
    $subjects = $package->subjects()->select('subjects.id', 'subjects.name', 'subjects.level')->get();
    
    echo "Subjects returned by query:\n";
    echo json_encode($subjects->toArray(), JSON_PRETTY_PRINT) . "\n\n";
    
    // Test the actual controller method
    $controller = new App\Http\Controllers\Tutor\MaterialController();
    $response = $controller->getPackageSubjects($package);
    
    echo "Controller response:\n";
    echo $response->getContent() . "\n";
} else {
    echo "Package not found!\n";
}

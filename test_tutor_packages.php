<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check tutor packages
$tutor = App\Models\User::where('email', 'tentor@gmail.com')->first();

if ($tutor) {
    echo "Tutor ID: {$tutor->id} - {$tutor->name}\n";
    echo "Packages assigned: " . $tutor->packages()->count() . "\n\n";
    
    $packages = $tutor->packages;
    foreach ($packages as $pkg) {
        echo "Package ID: {$pkg->id} - {$pkg->detail_title}\n";
        echo "  Subjects: " . $pkg->subjects()->count() . "\n";
        
        foreach ($pkg->subjects as $subject) {
            echo "    - {$subject->name} ({$subject->level})\n";
        }
    }
} else {
    echo "Tutor not found!\n";
}

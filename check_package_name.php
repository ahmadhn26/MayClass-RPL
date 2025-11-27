<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$pkg = App\Models\Package::first();

echo "Package Details:\n";
echo "ID: {$pkg->id}\n";
echo "name: " . ($pkg->name ?? 'NULL') . "\n";
echo "detail_title: " . ($pkg->detail_title ?? 'NULL') . "\n";
echo "level: " . ($pkg->level ?? 'NULL') . "\n";

// Check what the view would display
echo "\nWhat view displays:\n";
echo "{{ \$package->name }} ({{ \$package->level }}): ";
echo ($pkg->name ?? 'NULL') . " (" . ($pkg->level ?? 'NULL') . ")\n";

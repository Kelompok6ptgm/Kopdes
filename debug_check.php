<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$manager = App\Models\User::where('id_role', 2)->first();
echo "manager id_user: " . $manager->id_user . PHP_EOL;
echo "manager id_kopdes: " . var_export($manager->id_kopdes, true) . PHP_EOL;
echo "manager->kopdes (relation): " . ($manager->kopdes ? $manager->kopdes->nama_kopdes : 'NULL') . PHP_EOL;

$kopdesCount = App\Models\Kopdes::count();
echo "kopdes table count: " . $kopdesCount . PHP_EOL;
echo "role->nama_role: " . ($manager->role ? $manager->role->nama_role : 'NULL') . PHP_EOL;

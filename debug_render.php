<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('id_role', 2)->first();
Auth::login($user);
$html = view('dashboard')->with('errors', new Illuminate\Support\ViewErrorBag())->render();
file_put_contents('debug_manager.html', $html);
echo 'rendered bytes: ' . strlen($html) . PHP_EOL;

<?php
Auth::loginUsingId(App\Models\User::where('id_role', 2)->first()->id_user);
$html = view('dashboard')->render();
file_put_contents('debug_manager.html', $html);
echo 'rendered bytes: ' . strlen($html) . PHP_EOL;

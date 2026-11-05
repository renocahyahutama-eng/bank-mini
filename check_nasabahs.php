<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$nasabahs = App\Models\Nasabah::all(['id', 'account_number', 'student_name', 'email']);
foreach ($nasabahs as $n) {
    echo "ID: {$n->id} | Acc: {$n->account_number} | Name: {$n->student_name} | Email: {$n->email}" . PHP_EOL;
}


<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

echo "Starting restore process...\n";

// Read the SQL file
$path = database_path('database2.sql');
if (!File::exists($path)) {
    die("File not found: $path\n");
}
$sql = File::get($path);

// Remove CREATE DATABASE and USE statements
$sql = preg_replace('/^CREATE DATABASE.*;$/m', '', $sql);
$sql = preg_replace('/^USE.*;$/m', '', $sql);

// Split into statements
$statements = array_filter(array_map('trim', explode(';', $sql)));

DB::beginTransaction();
try {
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    
    // Drop BaiViet if exists to avoid conflicts during restore if it was created
    DB::statement('DROP TABLE IF EXISTS BaiViet');
    DB::statement('DROP TABLE IF EXISTS bai_viets');
    DB::statement('DROP TABLE IF EXISTS migrations');

    $count = 0;
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            try {
                DB::statement($statement);
                $count++;
            } catch (\Exception $e) {
                echo "Error executing statement: " . substr($statement, 0, 50) . "... \n";
                echo $e->getMessage() . "\n";
            }
        }
    }

    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    DB::commit();
    echo "Database restored successfully. Executed $count statements.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Fatal error: " . $e->getMessage() . "\n";
}

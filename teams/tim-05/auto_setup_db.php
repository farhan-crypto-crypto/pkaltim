<?php
$host = 'localhost';
$username = 'root';
$password = '';
$db_name = 'pesuttrip_db';

try {
    // Connect to MySQL without database name
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    echo "SUCCESS: Database '$db_name' berhasil dibuat atau sudah ada.\n";

    // Reconnect with database name
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Read and execute SQL file
    if (file_exists('database.sql')) {
        $sql = file_get_contents('database.sql');
        
        // Remove comments and split by semicolon
        $queries = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($queries as $query) {
            if (!empty($query)) {
                $pdo->exec($query);
            }
        }
        echo "SUCCESS: Data dari 'database.sql' berhasil diimpor.\n";
    } else {
        echo "WARNING: File 'database.sql' tidak ditemukan.\n";
    }

} catch (PDOException $e) {
    echo "ERROR: Petugas gagal mengatur database. Pesan: " . $e->getMessage() . "\n";
}
?>

<?php 

require_once 'Database.php';

function testConnection() {
    $conn = Database::getInstance()->getConnection();
    if ($conn) {
        echo "Berhasil terkoneksi dengan database.";
    }else {
        echo "Gagal terkoneksi dengan database.";
    }
    $conn->close();
}

testConnection();

?>
<?php
// koneksi database pakai PDO + singleton
// jadi objek koneksinya cuma dibuat sekali aja

class Database
{
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $dbname = 'inventaris_db';
    private $user = 'root';
    private $pass = '';

    // constructor private biar ga bisa di "new Database()" dari luar
    private function __construct()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            die('Koneksi database gagal: ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    // biar ga bisa di-clone
    private function __clone() {}
}

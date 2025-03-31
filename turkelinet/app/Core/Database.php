<?php
// app/Core/Database.php

// Veritabanı bağlantısını yönetmek için PDO kullanan sınıf

class Database {
    private static $instance = null; // Singleton pattern için instance
    private $connection;

    private $db_host = DB_HOST;
    private $db_user = DB_USER;
    private $db_pass = DB_PASS;
    private $db_name = DB_NAME;
    private $db_charset = DB_CHARSET;

    // Constructor'ı private yaparak doğrudan nesne oluşturmayı engelle (Singleton)
    private function __construct() {
        $dsn = "mysql:host={$this->db_host};dbname={$this->db_name};charset={$this->db_charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Hataları exception olarak yakala
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Sonuçları associative array olarak al
            PDO::ATTR_EMULATE_PREPARES   => false,                  // Gerçek prepare statement kullan
        ];

        try {
            $this->connection = new PDO($dsn, $this->db_user, $this->db_pass, $options);
            // echo "Veritabanı bağlantısı başarılı!"; // Test için eklenebilir, sonra kaldırılmalı
        } catch (PDOException $e) {
            // Gerçek uygulamada burada daha detaylı hata yönetimi yapılmalı
            // Örneğin: Hata loglama, kullanıcıya genel bir hata mesajı gösterme
            throw new PDOException("Veritabanı bağlantı hatası: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    // Singleton pattern: Sadece bir tane Database nesnesi olmasını sağlar
    public static function getInstance() {
        if (self::$instance === null) {
            // config.php dosyasını dahil et (eğer daha önce edilmediyse)
            // Bu dosyanın yolunu projenizin yapısına göre ayarlamanız gerekebilir.
            // Genellikle bir ön yükleyici (bootstrap) dosyasında yapılır.
            // Şimdilik burada varsayalım:
            require_once __DIR__ . '/../config.php';
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // PDO bağlantı nesnesini döndüren metod
    public function getConnection() {
        return $this->connection;
    }

    // Klonlamayı engelle (Singleton)
    private function __clone() {}

    // Uyandırmayı engelle (Singleton)
    public function __wakeup() {}
}

?>

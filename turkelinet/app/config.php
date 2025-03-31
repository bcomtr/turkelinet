<?php
// app/config.php

// Hata Raporlama Ayarları (Geliştirme aşamasında E_ALL önerilir, canlıda E_ALL & ~E_NOTICE)
error_reporting(E_ALL);
ini_set('display_errors', 1); // Geliştirme için 1, canlıda 0 yapın

// Zaman Dilimi Ayarı
date_default_timezone_set('Europe/Istanbul');

// Veritabanı Bağlantı Bilgileri
// BU BİLGİLERİ KENDİ KURULUMUNUZA GÖRE GÜNCELLEYİN!
define('DB_HOST', 'localhost');       // Genellikle localhost'tur
define('DB_USER', 'root');            // MySQL kullanıcı adınız (XAMPP'de varsayılan root)
define('DB_PASS', '');                // MySQL şifreniz (XAMPP'de varsayılan boş)
define('DB_NAME', 'turkelinet_db');   // Bir önceki adımda oluşturduğunuz veritabanı adı
define('DB_CHARSET', 'utf8mb4');      // Karakter seti

// Site Temel URL'si (Projenizin çalıştığı adres)
// Örnek: http://localhost/turkelinet/public/
// Sonuna / (slash) eklemeyi unutmayın!
define('BASE_URL', 'http://localhost/turkelinet/public/'); // Kendi adresinize göre güncelleyin

// Diğer site ayarları buraya eklenebilir
define('SITE_NAME', 'Türkelinet');

?>

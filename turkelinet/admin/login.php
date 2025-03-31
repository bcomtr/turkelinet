<?php
// admin/login.php
// Add error reporting to catch potential issues early
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (isset($_SESSION['admin_user_id'])) {
    header('Location: index.php');
    exit;
}
// require_once dirname(__DIR__) . '/app/config.php'; // Include if needed for SITE_NAME etc.

$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['error_message']);

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli Girişi - Türkelinet</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <style> body { font-family: 'Inter', sans-serif; } .loader { /*...*/ } @keyframes spin { /*...*/ } </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
<div class="w-full max-w-sm bg-white rounded-lg shadow-md p-8">
    <h1 class="text-2xl font-bold text-center text-gray-700 mb-6">Admin Paneli Girişi</h1>
    <?php if ($error_message): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 text-sm" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>
    <form id="login-form" action="index.php" method="POST" class="space-y-5">
        <input type="hidden" name="action" value="login">
        <div>
            <label for="username_or_email" class="block text-sm font-medium text-gray-700 mb-1">Kullanıcı Adı veya E-posta</label>
            <input type="text" id="username_or_email" name="username_or_email" required autofocus class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Kullanıcı adınız veya e-posta">
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Şifre</label>
            <input type="password" id="password" name="password" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Şifreniz">
        </div>
        <div>
            <button type="submit" id="login-button" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                <span class="button-text">Giriş Yap</span>
                <span class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-5 w-5 ml-2 hidden"></span>
            </button>
        </div>
    </form>
</div>
<script> /* ... Spinner script ... */ </script>
</body>
</html>

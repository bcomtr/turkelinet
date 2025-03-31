<?php
// admin/index.php - Admin Dashboard & Login Handler (Clean Version)

session_start();

// Include necessary files (adjust paths relative to this file)
// Go up one level from /admin/ to project root
require_once dirname(__DIR__) . '/app/config.php';
require_once dirname(__DIR__) . '/app/Core/Database.php';
require_once dirname(__DIR__) . '/app/Models/User.php';

// Instantiate User model only if needed for login processing
$userModel = null;

// --- Login Processing ---
// Check if the form was submitted
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    // Instantiate model only when processing login
    try {
        $userModel = new User();
    } catch (Throwable $e) {
        $_SESSION['error_message'] = "Veritabanı modeli yüklenemedi.";
        header('Location: login.php');
        exit;
    }

    $usernameOrEmail = $_POST['username_or_email'] ?? '';
    $password = $_POST['password'] ?? '';
    $error = null;

    if (empty($usernameOrEmail) || empty($password)) {
        $error = "Kullanıcı adı/e-posta ve şifre gereklidir.";
    } else {
        try {
            $user = $userModel->loginUser($usernameOrEmail, $password);

            // Check for successful login AND admin role
            if ($user && isset($user['role']) && $user['role'] === 'admin') {
                // Login successful for an admin
                session_regenerate_id(true); // Prevent session fixation
                // Store admin-specific session data
                $_SESSION['admin_user_id'] = $user['user_id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_fullname'] = $user['full_name'];
                // Redirect to the dashboard (this same file, GET request)
                header('Location: index.php');
                exit;
            } else {
                // Login failed or user is not an admin
                $error = "Geçersiz kimlik bilgileri veya admin yetkisi yok.";
            }
        } catch (Exception $e) {
            error_log("Admin login error: " . $e->getMessage());
            $error = "Giriş sırasında bir veritabanı hatası oluştu.";
        }
    }

    // If login failed, set error message in session and redirect back to login form
    if ($error) {
        $_SESSION['error_message'] = $error;
        header('Location: login.php');
        exit;
    }
}

// --- Dashboard Access Check ---
// Check if admin user is logged in via session
if (!isset($_SESSION['admin_user_id'])) {
    // Not logged in as admin, redirect to login page
    // Set error message only if no specific login error occurred above
    if (!isset($_SESSION['error_message'])) {
        $_SESSION['error_message'] = "Lütfen önce giriş yapın.";
    }
    header('Location: login.php');
    exit;
}

// If we reach here, the user is logged in as admin.
$adminFullName = $_SESSION['admin_fullname'] ?? $_SESSION['admin_username'];

// Determine which admin page to show (simple routing within admin)
$admin_page = $_GET['page'] ?? 'dashboard'; // Default to dashboard

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli - <?php echo SITE_NAME; ?></title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Sidebar styles */
        #sidebar { transition: width 0.3s ease; }
        #sidebar.collapsed { width: 4rem; /* 64px */ }
        #sidebar:not(.collapsed) { width: 16rem; /* 256px */ }
        #sidebar .menu-text { transition: opacity 0.1s ease 0.1s; white-space: nowrap; } /* Delay text fade */
        #sidebar.collapsed .menu-text { opacity: 0; pointer-events: none; }
        #sidebar:not(.collapsed) .menu-text { opacity: 1; }
        /* Lucide Icons */
        @font-face { font-family: 'LucideIcons'; src: url(https://cdn.jsdelivr.net/npm/lucide-static@latest/font/lucide.ttf) format('truetype'); }
        .lucide { font-family: 'LucideIcons'; font-size: 1.25rem; line-height: 1; vertical-align: middle; display: inline-block; }
        .nav-link.active { background-color: #374151; /* bg-gray-700 */ }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-100">
<div class="flex h-screen">


    <aside id="sidebar" class="bg-gray-800 text-gray-100 flex flex-col collapsed overflow-hidden">
        <div class="p-4 flex items-center justify-center h-16 border-b border-gray-700 flex-shrink-0">

            <span class="lucide text-teal-400 text-2xl cursor-pointer" id="sidebar-logo">&#xe9ae;</span>
            <span class="menu-text text-xl font-semibold ml-2">Admin</span>
        </div>
        <nav class="flex-grow p-2 space-y-1 overflow-y-auto">

            <a href="index.php?page=dashboard" class="nav-link flex items-center p-2 space-x-3 rounded-md hover:bg-gray-700 <?php echo ($admin_page === 'dashboard' ? 'active' : 'text-gray-400'); ?>">
                <span class="lucide flex-shrink-0">&#xe86f;</span>
                <span class="menu-text">Kontrol Paneli</span>
            </a>
            <a href="index.php?page=manage-posts" class="nav-link flex items-center p-2 space-x-3 rounded-md hover:bg-gray-700 <?php echo ($admin_page === 'manage-posts' ? 'active' : 'text-gray-400'); ?>">
                <span class="lucide flex-shrink-0">&#xe8d0;</span>
                <span class="menu-text">Haberleri Yönet</span>
            </a>
            <a href="index.php?page=manage-listings" class="nav-link flex items-center p-2 space-x-3 rounded-md hover:bg-gray-700 <?php echo ($admin_page === 'manage-listings' ? 'active' : 'text-gray-400'); ?>">
                <span class="lucide flex-shrink-0">&#xe8a8;</span>
                <span class="menu-text">Rehberi Yönet</span>
            </a>
            <a href="index.php?page=manage-events" class="nav-link flex items-center p-2 space-x-3 rounded-md hover:bg-gray-700 <?php echo ($admin_page === 'manage-events' ? 'active' : 'text-gray-400'); ?>">
                <span class="lucide flex-shrink-0">&#xe7ee;</span>
                <span class="menu-text">Etkinlikleri Yönet</span>
            </a>
            <a href="index.php?page=manage-comments" class="nav-link flex items-center p-2 space-x-3 rounded-md hover:bg-gray-700 <?php echo ($admin_page === 'manage-comments' ? 'active' : 'text-gray-400'); ?>">
                <span class="lucide flex-shrink-0">&#xe8b9;</span>
                <span class="menu-text">Yorumları Yönet</span>
            </a>
            <a href="index.php?page=manage-users" class="nav-link flex items-center p-2 space-x-3 rounded-md hover:bg-gray-700 <?php echo ($admin_page === 'manage-users' ? 'active' : 'text-gray-400'); ?>">
                <span class="lucide flex-shrink-0">&#xe9a4;</span>
                <span class="menu-text">Kullanıcıları Yönet</span>
            </a>

        </nav>
        <div class="p-2 border-t border-gray-700 flex-shrink-0">

            <a href="../public/" target="_blank" class="flex items-center p-2 space-x-3 rounded-md hover:bg-gray-700 text-gray-400" title="Siteyi yeni sekmede aç">
                <span class="lucide flex-shrink-0">&#xe85f;</span>
                <span class="menu-text">Siteyi Görüntüle</span>
            </a>

            <a href="index.php?action=logout" class="flex items-center p-2 space-x-3 rounded-md hover:bg-red-800 hover:text-white text-red-400">
                <span class="lucide flex-shrink-0">&#xe89e;</span>
                <span class="menu-text">Admin Çıkış</span>
            </a>
        </div>
    </aside>


    <div class="flex-1 flex flex-col">
        <header class="bg-white shadow h-16 flex items-center justify-between px-6 flex-shrink-0">

            <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring" aria-label="Kenar Çubuğunu Aç/Kapat">
                <span class="lucide">&#xe8a7;</span>
            </button>
            <div class="text-sm font-medium">
                Hoşgeldin, <?php echo htmlspecialchars($adminFullName); ?>
            </div>
        </header>
        <main class="flex-1 p-6 overflow-y-auto">

            <?php
            // Simple Admin Page Routing within admin/index.php
            $adminViewPath = 'views/' . $admin_page . '.php';

            if (file_exists($adminViewPath)) {
                include $adminViewPath;
            } elseif ($admin_page === 'dashboard') {
                echo '<h1 class="text-2xl font-semibold text-gray-800 mb-4">Kontrol Paneli</h1>';
                echo '<div class="bg-white p-6 rounded-lg shadow">';
                echo '<p>Admin paneline hoş geldiniz. Buradan site içeriğini yönetebilirsiniz.</p>';
                echo '<p class="mt-4">Sol menüden yönetmek istediğiniz bölümü seçin.</p>';
                echo '</div>';
            } else {
                echo '<h1 class="text-xl font-semibold text-red-600 mb-4">Sayfa Bulunamadı</h1>';
                echo '<div class="bg-white p-6 rounded-lg shadow">';
                echo "<p>İstenen admin sayfası ('" . htmlspecialchars($admin_page) . "') için görünüm dosyası (views/{$admin_page}.php) bulunamadı.</p>";
                echo '</div>';
            }

            // --- Admin Logout Processing ---
            if (isset($_GET['action']) && $_GET['action'] === 'logout') {
                unset($_SESSION['admin_user_id'], $_SESSION['admin_username'], $_SESSION['admin_fullname']);
                $_SESSION['error_message'] = "Başarıyla çıkış yaptınız.";
                header('Location: login.php'); exit;
            }
            ?>

        </main>
    </div>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.getElementById('sidebar-toggle');
    const logo = document.getElementById('sidebar-logo');

    const toggleSidebar = () => { if(sidebar) sidebar.classList.toggle('collapsed'); };
    if(toggleButton) { toggleButton.addEventListener('click', toggleSidebar); }
    if(logo) { logo.addEventListener('click', toggleSidebar); }
</script>
</body>
</html>

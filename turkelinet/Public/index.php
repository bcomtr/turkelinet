<?php
// public/index.php - FINAL COMPLETE VERSION (v32)

// Enable error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Include base configuration and core files
require_once dirname(__DIR__) . '/app/config.php';
require_once dirname(__DIR__) . '/app/Core/Database.php';

// Include all models
require_once dirname(__DIR__) . '/app/Models/Post.php';
require_once dirname(__DIR__) . '/app/Models/Comment.php';
require_once dirname(__DIR__) . '/app/Models/Listing.php';
require_once dirname(__DIR__) . '/app/Models/Event.php';
require_once dirname(__DIR__) . '/app/Models/Forum.php';
require_once dirname(__DIR__) . '/app/Models/User.php';

// Determine the requested page, default to 'home'
$page = isset($_GET['page']) ? preg_replace('/[^a-zA-Z0-9-]/', '', $_GET['page']) : 'home';
$pageTitle = SITE_NAME; // Default page title
$error_message = null; // Variable for error messages

// Define variables potentially used in views with default values
$latestPosts = []; $post = null; $comments = []; $listings = []; $listing = null;
$events = []; $topics = []; $topic = null; $posts = []; // $posts used for multiple contexts
$currentPage = 1; $totalPages = 0;
$errors = []; $old_input = []; $success_message = null;

// Instantiate models (handle potential errors)
try {
    $postModel = new Post();
    $commentModel = new Comment();
    $listingModel = new Listing();
    $eventModel = new Event();
    $forumModel = new Forum();
    $userModel = new User();
} catch (Throwable $e) { // Catch Throwable for broader error catching (PHP 7+)
    error_log("Model instantiation error: " . $e->getMessage());
    $page = 'error';
    $error_message = "Site altyapısında kritik bir sorun oluştu. Lütfen daha sonra tekrar deneyin.";
    http_response_code(500);
    $pageTitle = 'Sistem Hatası';
}

// --- Routing Logic ---

// Only proceed with page-specific logic if no critical error occurred
if ($page !== 'error') {

    // == Homepage Route ==
    if ($page == 'home') {
        $pageTitle = 'Ana Sayfa';
        try {
            $latestPosts = $postModel->getLatestPosts(5);
            $latestPosts = $latestPosts ? $latestPosts : [];
        } catch (Exception $e) { error_log("Homepage data fetching error: " . $e->getMessage()); $latestPosts = []; $error_message = "Ana sayfa verileri yüklenirken bir sorun oluştu."; }
    }

    // == News List Route ==
    elseif ($page == 'haberler') {
        $pageTitle = 'Tüm Haberler';
        try {
            // TODO: Implement pagination for news later
            $posts = $postModel->getAllPublishedPosts(); // $posts used here
            $posts = $posts ? $posts : [];
        } catch (Exception $e) { error_log("News list page data fetching error: " . $e->getMessage()); $posts = []; $error_message = "Haberler yüklenirken bir sorun oluştu."; }
    }

    // == Single News Post Route ==
    elseif ($page == 'haber') {
        $slug = isset($_GET['slug']) ? trim($_GET['slug']) : null;
        if ($slug) {
            try {
                $post = $postModel->getPostBySlug($slug); // Assign to $post
                if ($post) {
                    $pageTitle = $post['title'];
                    $comments = $commentModel->getCommentsByPostId($post['post_id']);
                    $comments = $comments ? $comments : [];
                } else { $page = '404'; http_response_code(404); $pageTitle = 'Sayfa Bulunamadı'; }
            } catch (Exception $e) { error_log("Single post/comments fetching error (slug: {$slug}): " . $e->getMessage()); $error_message = "Haber veya yorumlar yüklenirken bir sorun oluştu."; $page = 'error'; http_response_code(500); $pageTitle = 'Hata'; }
        } else { $page = '404'; http_response_code(404); $pageTitle = 'Sayfa Bulunamadı'; }
    }

    // == Add Comment Route ==
    elseif ($page == 'yorum-ekle') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
            $commentText = isset($_POST['comment_text']) ? trim($_POST['comment_text']) : '';
            $postSlug = isset($_POST['post_slug']) ? trim($_POST['post_slug']) : '';
            $userId = $_SESSION['user_id'] ?? 0; // Get logged-in user ID
            if ($userId <= 0) { header('Location: ' . BASE_URL . '?page=giris&status=login_required'); exit; } // Redirect if not logged in

            if ($postId > 0 && !empty($commentText) && $userId > 0 && !empty($postSlug)) {
                try {
                    $newCommentId = $commentModel->addComment($postId, $userId, $commentText, null, true); // Add comment
                    if ($newCommentId) {
                        header('Location: ' . BASE_URL . '?page=haber&slug=' . urlencode($postSlug) . '#comments'); exit; // Redirect back to post
                    } else { $_SESSION['flash_error'] = "Yorum eklenirken bir hata oluştu."; header('Location: ' . BASE_URL . '?page=haber&slug=' . urlencode($postSlug) . '#comment-form'); exit; }
                } catch (Exception $e) { error_log("Error adding comment: " . $e->getMessage()); $_SESSION['flash_error'] = "Yorum eklenirken bir hata oluştu."; header('Location: ' . BASE_URL . '?page=haber&slug=' . urlencode($postSlug) . '#comment-form'); exit; }
            } else { $_SESSION['flash_error'] = "Lütfen yorum alanını boş bırakmayın."; if (!empty($postSlug)) { header('Location: ' . BASE_URL . '?page=haber&slug=' . urlencode($postSlug) . '#comment-form'); } else { header('Location: ' . BASE_URL); } exit; }
        } else { header('Location: ' . BASE_URL); exit; } // Redirect if not POST
    }

    // == Listings Route ==
    elseif ($page == 'rehber') {
        $pageTitle = 'Türkeli Rehberi';
        try { $listings = $listingModel->getPublishedListings(0); $listings = $listings ? $listings : []; } catch (Exception $e) { error_log("Listings page data fetching error: " . $e->getMessage()); $listings = []; $error_message = "Rehber öğeleri yüklenirken bir sorun oluştu."; }
    }

    // == Single Listing Route ==
    elseif ($page == 'rehber-detay') {
        $listingId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($listingId > 0) {
            try { $listing = $listingModel->getListingById($listingId); if ($listing) { $pageTitle = $listing['name']; } else { $page = '404'; http_response_code(404); $pageTitle = 'Sayfa Bulunamadı'; } } catch (Exception $e) { error_log("Single listing fetching error (ID: {$listingId}): " . $e->getMessage()); $error_message = "Mekan bilgileri yüklenirken bir sorun oluştu."; $page = 'error'; http_response_code(500); $pageTitle = 'Hata'; }
        } else { $page = '404'; http_response_code(404); $pageTitle = 'Sayfa Bulunamadı'; }
    }

    // == Events Route ==
    elseif ($page == 'etkinlikler') {
        $pageTitle = 'Etkinlik Takvimi';
        try { $events = $eventModel->getUpcomingEvents(0); $events = $events ? $events : []; } catch (Exception $e) { error_log("Events page data fetching error: " . $e->getMessage()); $events = []; $error_message = "Etkinlikler yüklenirken bir sorun oluştu."; }
    }

    // == Forum Topics Route (with Pagination) ==
    elseif ($page == 'forum') {
        $pageTitle = 'Forum';
        try {
            $topicsPerPage = 15; $currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1; if ($currentPage < 1) $currentPage = 1;
            $totalTopics = $forumModel->getTotalTopicCount(); $totalPages = 0; $topics = [];
            if ($totalTopics !== false && $totalTopics > 0) { $totalPages = ceil($totalTopics / $topicsPerPage); if ($currentPage > $totalPages) { $currentPage = $totalPages; } $topics = $forumModel->getTopics($topicsPerPage, $currentPage); } elseif ($totalTopics === false) { $error_message = "Toplam konu sayısı alınamadı."; }
            $topics = $topics ? $topics : [];
        } catch (Exception $e) { error_log("Forum topics fetching error: " . $e->getMessage()); $error_message = "Forum konuları yüklenirken bir sorun oluştu."; $topics = []; $currentPage = 1; $totalPages = 0; }
    }

    // == Single Forum Topic Route ==
    elseif ($page == 'konu') {
        $topicId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($topicId > 0) {
            try {
                $topic = $forumModel->getTopicById($topicId); // Assign to $topic
                if ($topic) { $pageTitle = $topic['title']; $posts = $forumModel->getPostsByTopicId($topicId, 50, 0); $posts = $posts ? $posts : []; } else { $page = '404'; http_response_code(404); $pageTitle = 'Konu Bulunamadı'; }
            } catch (Exception $e) { error_log("Single topic/posts fetching error (ID: {$topicId}): " . $e->getMessage()); $error_message = "Konu veya mesajlar yüklenirken bir sorun oluştu."; $page = 'error'; http_response_code(500); $pageTitle = 'Hata'; }
        } else { $page = '404'; http_response_code(404); $pageTitle = 'Geçersiz Konu ID'; }
    }

    // == Add Forum Reply Route ==
    elseif ($page == 'yanit-ekle') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $topicId = isset($_POST['topic_id']) ? (int)$_POST['topic_id'] : 0; $replyContent = isset($_POST['reply_content']) ? trim($_POST['reply_content']) : '';
            $userId = $_SESSION['user_id'] ?? 0; if ($userId <= 0) { header('Location: ' . BASE_URL . '?page=giris&status=login_required'); exit; }
            if ($topicId > 0 && !empty($replyContent) && $userId > 0) {
                try { $topic = $forumModel->getTopicById($topicId); if ($topic && !$topic['is_locked']) { $newPostId = $forumModel->addPost($topicId, $userId, $replyContent); if ($newPostId) { $forumModel->updateTopicLastReply($topicId); header('Location: ' . BASE_URL . '?page=konu&id=' . $topicId . '#post-' . $newPostId); exit; } else { $_SESSION['flash_error'] = "Yanıt eklenirken hata oluştu."; header('Location: ' . BASE_URL . '?page=konu&id=' . $topicId . '#reply-form'); exit; } } else { $_SESSION['flash_error'] = "Konu bulunamadı veya kilitli."; header('Location: ' . BASE_URL . '?page=konu&id=' . $topicId); exit; } } catch (Exception $e) { error_log("Error adding forum reply: " . $e->getMessage()); $_SESSION['flash_error'] = "Yanıt eklenirken hata oluştu."; header('Location: ' . BASE_URL . '?page=konu&id=' . $topicId . '#reply-form'); exit; }
            } else { $_SESSION['flash_error'] = "Lütfen yanıt alanını boş bırakmayın."; header('Location: ' . BASE_URL . '?page=konu&id=' . $topicId . '#reply-form'); exit; }
        } else { header('Location: ' . BASE_URL . '?page=forum'); exit; }
    }

    // == Show New Topic Form Route ==
    elseif ($page == 'yeni-konu') {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . BASE_URL . '?page=giris&status=login_required'); exit; }
        $pageTitle = 'Yeni Forum Konusu Aç';
        $errors = $_SESSION['errors'] ?? []; $old_input = $_SESSION['old_input'] ?? []; unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // == Handle New Topic Submission Route ==
    elseif ($page == 'yeni-konu-kaydet') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = isset($_POST['title']) ? trim($_POST['title']) : ''; $content = isset($_POST['content']) ? trim($_POST['content']) : '';
            $userId = $_SESSION['user_id'] ?? 0; if ($userId <= 0) { header('Location: ' . BASE_URL . '?page=giris&status=login_required'); exit; }
            $errors = [];
            if (empty($title)) { $errors['title'] = "Başlık boş olamaz."; }
            if (empty($content)) { $errors['content'] = "İçerik boş olamaz."; }

            if (empty($errors)) {
                try { $newTopicId = $forumModel->addTopic($userId, $title, $content); if ($newTopicId) { header('Location: ' . BASE_URL . '?page=konu&id=' . $newTopicId); exit; } else { $errors['general'] = "Konu eklenirken bir veritabanı hatası oluştu."; error_log("Failed to add new topic by user ID: {$userId}"); } } catch (Exception $e) { error_log("Error saving new topic: " . $e->getMessage()); $errors['general'] = "Konu eklenirken beklenmedik bir hata oluştu."; }
            }
            // If errors occurred, redirect back to form
            if (!empty($errors)) { $_SESSION['errors'] = $errors; $_SESSION['old_input'] = ['title' => $title, 'content' => $content]; header('Location: ' . BASE_URL . '?page=yeni-konu'); exit; }
        } else { header('Location: ' . BASE_URL . '?page=forum'); exit; }
    }

    // == Show Registration Form Route ==
    elseif ($page == 'kayit') {
        if (isset($_SESSION['user_id'])) { header('Location: ' . BASE_URL); exit; }
        $pageTitle = 'Hesap Oluştur'; $errors = $_SESSION['errors'] ?? []; $old_input = $_SESSION['old_input'] ?? []; unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // == Handle Registration Submission Route ==
    elseif ($page == 'kayit-ol') {
        if (isset($_SESSION['user_id'])) { header('Location: ' . BASE_URL); exit; }
        $pageTitle = 'Kayıt İşlemi';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = isset($_POST['full_name']) ? trim($_POST['full_name']) : ''; $username = isset($_POST['username']) ? trim($_POST['username']) : ''; $email = isset($_POST['email']) ? trim($_POST['email']) : ''; $password = isset($_POST['password']) ? $_POST['password'] : ''; $passwordConfirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : ''; $errors = [];
            // --- Validation Logic ---
            if (empty($fullName)) { $errors['full_name'] = "Ad Soyad alanı zorunludur."; }
            if (empty($username)) { $errors['username'] = "Kullanıcı adı zorunludur."; } elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) { $errors['username'] = "Kullanıcı adı 3-20 krk, harf/rakam/_ içermeli."; } elseif ($userModel->findByUsername($username)) { $errors['username'] = "Bu kullanıcı adı zaten alınmış."; }
            if (empty($email)) { $errors['email'] = "E-posta alanı zorunludur."; } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors['email'] = "Geçerli bir e-posta adresi girin."; } elseif ($userModel->findByEmail($email)) { $errors['email'] = "Bu e-posta adresi zaten kayıtlı."; }
            if (empty($password)) { $errors['password'] = "Şifre alanı zorunludur."; } elseif (strlen($password) < 6) { $errors['password'] = "Şifre en az 6 karakter olmalıdır."; }
            if (empty($passwordConfirm)) { $errors['password_confirm'] = "Şifre tekrar alanı zorunludur."; } elseif ($password !== $passwordConfirm) { $errors['password_confirm'] = "Şifreler eşleşmiyor."; }
            // --- End Validation ---
            if (empty($errors)) {
                $userData = [ 'full_name' => $fullName, 'username' => $username, 'email' => $email, 'password' => $password ];
                try { $newUserId = $userModel->registerUser($userData); if ($newUserId) { $_SESSION['success_message'] = "Hesabınız başarıyla oluşturuldu! Şimdi giriş yapabilirsiniz."; header('Location: ' . BASE_URL . '?page=giris'); exit; } else { $errors['general'] = "Kayıt sırasında bir veritabanı hatası oluştu (Kullanıcı adı veya e-posta zaten mevcut olabilir)."; error_log("User registration failed for email: {$email}"); } } catch (Exception $e) { error_log("Registration exception: " . $e->getMessage()); $errors['general'] = "Kayıt sırasında beklenmedik bir hata oluştu."; }
            }
            if (!empty($errors)) { $_SESSION['errors'] = $errors; $_SESSION['old_input'] = [ 'full_name' => $fullName, 'username' => $username, 'email' => $email ]; header('Location: ' . BASE_URL . '?page=kayit'); exit; }
        } else { header('Location: ' . BASE_URL . '?page=kayit'); exit; }
    }

    // == Show Login Form Route ==
    elseif ($page == 'giris') {
        if (isset($_SESSION['user_id'])) { header('Location: ' . BASE_URL); exit; }
        $pageTitle = 'Giriş Yap'; $success_message = $_SESSION['success_message'] ?? null; $error_message = $_SESSION['error_message'] ?? null; $old_input = $_SESSION['old_input'] ?? []; unset($_SESSION['success_message'], $_SESSION['error_message'], $_SESSION['old_input']);
    }

    // == Handle Login Submission Route ==
    elseif ($page == 'giris-yap') {
        if (isset($_SESSION['user_id'])) { header('Location: ' . BASE_URL); exit; }
        $pageTitle = 'Giriş Yapılıyor...';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usernameOrEmail = isset($_POST['username_or_email']) ? trim($_POST['username_or_email']) : ''; $password = isset($_POST['password']) ? $_POST['password'] : ''; $error = null;
            if (empty($usernameOrEmail) || empty($password)) { $error = "Kullanıcı adı/e-posta ve şifre alanları zorunludur."; } else { try { $user = $userModel->loginUser($usernameOrEmail, $password); if ($user) { session_regenerate_id(true); $_SESSION['user_id'] = $user['user_id']; $_SESSION['username'] = $user['username']; $_SESSION['user_role'] = $user['role']; $_SESSION['user_fullname'] = $user['full_name']; header('Location: ' . BASE_URL); exit; } else { $error = "Geçersiz kullanıcı adı/e-posta veya şifre."; } } catch (Exception $e) { error_log("Login error: " . $e->getMessage()); $error = "Giriş sırasında bir hata oluştu."; } }
            if ($error) { $_SESSION['error_message'] = $error; $_SESSION['old_input'] = ['username_or_email' => $usernameOrEmail]; header('Location: ' . BASE_URL . '?page=giris'); exit; }
        } else { header('Location: ' . BASE_URL . '?page=giris'); exit; }
    }

    // == Logout Route ==
    elseif ($page == 'cikis-yap') {
        $_SESSION = array(); if (ini_get("session.use_cookies")) { $params = session_get_cookie_params(); setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]); } session_destroy(); header('Location: ' . BASE_URL); exit;
    }

    // Add other page routes here...

} // End error check

// Determine the view file path
$viewPath = dirname(__DIR__) . '/app/Views/' . $page . '.php';

// Include the header (passes $pageTitle)
require_once dirname(__DIR__) . '/app/Views/partials/header.php';

// Check if the view file exists and include it, otherwise show 404/error content
if (file_exists($viewPath)) {
    // Make variables available to the view scope
    // (No need for extract if variables are defined in this scope before this point)
    require_once $viewPath;
} else {
    // Handle 404 / Error display
    if ($page !== '404' && $page !== 'error') { http_response_code(404); }
    echo '<main class="container mx-auto px-4 py-8 text-center">';
    if (isset($error_message) && $page === 'error') {
        echo '<h1 class="text-4xl font-bold text-red-600 mb-4">Bir Hata Oluştu</h1>';
        echo '<p class="text-gray-600 dark:text-gray-400">' . htmlspecialchars($error_message ?? 'Bilinmeyen bir hata oluştu.') . '</p>';
    } else { // Otherwise show 404
        $pageTitle = 'Sayfa Bulunamadı';
        echo '<h1 class="text-4xl font-bold text-red-600 mb-4">404 - Sayfa Bulunamadı</h1>';
        echo '<p class="text-gray-600 dark:text-gray-400">Aradığınız sayfa mevcut değil veya taşınmış olabilir.</p>';
    }
    echo '<a href="' . BASE_URL . '" class="mt-6 inline-block bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-5 rounded-lg">Ana Sayfaya Dön</a>';
    echo '</main>';
}

// Include the footer
require_once dirname(__DIR__) . '/app/Views/partials/footer.php';

?>

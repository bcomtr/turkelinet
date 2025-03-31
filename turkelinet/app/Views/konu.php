<?php
// app/Views/konu.php - Clean Version

// Check if topic and posts data exist and are not empty/false
if (!isset($topic) || empty($topic) || !isset($posts)) { // Check $topic with empty()
    echo '<main class="container mx-auto px-4 py-8 text-center"><p>Konu yüklenirken bir sorun oluştu veya konu bulunamadı.</p></main>';
    return;
}
// Ensure $posts is an array even if empty
$posts = isset($posts) ? $posts : [];

// Helper function for relative time
function time_ago_topic($datetime) {
    if(!$datetime) return '-';
    try {
        $now = new DateTime; $ago = new DateTime($datetime); $diff = $now->diff($ago);
        $string = ['y' => 'yıl', 'm' => 'ay', 'd' => 'gün', 'h' => 'saat', 'i' => 'dakika', 's' => 'saniye'];
        foreach ($string as $k => &$v) { if ($diff->$k) { $plural = ($diff->$k > 1 && $k != 'm') ? '' : ''; $v = $diff->$k . ' ' . $v . $plural; } else { unset($string[$k]); } }
        $string = array_slice($string, 0, 1); return $string ? implode(', ', $string) . ' önce' : 'az önce';
    } catch (Exception $e) { error_log("Time ago function error: " . $e->getMessage()); return '-'; }
}

?>

<main class="container mx-auto px-4 py-8">

    <nav class="text-sm mb-4 text-gray-500 dark:text-gray-400" aria-label="breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="<?php echo BASE_URL; ?>?page=forum" class="hover:text-teal-600 dark:hover:text-teal-400">Forum</a>
                <span class="lucide mx-2 text-xs">&#xe7c2;</span>
            </li>
            <li class="flex items-center">
                <span class="text-gray-700 dark:text-gray-200" aria-current="page"><?php echo htmlspecialchars($topic['title']); ?></span>
            </li>
        </ol>
    </nav>


    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-6">
        <?php if ($topic['is_sticky']): ?><span class="lucide text-2xl text-yellow-500 mr-2" title="Sabitlenmiş Konu">&#xe974;</span><?php endif; ?>
        <?php if ($topic['is_locked']): ?><span class="lucide text-2xl text-red-500 mr-2" title="Kilitli Konu">&#xe89a;</span><?php endif; ?>
        <?php echo htmlspecialchars($topic['title']); ?>
    </h1>

    <div class="space-y-6">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post_item): ?>
                <div id="post-<?php echo $post_item['post_id']; ?>" class="flex items-start space-x-4 bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-6">
                    <div class="flex-shrink-0 text-center w-16 md:w-24">
                        <img src="<?php echo BASE_URL . 'uploads/avatars/' . htmlspecialchars($post_item['profile_picture'] ?? 'default.png'); ?>" alt="<?php echo htmlspecialchars($post_item['author_name'] ?? 'Kullanıcı'); ?>" class="w-12 h-12 md:w-16 md:h-16 rounded-full mx-auto mb-1" onerror="this.onerror=null; this.src='https://placehold.co/64x64/e2e8f0/a0aec0?text=<?php echo mb_substr($post_item['author_name'] ?? 'K', 0, 1); ?>';">
                        <span class="font-semibold text-gray-800 dark:text-gray-100 text-sm block truncate" title="<?php echo htmlspecialchars($post_item['author_name'] ?? 'Bilinmeyen Kullanıcı'); ?>">
                            <?php echo htmlspecialchars($post_item['author_name'] ?? 'Bilinmeyen'); ?>
                        </span>
                    </div>
                    <div class="flex-grow border-l border-gray-200 dark:border-gray-700 pl-4 sm:pl-6">
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                            <time datetime="<?php echo date('Y-m-d H:i', strtotime($post_item['created_at'])); ?>">
                                <?php echo time_ago_topic($post_item['created_at']); ?>
                            </time>
                        </div>
                        <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
                            <?php echo nl2br(htmlspecialchars($post_item['content'])); ?>
                        </div>
                        <div class="text-xs space-x-4 mt-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                            <a href="#reply-form" class="text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 font-medium"><span class="lucide text-xs mr-0.5">&#xe93a;</span> Yanıtla</a>
                            <a href="#" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 font-medium"><span class="lucide text-xs mr-0.5">&#xe854;</span> Bildir</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-500 dark:text-gray-400 text-center py-8">Bu konuda henüz hiç mesaj yok.</p>
        <?php endif; ?>
    </div>

    <?php // TODO: Add pagination for posts here ?>

    <div id="reply-form" class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
        <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Yanıt Yaz</h4>
        <?php if ($topic['is_locked']): ?>
            <p class="text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900 dark:bg-opacity-30 p-4 rounded-lg text-center">Bu konu kilitlenmiştir, yeni yanıt yazılamaz.</p>
        <?php else: ?>
            <?php // TODO: Add check if user is logged in ?>
            <form action="<?php echo BASE_URL; ?>?page=yanit-ekle" method="POST" class="space-y-4">
                <input type="hidden" name="topic_id" value="<?php echo $topic['topic_id']; ?>">
                <div>
                    <label for="reply_content" class="sr-only">Yanıtınız</label>
                    <textarea id="reply_content" name="reply_content" rows="5" required
                              class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                              placeholder="Yanıtınızı buraya yazın..."></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 dark:bg-teal-700 dark:hover:bg-teal-600 text-white font-semibold py-2.5 px-6 rounded-lg text-sm flex items-center transition duration-200 ease-in-out">
                        <span class="lucide mr-1.5 text-sm">&#xe956;</span> Yanıtı Gönder
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>

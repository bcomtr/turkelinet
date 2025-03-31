<?php
// app/Views/haber.php - Comment Form Restored

// Check if the $post variable exists
if (!isset($post) || empty($post)) {
    echo '<main class="container mx-auto px-4 py-8 text-center"><p>Haber yüklenirken bir sorun oluştu veya haber bulunamadı.</p></main>';
    return;
}
// Check if the $comments variable exists
$comments = isset($comments) ? $comments : [];

// Helper function for date formatting
function format_turkish_date_detail($datetime) {
    if(!$datetime) return '';
    try {
        if (extension_loaded('intl')) {
            $fmt = new IntlDateFormatter('tr_TR', IntlDateFormatter::LONG, IntlDateFormatter::SHORT, 'Europe/Istanbul', IntlDateFormatter::GREGORIAN);
            return $fmt->format(new DateTime($datetime));
        } else {
            $date = new DateTime($datetime); $months = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
            return $date->format('d') . ' ' . $months[$date->format('n') - 1] . ' ' . $date->format('Y H:i');
        }
    } catch (Exception $e) { return $datetime; }
}
?>

<main class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <article class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden mb-8">
            <?php if (!empty($post['featured_image'])): ?>
                <img src="<?php echo BASE_URL . 'uploads/images/' . htmlspecialchars($post['featured_image']); ?>"
                     alt="<?php echo htmlspecialchars($post['title']); ?>"
                     class="w-full h-64 md:h-96 object-cover"
                     onerror="this.onerror=null; this.style.display='none';">
            <?php endif; ?>

            <div class="p-6 md:p-8 lg:p-10">

                <div class="mb-4">
                    <a href="<?php echo BASE_URL . '?page=kategori&slug=' . htmlspecialchars($post['category_slug']); ?>"
                       class="text-sm font-semibold text-teal-700 bg-teal-100 dark:text-teal-300 dark:bg-teal-900 px-3 py-1 rounded-full hover:bg-teal-200 dark:hover:bg-teal-800 transition-colors">
                        <?php echo htmlspecialchars(strtoupper($post['category_name'])); ?>
                    </a>
                </div>

                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-gray-100 mb-4 leading-tight">
                    <?php echo htmlspecialchars($post['title']); ?>
                </h1>

                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-500 dark:text-gray-400 mb-6 border-b border-t border-gray-200 dark:border-gray-700 py-3">
                    <div class="flex items-center"> <span class="lucide mr-1.5 text-base">&#xe9a9;</span> <span><?php echo htmlspecialchars($post['author_name'] ?? 'Bilinmiyor'); ?></span> </div>
                    <div class="flex items-center"> <span class="lucide mr-1.5 text-base">&#xe7ee;</span> <time datetime="<?php echo date('Y-m-d H:i', strtotime($post['created_at'])); ?>"><?php echo format_turkish_date_detail($post['created_at']); ?></time> </div>
                    <div class="flex items-center"> <span class="lucide mr-1.5 text-base">&#xe8b9;</span> <a href="#comments" class="hover:text-teal-600 dark:hover:text-teal-400"><?php echo count($comments); ?> Yorum</a> </div>
                </div>

                <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 text-lg leading-relaxed">
                    <?php echo $post['content']; // WARNING: Sanitize this in real app! ?>
                </div>
            </div>
        </article>


        <div id="comments" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 md:p-8 lg:p-10">
            <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Yorumlar (<?php echo count($comments); ?>)</h3>
            <div class="space-y-8">

                <?php // Display comments with improved CSS ?>
                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment flex items-start space-x-4">
                            <img src="<?php echo BASE_URL . 'uploads/avatars/' . htmlspecialchars($comment['profile_picture'] ?? 'default.png'); ?>" alt="<?php echo htmlspecialchars($comment['author_name'] ?? 'Kullanıcı'); ?>" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full flex-shrink-0 mt-1" onerror="this.onerror=null; this.src='https://placehold.co/48x48/e2e8f0/a0aec0?text=<?php echo mb_substr($comment['author_name'] ?? 'K', 0, 1); ?>';">
                            <div class="flex-grow">
                                <div class="comment-meta flex flex-col sm:flex-row justify-between items-start sm:items-center mb-1.5">
                                    <span class="font-semibold text-gray-800 dark:text-gray-100 text-sm sm:text-base"><?php echo htmlspecialchars($comment['author_name'] ?? 'Bilinmeyen Kullanıcı'); ?></span>
                                    <time datetime="<?php echo date('Y-m-d H:i', strtotime($comment['created_at'])); ?>" class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 sm:mt-0"><?php echo format_turkish_date_detail($comment['created_at']); ?></time>
                                </div>
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                                <div class="comment-actions text-xs space-x-4 mt-2.5">
                                    <a href="#comment-form" class="text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 font-medium"><span class="lucide text-xs mr-0.5">&#xe93a;</span> Yanıtla</a>
                                    <a href="#" class="text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400 font-medium"><span class="lucide text-xs mr-0.5">&#xe98f;</span> Beğen</a>
                                    <a href="#" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 font-medium"><span class="lucide text-xs mr-0.5">&#xe854;</span> Bildir</a>
                                </div>
                            </div>
                        </div>
                        <hr class="border-gray-200 dark:border-gray-700 last:hidden">
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Henüz hiç yorum yapılmamış. İlk yorumu siz yapın!</p>
                <?php endif; ?>


                <?php // ----- START: Comment Form (Complete Code) ----- ?>
                <div id="comment-form" class="pt-8 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Yorum Yap</h4>
                    <?php // Check if user is logged in (using session variable) ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form action="<?php echo BASE_URL; ?>?page=yorum-ekle" method="POST" class="space-y-4">
                            <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                            <input type="hidden" name="post_slug" value="<?php echo htmlspecialchars($post['slug']); ?>">
                            <?php // TODO: Add hidden parent_comment_id field for replies ?>
                            <div>
                                <label for="comment_text" class="sr-only">Yorumunuz</label>
                                <textarea id="comment_text" name="comment_text" rows="4" required
                                          class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                                          placeholder="Yorumunuzu buraya yazın..."></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-teal-600 hover:bg-teal-700 dark:bg-teal-700 dark:hover:bg-teal-600 text-white font-semibold py-2.5 px-6 rounded-lg text-sm flex items-center transition duration-200 ease-in-out">
                                    <span class="lucide mr-1.5 text-sm">&#xe956;</span> Yorumu Gönder
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <p class="text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 p-4 rounded-lg text-center">
                            Yorum yapmak için <a href="<?php echo BASE_URL; ?>?page=giris" class="text-teal-600 dark:text-teal-400 font-semibold hover:underline">giriş yapmanız</a> gerekmektedir.
                        </p>
                    <?php endif; ?>
                </div>
                <?php // ----- END: Comment Form ----- ?>
            </div>
        </div>
    </div>
</main>

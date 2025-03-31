<?php
// app/Views/forum.php

$topics = isset($topics) ? $topics : [];
$error_message = isset($error_message) ? $error_message : null;
$currentPage = isset($currentPage) ? $currentPage : 1;
$totalPages = isset($totalPages) ? $totalPages : 0;

// Helper function for relative time
function time_ago($datetime) {
    if(!$datetime) return '-'; try { $now = new DateTime; $ago = new DateTime($datetime); $diff = $now->diff($ago); $string = ['y' => 'yıl', 'm' => 'ay', 'd' => 'gün', 'h' => 'saat', 'i' => 'dakika', 's' => 'saniye']; foreach ($string as $k => &$v) { if ($diff->$k) { $plural = ($diff->$k > 1 && $k != 'm') ? '' : ''; $v = $diff->$k . ' ' . $v . $plural; } else { unset($string[$k]); } } $string = array_slice($string, 0, 1); return $string ? implode(', ', $string) . ' önce' : 'az önce'; } catch (Exception $e) { error_log("Time ago function error: " . $e->getMessage()); return '-'; }
}
?>

<main class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 border-l-4 border-teal-600 dark:border-teal-500 pl-3">
            Forum Konuları
        </h1>
        <a href="<?php echo BASE_URL; ?>?page=yeni-konu" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-4 rounded-lg text-sm flex items-center">
            <span class="lucide mr-1.5 text-sm">&#xe91f;</span> Yeni Konu Aç
        </a>
    </div>

    <?php if ($error_message): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Hata!</strong>
            <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
        </div>
    <?php endif; ?>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Konu Başlığı</th>
                    <th scope="col" class="px-6 py-3 hidden md:table-cell">Başlatan</th>
                    <th scope="col" class="px-6 py-3 text-center">Yanıtlar</th>
                    <th scope="col" class="px-6 py-3 hidden sm:table-cell text-right">Son Aktivite</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($topics)): ?>
                    <?php foreach ($topics as $topic): ?>
                        <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                <?php if ($topic['is_sticky']): ?><span class="lucide text-xs text-yellow-500 mr-1" title="Sabitlenmiş Konu">&#xe974;</span><?php endif; ?>
                                <?php if ($topic['is_locked']): ?><span class="lucide text-xs text-red-500 mr-1" title="Kilitli Konu">&#xe89a;</span><?php endif; ?>
                                <a href="<?php echo BASE_URL . '?page=konu&id=' . $topic['topic_id']; ?>" class="hover:text-teal-600 dark:hover:text-teal-400">
                                    <?php echo htmlspecialchars($topic['title']); ?>
                                </a>
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell"><?php echo htmlspecialchars($topic['author_name'] ?? 'Bilinmiyor'); ?></td>
                            <td class="px-6 py-4 text-center"><?php echo number_format($topic['reply_count'] > 0 ? $topic['reply_count'] -1 : 0); ?></td>
                            <td class="px-6 py-4 hidden sm:table-cell text-right text-xs whitespace-nowrap"><?php echo time_ago($topic['actual_last_reply_at'] ?? $topic['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="bg-white dark:bg-gray-800"><td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Henüz hiç forum konusu açılmamış.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php // ----- YENİ: Sayfalama Kontrolleri ----- ?>
    <?php if ($totalPages > 1): ?>
        <nav class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 sm:px-6 mt-4 rounded-b-lg shadow">
            <div class="flex flex-1 justify-between sm:hidden">
                <?php if ($currentPage > 1): ?>
                    <a href="<?php echo BASE_URL . '?page=forum&p=' . ($currentPage - 1); ?>" class="relative inline-flex items-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Önceki</a>
                <?php else: ?>
                    <span class="relative inline-flex items-center rounded-md border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-400 dark:text-gray-500 cursor-not-allowed">Önceki</span>
                <?php endif; ?>
                <?php if ($currentPage < $totalPages): ?>
                    <a href="<?php echo BASE_URL . '?page=forum&p=' . ($currentPage + 1); ?>" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Sonraki</a>
                <?php else: ?>
                    <span class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-400 dark:text-gray-500 cursor-not-allowed">Sonraki</span>
                <?php endif; ?>
            </div>
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <?php // Optional: Display item count info ?>

                </div>
                <div>
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        <?php // Previous Button ?>
                        <?php if ($currentPage > 1): ?>
                            <a href="<?php echo BASE_URL . '?page=forum&p=' . ($currentPage - 1); ?>" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 dark:text-gray-500 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Önceki</span>
                                <span class="lucide text-sm">&#xe7c3;</span>
                            </a>
                        <?php else: ?>
                            <span class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-300 dark:text-gray-600 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 cursor-not-allowed">
                                <span class="sr-only">Önceki</span>
                                <span class="lucide text-sm">&#xe7c3;</span>
                             </span>
                        <?php endif; ?>

                        <?php // Page Numbers (simplified example: show current and +/- 2 pages) ?>
                        <?php
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);
                        // Add ellipsis logic if needed
                        ?>
                        <?php if ($startPage > 1): ?>
                            <a href="<?php echo BASE_URL . '?page=forum&p=1'; ?>" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0">1</a>
                            <?php if ($startPage > 2): ?> <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-400 ring-1 ring-inset ring-gray-300 dark:ring-gray-600">...</span> <?php endif; ?>
                        <?php endif; ?>

                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <a href="<?php echo BASE_URL . '?page=forum&p=' . $i; ?>"
                               aria-current="<?php echo ($i == $currentPage) ? 'page' : 'false'; ?>"
                               class="relative inline-flex items-center px-4 py-2 text-sm font-semibold <?php echo ($i == $currentPage) ? 'z-10 bg-teal-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-teal-600 dark:bg-teal-700' : 'text-gray-900 dark:text-gray-200 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700'; ?> focus:z-20 focus:outline-offset-0">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($endPage < $totalPages): ?>
                            <?php if ($endPage < $totalPages - 1): ?> <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-400 ring-1 ring-inset ring-gray-300 dark:ring-gray-600">...</span> <?php endif; ?>
                            <a href="<?php echo BASE_URL . '?page=forum&p=' . $totalPages; ?>" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0"><?php echo $totalPages; ?></a>
                        <?php endif; ?>


                        <?php // Next Button ?>
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="<?php echo BASE_URL . '?page=forum&p=' . ($currentPage + 1); ?>" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 dark:text-gray-500 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Sonraki</span>
                                <span class="lucide text-sm">&#xe7c2;</span>
                            </a>
                        <?php else: ?>
                            <span class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-300 dark:text-gray-600 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 cursor-not-allowed">
                                <span class="sr-only">Sonraki</span>
                                <span class="lucide text-sm">&#xe7c2;</span>
                             </span>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
        </nav>
    <?php endif; ?>
    <?php // ----- BİTİŞ: Sayfalama Kontrolleri ----- ?>

</main>

<?php
// app/Views/yeni-konu.php
// $pageTitle is set in index.php
?>

<main class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow p-6 md:p-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">
            Yeni Forum Konusu Aç
        </h1>

        <?php // TODO: Add login check - show form only if logged in ?>

        <form action="<?php echo BASE_URL; ?>?page=yeni-konu-kaydet" method="POST" class="space-y-5">

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konu Başlığı</label>
                <input type="text" id="title" name="title" required maxlength="255"
                       class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                       placeholder="Konu başlığını buraya yazın...">
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">İlk Mesajınız</label>
                <textarea id="content" name="content" rows="8" required
                          class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                          placeholder="Konuyla ilgili ilk mesajınızı buraya yazın..."></textarea>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Basit metin veya Markdown kullanabilirsiniz (Markdown desteği eklenebilir).</p>
            </div>

            <?php // TODO: Add category selection, sticky/locked options for admins ?>

            <div class="flex justify-end pt-3">
                <a href="<?php echo BASE_URL; ?>?page=forum" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 px-4 py-2 mr-3">İptal</a>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 dark:bg-teal-700 dark:hover:bg-teal-600 text-white font-semibold py-2.5 px-6 rounded-lg text-sm flex items-center transition duration-200 ease-in-out">
                    <span class="lucide mr-1.5 text-sm">&#xe91f;</span> Konuyu Aç
                </button>
            </div>

        </form>
        <?php // TODO: Show login link/message if user is not logged in ?>
    </div>
</main>

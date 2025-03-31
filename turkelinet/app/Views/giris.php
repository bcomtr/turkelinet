<?php
// app/Views/giris.php ('giris' means 'login')
// $pageTitle is set in index.php

// Display potential login errors passed from the controller (index.php) later
$errors = isset($errors) ? $errors : [];
$old_input = isset($old_input) ? $old_input : [];

// Display success message from registration (if redirected here)
$status = isset($_GET['status']) ? $_GET['status'] : null;
$success_message = ($status === 'kayit_basarili') ? "Hesabınız başarıyla oluşturuldu! Şimdi giriş yapabilirsiniz." : null;

?>

<main class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-lg shadow p-6 md:p-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6 text-center">
            Giriş Yap
        </h1>

        <?php // Display general errors or success messages ?>
        <?php if (isset($errors['general'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?php echo htmlspecialchars($errors['general']); ?>
            </div>
        <?php elseif ($success_message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>


        <form action="<?php echo BASE_URL; ?>?page=giris-yap" method="POST" class="space-y-5">
            <?php // CSRF Token should be added here in a real application ?>

            <div>
                <label for="username_or_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kullanıcı Adı veya E-posta</label>
                <input type="text" id="username_or_email" name="username_or_email" required
                       value="<?php echo htmlspecialchars($old_input['username_or_email'] ?? ''); ?>"
                       class="w-full p-3 border <?php echo isset($errors['username_or_email']) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                       placeholder="Kullanıcı adınız veya e-posta adresiniz">
                <?php if (isset($errors['username_or_email'])): ?>
                    <p class="text-xs text-red-600 mt-1"><?php echo htmlspecialchars($errors['username_or_email']); ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Şifre</label>
                <input type="password" id="password" name="password" required
                       class="w-full p-3 border <?php echo isset($errors['password']) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"
                       placeholder="Şifreniz">
                <?php if (isset($errors['password'])): ?>
                    <p class="text-xs text-red-600 mt-1"><?php echo htmlspecialchars($errors['password']); ?></p>
                <?php endif; ?>
            </div>

            <div class="flex items-center justify-between">
                <div class="text-sm">
                    <?php // TODO: Add Remember Me checkbox ?>

                </div>
                <div class="text-sm">
                    <a href="<?php echo BASE_URL; ?>?page=sifremi-unuttum" class="font-medium text-teal-600 hover:text-teal-800 dark:text-teal-400 dark:hover:text-teal-300 hover:underline">Şifremi Unuttum?</a>
                </div>
            </div>


            <div class="pt-3">
                <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 dark:bg-teal-700 dark:hover:bg-teal-600 text-white font-bold py-3 px-6 rounded-lg text-base flex items-center justify-center transition duration-200 ease-in-out">
                    <span class="lucide mr-2 text-base">&#xe89d;</span> Giriş Yap
                </button>
            </div>

            <div class="text-center text-sm mt-6">
                <span class="text-gray-600 dark:text-gray-400">Hesabınız yok mu?</span>
                <a href="<?php echo BASE_URL; ?>?page=kayit" class="font-medium text-teal-600 hover:text-teal-800 dark:text-teal-400 dark:hover:text-teal-300 hover:underline ml-1">Hemen Kaydolun</a>
            </div>

        </form>
    </div>
</main>

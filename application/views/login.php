<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url("assets/css/styles.css") ?>">
    <style>
        body {
            background-image: url("<?= base_url('assets/images/logi-bg.jpg') ?>") !important;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
    <title>Hekima Beauty</title>
</head>

<body>
    <section class="bg-transparent">
        <div class="flex bg-transparent flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="mr-2 w-8 h-8 text-rose-600 animate-pulse">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
                Hekima Beauty
            </a>
            <p class="text-slate-400 text-lg mb-5"> Sales Software</p>
            <div
                class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1
                        class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Sign in to your account
                    </h1>
                    <?php if ($this->session->flashdata("login_failure")): ?>
                        <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-900 dark:text-red-400"
                            role="alert">
                            <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                            </svg>
                            <span class="sr-only">Info</span>
                            <div>
                                <span class="font-medium">Failed!</span>
                                <?= $this->session->flashdata("login_failure") ?>
                            </div>
                        </div>
                    <?php endif ?>
                    <?php echo form_open('login/auth', ['class' => "space-y-4 md:space-y-6"]) ?>
                    <div>
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                            username</label>
                        <input type="username" name="username" id="username"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-sky-600 focus:border-sky-600 block w-full p-2.5"
                            placeholder="Your username" required="">
                    </div>
                    <div>
                        <label for="password"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" name="password" id="password" placeholder="...."
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-sky-600 focus:border-sky-600 block w-full p-2.5"
                            required="">
                    </div>
                    <button type="submit"
                        class="w-full text-white bg-sky-600 hover:bg-sky-700 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-sky-600 dark:hover:bg-sky-700 dark:focus:ring-sky-800">Sign
                        in</button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        Don’t have an account yet? <a href="#"
                            class="font-medium text-sky-600 hover:underline dark:text-sky-500">Contact your admin</a>
                    </p>
                    </?>
                </div>
            </div>
        </div>
    </section>

</body>

</html>
<?php require '_header.php'; ?>

<div class="flex min-w-full items-center justify-center min-h-screen">

    <div class="min-h-screen w-full md:w-2/3 lg:w-1/2 md:max-w-lg md:min-h-0 p-8 text-left bg-white md:shadow-lg">

        <img src="/img/logo-without-tags.svg" alt="Logo" />

        <h3 class="mt-6 text-2xl font-bold text-center text-slate-900">
            Client Dashboard
        </h3>

        <div class="login-alerts alerts"></div>

        <form method="post" action="/clients/login">

            <div class="mt-4">

                <div class="relative">
                    <label class="block mb-2 text-slate-900" for="email">
                        Email
                    </label>
                    <i class="field-icon fas fa-user"></i>
                    <input
                        id="email"
                        class="styled w-full !pl-9"
                        type="text"
                        name="email"
                        placeholder="name@example.com"
                        aria-label="Email Address"
                    />
                </div>

                <div class="relative mt-4">
                    <label class="block mb-2 text-slate-900" for="password">
                        Password
                    </label>
                    <i class="field-icon fas fa-key"></i>
                    <input
                        id="password"
                        class="styled w-full !pl-9"
                        type="password"
                        name="password"
                        placeholder="Your Password"
                        aria-label="Password"
                    />
                </div>

                <div class="flex flex-col md:flex-row md:items-center mt-8">
                    <button class="styled w-full md:w-auto">
                        Login
                    </button>
                    <label for="remember" class="flex pt-4 pb-2 md:py-0 md:pl-4 items-center text-sm">
                        <input
                            id="remember"
                            type="checkbox"
                            name="remember"
                            value="Y"
                            aria-label="Remember Me"
                        />
                        <div class="px-2 text-indigo-900">
                            Remember Me
                        </div>
                    </label>
                    <a
                        href="/clients/password/forgot"
                        class="md:ml-auto text-sm hover:underline text-indigo-900">
                        Forgot password?
                    </a>
                </div>

            </div>

        </form>

    </div>

</div>

<?php require '_footer.php'; ?>

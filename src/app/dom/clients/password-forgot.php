<?php require '_header.php'; ?>

<div class="flex min-w-full items-center justify-center min-h-screen">

    <div class="min-h-screen w-full md:w-2/3 lg:w-1/2 md:max-w-lg md:min-h-0 p-8 text-left bg-white md:shadow-lg">

        <img src="/img/logo-without-tags.svg" alt="Logo" />

        <h3 class="mt-6 text-2xl font-bold text-center text-slate-900">
            Forgot your password?
        </h3>

        <div class="login-alerts alerts"></div>

        <form
            method="post"
            action="password-forgot.php"
            data-call="clients/password/forgot">

            <div class="mt-4">

                <div class="relative">
                    <label class="block mb-2 text-night-400" for="email">
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

                <div class="flex flex-col md:flex-row md:items-center justify-between mt-8">
                    <button class="styled w-full md:w-auto">
                        Send Reset Link
                    </button>
                </div>

            </div>

        </form>

    </div>

</div>

<?php require '_footer.php'; ?>

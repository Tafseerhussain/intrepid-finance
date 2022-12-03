<?php

$app->call('dashboard')->any(function () {
    return $this->format('clients.dashboard');
})->auth(Auth_UserLogin::class, 'isAuthenticated');

$app->call('mx/connect')->get(function () {
    return $this->format('clients.connect');
})->auth(Auth_UserLogin::class, 'isAuthenticated');

$app->call('mx/disconnect')->post(function () {
    return $this->action(Action_User_MxDisconnect::class);
})->auth(Auth_UserLogin::class, 'isAuthenticated');

$app->call('mx/member-connected')->post(function () {
    return $this->action(Action_User_MxMemberConnected::class);
})->auth(Auth_UserLogin::class, 'isAuthenticated');

$app->call('password/forgot')->get(function () {
    return $this->format('clients.password-forgot');
})->post(function () {
    return $this->action(Action_User_PasswordForgot::class)->onSuccess(function () {
        return $this
            ->redirect('forgot')
            ->success('An email with a secure password reset link has been sent.');
    });
});

$app->call('password/reset/{slug}')->get(function (User $user, $token) {
    return $this->format('clients.password-reset')->using([
        'user'  => $user,
        'token' => $token,
    ]);
});

$app->call('password/reset')->post(function () {
    return $this->action(Action_User_PasswordReset::class)->onSuccess(function () {
        return $this
            ->redirect('clients/login')
            ->success('Your password has been reset! You may now login using your new password.');
    });
});

$app->call('login')->get(function (Auth_UserLogin $auth) {
    return $auth->isAuthenticated()
        ? $this->redirect('dashboard')
        : $this->format('clients.login');
})->post(function (Auth_UserLogin $auth) {
    $email    = $this->req->post('email');
    $password = $this->req->post('password');
    $remember = $this->req->post('remember') === 'Y';

    return $auth->login($email, $password, $remember)
        ? $this->redirect('dashboard')->success('Logged in successfully!')
        : $this->redirect('login')->error('Invalid login credentials.');
})->secure();

$app->call('logout')->get(function (Auth_UserLogin $auth) {
    return $auth->logout()
        ? $this->redirect('login')->success('Logged out successfully.')
        : $this->redirect('login');
})->secure();

$app->call('/')->get(function (Auth_UserLogin $auth) {
    return $auth->isAuthenticated()
        ? $this->redirect('dashboard')
        : $this->redirect('login');
})->secure();

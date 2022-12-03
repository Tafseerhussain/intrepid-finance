<?php

$app->call('dashboard')->any(function () {
    return $this->format('admin.dashboard');
})->auth(Auth_AdminLogin::class, 'isAuthenticated');

$app->call('profile')->post(function () {
    return $this->action(Action_Admin_ProfileEdit::class);
})->auth(Auth_AdminLogin::class, 'isAuthenticated');

$app->call('admins/list')->any(function () {
    return $this->format('admin.admins-list');
})->auth(Auth_AdminLogin::class, 'isAuthenticated', 'isLevel3');

$app->call('admins/add')->post(function () {
    return $this->action(Action_Admin_AdminAdd::class);
})->auth(Auth_AdminLogin::class, 'isAuthenticated', 'isLevel3');

$app->call('admins/edit/{id}')->post(function ($id) {
    return $this->action(Action_Admin_AdminEdit::class, $id);
})->auth(Auth_AdminLogin::class, 'isAuthenticated', 'isLevel3');

$app->call('admins/delete/{id}')->post(function ($id) {
    return $this->action(Action_Admin_AdminDelete::class, $id);
})->auth(Auth_AdminLogin::class, 'isAuthenticated', 'isLevel3');

$app->call('clients/list')->any(function () {
    return $this->format('admin.clients-list');
})->auth(Auth_AdminLogin::class, 'isAuthenticated');

$app->call('clients/login/{id}')->get(function ($id) {
    return $this->action(Action_Admin_UserLogin::class, $id)->onSuccess(function () {
        return $this->redirect('/clients/dashboard');
    });
})->auth(Auth_AdminLogin::class, 'isAuthenticated');

$app->call('clients/edit/{id}')->get(function ($id) {
    return $this->format('admin.clients-edit')->using('id', $id);
})->post(function ($id) {
    return $this->action(Action_Admin_UserEdit::class, $id);
})->auth(Auth_AdminLogin::class, 'isAuthenticated');

$app->call('clients/delete/{id}')->post(function ($id) {
    return $this->action(Action_Admin_UserDelete::class, $id);
})->auth(Auth_AdminLogin::class, 'isAuthenticated', 'isLevel3');

$app->call('clients/edit/{id}/address-business')->post(function ($id) {
    return $this->action(Action_Admin_UserAddressBusiness::class, $id);
})->auth(Auth_AdminLogin::class, 'isAuthenticated');

$app->call('clients/edit/{id}/address-home')->post(function ($id) {
    return $this->action(Action_Admin_UserAddressHome::class, $id);
})->auth(Auth_AdminLogin::class, 'isAuthenticated');

$app->call('clients/notes/edit/{id}')->post(function ($id) {
    return $this->action(Action_Admin_UserNoteEdit::class, $id);
})->auth(Auth_AdminLogin::class, 'isAuthenticated', 'isLevel3');

$app->call('clients/notes/delete/{id}')->post(function ($id) {
    return $this->action(Action_Admin_UserNoteDelete::class, $id);
})->auth(Auth_AdminLogin::class, 'isAuthenticated', 'isLevel3');

$app->call('reports')->any(function () {
    return $this->format('admin.reports');
})->auth(Auth_AdminLogin::class, 'isAuthenticated');

$app->call('demo-typography')->any(function () {
    return $this->format('admin.demo-typography');
})->auth(Auth_AdminLogin::class, 'isAuthenticated');

$app->call('login')->get(function (Auth_AdminLogin $auth) {
    return $auth->isAuthenticated()
        ? $this->redirect('dashboard')
        : $this->format('admin.login');
})->post(function (Auth_AdminLogin $auth) {
    $email    = $this->req->post('email');
    $password = $this->req->post('password');
    $remember = $this->req->post('remember') === 'Y';

    return $auth->login($email, $password, $remember)
        ? $this->redirect('dashboard')->success('Logged in successfully!')
        : $this->redirect('login')->error('Invalid login credentials.');
})->secure();

$app->call('logout')->get(function (Auth_AdminLogin $auth) {
    return $auth->logout()
        ? $this->redirect('login')->success('Logged out successfully.')
        : $this->redirect('login');
})->secure();

$app->call('/')->get(function (Auth_AdminLogin $auth) {
    return $auth->isAuthenticated()
        ? $this->redirect('dashboard')
        : $this->redirect('login');
})->secure();

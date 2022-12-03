<?php

$app->call('nav')->get(function (Auth_AdminLogin $auth) {
    $nav = [];

    $nav[] = [
        'name' => 'Dashboard',
        'icon' => 'fa-sharp fa-solid fa-desktop',
        'uri'  => '/admin/dashboard',
        'show' => FALSE,
    ];

    if ($auth->isLevel3()) {
        $nav[] = [
            'name' => 'Admins',
            'icon' => 'fa-regular fa-user-unlock',
            'uri'  => '/admin/admins/list',
            'show' => FALSE,
        ];
    }

    $nav[] = [
        'name' => 'Clients',
        'icon' => 'fa-solid fa-users',
        'uri'  => '/admin/clients/list',
        'show' => FALSE,
    ];

    $nav[] = [
        'name' => 'Reports',
        'icon' => 'fa-sharp fa-solid fa-chart-line-up',
        'uri'  => '/admin/reports',
        'show' => FALSE,
    ];

    $nav[] = [
        'name' => 'Logout',
        'icon' => 'fa-sharp fa-solid fa-arrow-up-left-from-circle',
        'uri'  => '/admin/logout',
        'show' => FALSE,
    ];

    return $nav;
})->auth(Auth_AdminLogin::class, 'isAuthenticated');

$app->call('admin')->get(function (Auth_AdminLogin $auth) {
    return [
        'id'           => $auth->get('id'),
        'first_name'   => $auth->get('first_name'),
        'last_name'    => $auth->get('last_name'),
        'email'        => $auth->get('email'),
        'access_level' => $auth->get('access_level'),
        'status'       => $auth->get('status'),
        'gravatar'     => $auth->getGravatar(),
    ];
})->auth(Auth_AdminLogin::class, 'isAuthenticated');

$app->call('admins')->get(function (Repo_Admins $admins) {
    $admins
        ->setPage($this->req->input('page'))
        ->setPerPage($this->req->input('per_page'))
        ->setSortBy($this->req->input('sort_by'), $this->req->input('sort_type'))
        ->setSearch($this->req->input('search'));

    return $admins->getResult();
})->auth(Auth_AdminLogin::class, 'isAuthenticated', 'isLevel3');

$app->call('admins/{id}')->get(function (Admin $admin, $id) {
    if ($admin->load($id)) {
        return $admin->getSafeFields();
    }

    return $this->abort();
})->auth(Auth_AdminLogin::class, 'isAuthenticated', 'isLevel3');

$app->call('clients')->get(function (Repo_Users $users) {
    $users
        ->setPage($this->req->input('page'))
        ->setPerPage($this->req->input('per_page'))
        ->setSortBy($this->req->input('sort_by'), $this->req->input('sort_type'))
        ->setSearch($this->req->input('search'))
        ->setFormType($this->req->input('form_type'))
        ->setStatuses($this->req->input('statuses'));

    return $users->getResult();
})->auth(Auth_AdminLogin::class, 'isAuthenticated');

$app->call('clients/{id}')->get(function (User $user, $id) {
    if ($user->load($id)) {
        return $user->parseJsonFields($user->formatCurrencyFields($user->get()));
    }

    return $this->abort();
})->auth(Auth_AdminLogin::class, 'isAuthenticated');

$app->call('clients/{id}/address-business')->get(function (User $user, $id) {
    if ( ! $user->load($id)) {
        return $this->abort();
    }

    return (new Tell_Bridge_Contact('business'))
        ->address1($user->get('business_address1'))
        ->address2($user->get('business_address2'))
        ->city($user->get('business_city'))
        ->province($user->get('business_province'))
        ->postal($user->get('business_postal'))
        ->country($user->get('business_country'))
        ->format();
})->auth(Auth_AdminLogin::class, 'isAuthenticated');

$app->call('clients/{id}/address-home')->get(function (User $user, $id) {
    if ( ! $user->load($id)) {
        return $this->abort();
    }

    return (new Tell_Bridge_Contact('home'))
        ->address1($user->get('home_address1'))
        ->address2($user->get('home_address2'))
        ->city($user->get('home_city'))
        ->province($user->get('home_province'))
        ->postal($user->get('home_postal'))
        ->country($user->get('home_country'))
        ->format();
})->auth(Auth_AdminLogin::class, 'isAuthenticated');

$app->call('clients/{id}/notes')->get(function (Repo_UsersNotes $notes, $id) {
    return $notes->setUserId($id)->getResult();
})->auth(Auth_AdminLogin::class, 'isAuthenticated');

$app->call('clients/{id}/changes')->get(function (Repo_UsersChanges $changes, $id) {
    return $changes->setUserId($id)->getResult();
})->auth(Auth_AdminLogin::class, 'isAuthenticated');

$app->call('clients/notes/{id}')->get(function (UserNote $note, $id) {
    if ($note->load($id)) {
        return $note->get();
    }

    return $this->abort();
})->auth(Auth_AdminLogin::class, 'isAuthenticated');

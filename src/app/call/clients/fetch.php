<?php

$app->call('profile')->get(function (Auth_UserLogin $auth, User $user) {
    if ( ! $user->load($auth->get('id'))) {
        return $this->abort('Failed to load user record.');
    }

    return [
        'reference_number' => $user->get('reference_number'),
        'company_name'     => $user->get('company_name'),
        'first_name'       => $user->get('first_name'),
        'last_name'        => $user->get('last_name'),
        'email'            => $user->get('email'),
        'status'           => $user->get('status'),
        'gravatar'         => $auth->getGravatar(),
        'mx_needs_widget'  => $user->get('mx_needs_widget'),
        'first_name'       => $user->get('first_name'),
    ];
})->auth(Auth_UserLogin::class, 'isAuthenticated');

$app->call('mx/widget-url')->any(function (Auth_UserLogin $auth, User $user) {
    $json = [
        'widget_url'    => NULL,
        'widget_needed' => TRUE,
        'error'         => NULL,
    ];

    if ( ! $user->load($auth->get('id'))) {
        $json['error'] = 'Failed to load user record.';

        return $json;
    }

    if ( ! $user->needsMxConnectWidget()) {
        $json['widget_needed'] = FALSE;

        return $json;
    }

    try {
        $json['widget_url'] = $user->getMxWidgetRequestUrl();
    } catch (Throwable $e) {
        $json['error'] = 'Failed to load connection widget.';
    }

    return $json;
})->auth(Auth_UserLogin::class, 'isAuthenticated');

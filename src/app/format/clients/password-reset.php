<?php /** @var Tell_Dom_Html $dom */
      /** @var User $user */

$token = $get('token');

$user = $get('user');

$user->where('password_token = ? AND deleted IS NULL', $token);

if (   ! $user->isLoaded()
    || ! $user->isUsableAccount()
    || strtotime($user->get('password_date')) < strtotime('-24 hours')
) {

    Tell_Message::error('Invalid password reset token.');

    $redirect('/clients/password/forgot');

}

$dom('clients/password-reset.php')->apply('clients._trait');

$dom('@password_token')->val($token);

$dom('@email')->val($user->get('email'));

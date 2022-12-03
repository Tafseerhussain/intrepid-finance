<?php /** @var Tell_Dom_Html $dom */

$dom('public/confirmation.php')->apply('public._trait');

$user = $ioc->factory(User::class);

$user->loadByFormToken($get('token'));

if ( ! $user->isLoaded()) {
    $abort(404, 'Record not found.');
}

$dom('#app-params')->text(Tell_Json::encode([
    'form_type'     => $user->get('form_type'),
    'form_token'    => $user->get('form_token'),
    'data'          => $user->formatCurrencyFields($user->getSafeFields()),
    'needs_account' => $user->needsAccountCreated(),
]));

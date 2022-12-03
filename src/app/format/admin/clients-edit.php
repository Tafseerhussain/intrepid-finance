<?php /** @var Tell_Dom_Html $dom */

$dom('admin/clients-edit.php')->apply('admin._trait');

$id = $get('id');

$user = $ioc->factory(User::class);

if ( ! $user->load($id)) {
    $abort(404, 'Record not found.');
}

$dom('#app-params')->text(Tell_Json::encode([
    'data' => $user->parseJsonFields($user->formatCurrencyFields($user->get())),
]));

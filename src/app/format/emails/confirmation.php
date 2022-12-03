<?php /** @var Tell_Dom_Html $dom */

$url = Tell_Request::host(TRUE) . '/application/create-account/' . $get('form_token');

$dom('emails/confirmation.email.php');

$dom('.data-first-name')->text(ucfirst($get('first_name')));

$dom('.link-create-account')->attr('href', $url);

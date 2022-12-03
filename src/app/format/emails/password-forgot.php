<?php /** @var Tell_Dom_Html $dom */

$url = Tell_App_Uri::home() . 'clients/password/reset/' . $get('token');

$dom('emails/password-forgot.email.php');

$dom('.link-password-reset')->attr('href', $url);

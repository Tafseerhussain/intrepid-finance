<?php /** @var Tell_Dom_Html $dom */

$dom('title')->text('Admin Dashboard');

$dom('.alert')->remove();

$dom('.alerts')->html(Tell_Message::all());

<?php /** @var Tell_Dom_Html $dom */

$dom('title')->text('Client Dashboard');

$dom('.alert')->remove();

$dom('.alerts')->html(Tell_Message::all());

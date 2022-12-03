<?php /** @var Tell_Dom_Html $dom */

$dom('title')->text('Intrepid Finance & Venture');

$dom('.alert')->remove();

$dom('.alerts')->html(Tell_Message::all());

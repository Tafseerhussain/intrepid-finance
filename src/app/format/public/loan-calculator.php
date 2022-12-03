<?php /** @var Tell_Dom_Html $dom */

$dom('public/loan-calculator.php')->apply('public._trait');

$dom('#app-params')->text(Tell_Json::encode([]));

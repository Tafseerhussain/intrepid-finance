<?php /** @var Tell_Dom_Html $dom */

$dom('admin/login.php')->apply('admin._trait');

if (Tell::testIp()) {

    $dom('@email')->val('steve@intrepidfinance.io');

    $dom('@password')->val('testing');

}

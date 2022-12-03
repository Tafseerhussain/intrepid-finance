<?php /** @var Tell_Dom_Html $dom */

$dom('admin/password-forgot.php')->apply('admin._trait');

if (Tell::testIp()) {

    $dom('@email')->val('admin@ifvdev.com');

}

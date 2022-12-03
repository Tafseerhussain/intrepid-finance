<?php

$inspect('Example Inspection', function () {

    // Try changing this to: contains('example.org')
    $this->assert('bob.smith@example.com')->contains('example.com');

});

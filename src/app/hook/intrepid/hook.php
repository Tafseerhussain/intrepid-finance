<?php

/**
 * Custom hook to override some of the default framework assets.
 * ---
 * @author  Tell Konkle <tellkonkle@gmail.com>
 */

return function(Tell_Hook $hook) {
    $hook->assets(__DIR__ . DS . 'assets');
};

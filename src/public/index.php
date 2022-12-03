<?php

// Dependencies
require_once __DIR__ . '/../core/start.php';

// Initialize kernel
Tell::boot(
    __DIR__ . '/../app/App.php',
    __DIR__ . '/../config.php'
);

// Setup IoC container, event manager, mutator, and HTTP handles
$container = new Tell_Container();
$event     = new Tell_Event($container);
$mutate    = new Tell_Mutate($container);
$request   = new Tell_Request($mutate);
$response  = new Tell_Response($mutate);

// Bind mutator to kernel
Tell::bind($mutate);

// Helper functions
if (is_file(__DIR__ . '/../app/Functions.php')) {
    require_once __DIR__ . '/../app/Functions.php';
}

// Initialize and bootstrap the application
App::instance($request, $response, $container, $event, $mutate)->bootstrap();

// Prevents infinite loop messes during development
if (Tell::testIp()) {
    set_time_limit(5);
}

// Route the HTTP request (conditional allows index to be included in scripts without routing)
if (Tell::is('index.php')) {
    App::instance()->route();
}

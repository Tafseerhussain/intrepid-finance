<?php

/**
 * ABC Design Pattern
 * ---
 * A = Action  - Form actions, RESTful actions
 * B = Bridge  - Bridge classes (Models)
 * C = Call    - Cascading call routes (Routers + Controllers)
 * D = Dom     - DOM ([Document Object Model] Templates)
 * E = Event   - Custom event handlers
 * F = Format  - DOM formatters (ViewModels)
 * G = Graft   - Database schema (Migrations)
 * H = Hook    - Modular hooks (Plugins / Modules / Extensions)
 * I = Inspect - Inspections (Unit Tests)
 * J = Job     - Cron jobs (Scheduled Tasks), background daemons
 * K = Keep    - Private assets, file storage, etc. (the "everything-else" area)
 * L = Lang    - Language translations, locale data (i18n)
 * M = Mutate  - Custom mutators
 */
class App extends Tell_App
{
    /**
     * Absolute directory paths.
     */
    const PATH_ACTION  = ROOT_PATH . 'app/action';
    const PATH_BRIDGE  = ROOT_PATH . 'app/bridge';
    const PATH_CALL    = ROOT_PATH . 'app/call';
    const PATH_DOM     = ROOT_PATH . 'app/dom';
    const PATH_EVENT   = ROOT_PATH . 'app/event';
    const PATH_FORMAT  = ROOT_PATH . 'app/format';
    const PATH_GRAFT   = ROOT_PATH . 'app/graft';
    const PATH_HOOK    = ROOT_PATH . 'app/hook';
    const PATH_INSPECT = ROOT_PATH . 'app/inspect';
    const PATH_JOB     = ROOT_PATH . 'app/job';
    const PATH_KEEP    = ROOT_PATH . 'app/keep';
    const PATH_LANG    = ROOT_PATH . 'app/lang';
    const PATH_MUTATE  = ROOT_PATH . 'app/mutate';

    /**
     * Default formatters to run for aborted HTTP requests.
     */
    const FORMAT_ABORT = [
        403 => NULL, // 403 - Forbidden (e.g. 'foo.bar.403' --> 'format/foo/bar/403.php')
        404 => NULL, // 404 - Not Found (e.g. 'foo.bar.404' --> 'format/foo/bar/404.php')
        // ...
    ];

    /**
     * Events that should always be ran.
     */
    const EVENT_ALL = [
        'call.before' => [
            Tell_Event_Call::class,
        ],
        'call.after' => [
            Tell_Event_Call::class,
        ],
        'db.failure' => [
            Tell_Event_Db::class,
        ],
        'db.success' => [
            Tell_Event_Db::class,
        ],
    ];

    /**
     * Events that should only be ran in live mode.
     */
    const EVENT_LIVE = [];

    /**
     * Events that should only be ran in test mode.
     */
    const EVENT_TEST = [];

    /**
     * Events that should only be ran during inspections.
     */
    const EVENT_INSPECT = [];

    /**
     * Mutators that should always be ran.
     */
    const MUTATE_ALL = [
        'call.explicit' => [
            Tell_Mutate_Asset::class,
        ],
        'dom.handle.cache' => [
            Tell_Mutate_Dom::class,
            Mutate_DomCacheBuster::class,
        ],
        'dom.markup.cache' => [
            Tell_Mutate_Asset::class,
        ],
        'dom.markup.forge' => [
            Tell_Mutate_Markup::class,
        ],
        'request' => [
            Tell_Mutate_Request::class,
        ],
        'response' => [
            Tell_Mutate_Response::class,
        ],
    ];

    /**
     * Mutators that should only be ran in live mode.
     */
    const MUTATE_LIVE = [];

    /**
     * Mutators that should only be ran in test mode.
     */
    const MUTATE_TEST = [
        'buffer' => [
            Tell_Mutate_Debug::class,
        ],
    ];

    /**
     * Mutators that should only be ran during inspections.
     */
    const MUTATE_INSPECT = [
        'inspect.before' => [
            Tell_Mutate_Inspect::class,
        ],
        'inspect.after' => [
            Tell_Mutate_Inspect::class,
        ],
    ];

    /**
     * Bootstrap the application's service container.
     */
    public function bootstrap()
        : void
    {
        // Preemptive dependency injection
        Bridge::inject([
            'ioc' => $this->ioc,
            'db'  => $this->ioc->defer(Tell_Db::class),
        ]);
    }

    /**
     * Bootstrap the inspector's base service container.
     */
    public function inspector()
        : Tell_Container
    {
        // OPcache is disabled in CLI most other frameworks test with; mimic for browser inspector
        opcache_reset();

        // (object) Instance of Tell_Container
        return $this->ioc;
    }
}

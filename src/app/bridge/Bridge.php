<?php

/**
 * Baseline Bridge
 * ---
 * @author  Tell Konkle <tellkonkle@gmail.com>
 * ---
 * @property-read  Tell_Container  $ioc  [deferrable] Service container.
 * @property-read  Tell_Db         $db   [deferrable] Database handle.
 */
abstract class Bridge
{
    /*
     * Enable macro functionality.
     */
    use Tell_Trait_Macro;

    /*
     * Debugging aid.
     */
    use Tell_Trait_Debug;

    /*
     * Preemptive dependency injector.
     */
    use Tell_Trait_Inject;

    /**
     * Deferrable properties that can be preemptively injected.
     * ---
     * @var  mixed[]
     */
    protected static $defer = [
        'ioc' => NULL,
        'db'  => NULL,
    ];

    /**
     * Optional constructor.
     * ---
     * @param   object  [?] Instance of Tell_Container.
     * @param   object  [?] Instance of Tell_Db.
     * @return  void
     */
    public function __construct(Tell_Container $ioc = NULL, Tell_Db $db = NULL)
    {
        // (object) Manually inject Tell_Container dependency (deferred otherwise)
        if ($ioc) {
            $this->ioc = $ioc;
        }

        // (object) Manually inject Tell_Db dependency (deferred otherwise)
        if ($db) {
            $this->db = $db;
        }
    }
}

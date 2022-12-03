<?php

/**
 * Change Tracking Handler
 * ---
 * @author  Tell Konkle <tellkonkle@gmail.com>
 */
class Tracker
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
     * Property accessor.
     */
    use Tell_Trait_Property;

    /**
     * Database table info used for storing tracking data.
     */
    const TRACK_TABLE        = 'tracking';
    const FIELD_PRIMARY      = 'id';
    const FIELD_PARENT_TABLE = 'parent_table';
    const FIELD_PARENT_ID    = 'parent_id';
    const FIELD_AUTHOR_TABLE = 'author_table';
    const FIELD_AUTHOR_ID    = 'author_id';
    const FIELD_AUTHOR_NAME  = 'author_name';
    const FIELD_CHANGES      = 'changes';
    const FIELD_ACTIONS      = 'actions';
    const FIELD_IP           = 'ip_address';
    const FIELD_MODIFIED     = 'modified';
    const FIELD_CREATED      = 'created';
    const FIELD_DELETED      = 'deleted';
    const FIELD_EMPTY        = '*empty*';

    /**
     * Instance of Tell_Db.
     * ---
     * @var  Tell_Db
     */
    protected $db = NULL;

    /**
     * The table name that the author record is stored in.
     * ---
     * @var  string
     */
    protected $authorTable = NULL;

    /**
     * The database ID of the author that made these changes.
     * ---
     * @var  int
     */
    protected $authorId = NULL;

    /**
     * The name of the author that made these changes.
     * ---
     * @var  string
     */
    protected $authorName = NULL;

    /**
     * The parent record's table name.
     * ---
     * @var  string
     */
    protected $parentTable = NULL;

    /**
     * The parent record's database ID.
     * ---
     * @var  int
     */
    protected $parentId = NULL;

    /**
     * An array of messages describing actions taken by the author.
     * ---
     * @var  array
     */
    protected $actions = [];

    /**
     * Build data before changes are made.
     * ---
     * @var  array
     */
    protected $before = [];

    /**
     * Build data after changes are made.
     * ---
     * @var  array
     */
    protected $after = [];

    /**
     * Setup build tracker.
     * ---
     * @param   object  Instance of Tell_Db.
     * @return  void
     */
    public function __construct(Tell_Db $db)
    {
        // (object) Apply dependencies
        $this->db = $db;

        // Create database table for storing tracking data
        if (Tell::isTestable()) {
            $this->createTable();
        }
    }

    /**
     * Reset all of the tracker's data states.
     * ---
     * @return  object  Chainable instance of self.
     */
    public function reset()
        : Tracker
    {
        // Reset all data states
        $this->authorTable = NULL;
        $this->authorId    = NULL;
        $this->authorName  = NULL;
        $this->parentTable = NULL;
        $this->parentId    = NULL;
        $this->actions     = [];
        $this->before      = [];
        $this->after       = [];

        // (object) Instance of self for chainability
        return $this;
    }

    /**
     * Get the field change history of the loaded record.
     * ---
     * @param   bool    [T] Order by oldest first? Default = TRUE.
     * @param   object  [?] Instance of Tell_Crypto. Data is de-encrypted if provided.
     * @return  array   Field change history.
     */
    public function getHistory(bool $asc = TRUE, Tell_Crypto $crypto = NULL)
        : array
    {
        // (string) Character used to quote structure names for the applicable RDBMS
        $q = $this->db->quote();

        // (string) Query all tracking history for this table + primary key value
        $sql = 'SELECT * '
             . 'FROM ' . $q . self::TRACK_TABLE . $q . ' '
             . 'WHERE ' . $q . self::FIELD_PARENT_TABLE . $q . ' = ? '
             . 'AND ' . $q . self::FIELD_PARENT_ID . $q . ' = ? '
             . 'AND ' . $q . self::FIELD_DELETED . $q . ' IS NULL '
             . 'ORDER BY ' . $q . self::FIELD_CREATED . $q . ' '
             . ($asc ? 'ASC' : 'DESC');

        // (array) Execute query
        $rows = $this->db
            ->query($sql)
            ->bind([$this->parentTable, $this->parentId])
            ->run();

        // Loop through and parse each result
        foreach ($rows as $k => $row) {
            // (string; string) Field keys
            $changes = self::FIELD_CHANGES;
            $actions = self::FIELD_ACTIONS;

            // (string) Decrypt field changes
            if ($crypto && $crypto->isEncrypted($row[$changes])) {
                $row[$changes] = $crypto->decrypt($crypto->removeFlag($row[$changes]));
            }

            // (string) Decrypt actions
            if ($crypto && $crypto->isEncrypted($row[$actions])) {
                $row[$actions] = $crypto->decrypt($crypto->removeFlag($row[$actions]));
            }

            // (array) Parse field change JSON
            if ('[' === substr($row[$changes], 0, 1)) {
                $row[$changes] = Tell_Json::decode($row[$changes]);
            }

            // (array) Parse action JSON
            if ('[' === substr($row[$actions], 0, 1)) {
                $row[$actions] = Tell_Json::decode($row[$$actions]);
            }

            // (array) Update this row
            $rows[$k] = $row;
        }

        // (array) Field change history
        return $rows;
    }

    /**
     * Scan the record data for field changes. If any changes are found, the results will be
     * archived to the applicable database table as a JSON encoded string.
     * ---
     * @param   array    Trackable fields in a ['field_name' => 'Field Label'] format.
     * @param   closure  [?] Callback function used for comparing field values.
     * @param   object   [?] Instance of Tell_Crypto. Data is encrypted if provided.
     * @return  bool     TRUE if save is successful or not needed, FALSE otherwise.
     */
    public function archive(array $fields, Closure $callback = NULL, Tell_Crypto $crypto = NULL)
        : bool
    {
        // (array|null) A multi-dimensional array of field changes; NULL if no changes
        $changes = $this->changes($fields, $callback);

        // (bool) No changes were made and no actions were taken, safely stop here
        if ( ! $changes && ! $this->actions) {
            return TRUE;
        }

        // (array) Field changes
        $changes = Tell_Json::encode($changes);

        // (array) Actions taken
        $actions = Tell_Json::encode($this->actions);

        // (array; array) Encrypt data
        if ($crypto) {
            $changes = $crypto->encrypt($changes);
            $actions = $crypto->encrypt($actions);
        }

        // (array) Start compiling this tracking record
        $data = [
            self::FIELD_PARENT_TABLE => $this->parentTable,
            self::FIELD_PARENT_ID    => $this->parentId,
            self::FIELD_AUTHOR_TABLE => $this->authorTable,
            self::FIELD_AUTHOR_ID    => $this->authorId,
            self::FIELD_AUTHOR_NAME  => $this->authorName,
            self::FIELD_CHANGES      => $changes,
            self::FIELD_ACTIONS      => $actions,
            self::FIELD_IP           => Tell_Request::ip(),
            self::FIELD_MODIFIED     => date('Y-m-d H:i:s'),
            self::FIELD_CREATED      => date('Y-m-d H:i:s'),
            self::FIELD_DELETED      => NULL,
        ];

        // (bool) Insert this tracking record into the database
        return (bool) $this->db
            ->query('INSERT INTO ' . self::TRACK_TABLE)
            ->data($data)
            ->run();
    }

    /**
     * Set the parent record's database table and database ID.
     * ---
     * @param   string      Parent record's database table name.
     * @param   string|int  Parent record's database ID.
     * @return  object      Chainable instance of self.
     */
    public function parent(string $table, $id)
        : self
    {
        // Set the parent record's meta data
        $this->parentTable = $table;
        $this->parentId    = $id;

        // (object) Instance of self for chainability
        return $this;
    }

    /**
     * Set the author's name, database table (if applicable), and database ID (if applicable).
     * ---
     * @param   string      Author's name.
     * @param   string      [?] Author's database table.
     * @param   string|int  [?] Author's database ID.
     * @return  object      Chainable instance of self.
     */
    public function author(string $name, string $table = NULL, $id = NULL)
        : self
    {
        // (string) Set the author's applicable meta data
        $this->authorName  = $name;
        $this->authorTable = $table;
        $this->authorId    = $id;

        // (object) Instance of self for chainability
        return $this;
    }

    /**
     * Set the record's 'before' state, representing the record's data before any changes.
     * ---
     * @param   array   Array of data representing the record's 'before' state.
     * @return  object  Chainable instance of self.
     */
    public function before(array $input)
        : self
    {
        // (array) Record data before changes
        $this->before = $input;

        // (object) Instance of self for chainability
        return $this;
    }

    /**
     * Set the record's 'after' state, representing the record's data after all changes.
     * ---
     * @param   array   Array of data representing the record's 'after' state.
     * @return  object  Chainable instance of self.
     */
    public function after(array $input)
        : self
    {
        // (array) Record data after changes
        $this->after = $input;

        // (object) Instance of self for chainability
        return $this;
    }

    /**
     * Compile an action string, or array of action strings.
     * ---
     * @param   string|array  Action string, or array of action strings.
     * @return  object        Chainable instance of self.
     */
    public function actions($actions)
        : self
    {
        // (array) Multiple actions
        if (is_array($actions) && ! empty($actions)) {
            $this->actions = array_map('trim', array_values($actions));
        // (array) Single action
        } elseif (is_scalar($actions) && strlen(trim($actions)) > 0) {
            $this->actions = [trim($actions)];
        // (array) No action to save
        } else {
            $this->actions = [];
        }

        // (object) Instance of self for chainability
        return $this;
    }

    /**
     * Get all of the changes made to this record as an array in the following format:
     * ---
     * [
     *     0 => [
     *         'field'  => 'field_name',
     *         'label'  => 'Field Label',
     *         'before' => 'Value Before Change',
     *         'after'  => 'Value After Change',
     *     ],
     *     1 => [
     *         'field'  => 'field_name',
     *         'label'  => 'Field Label',
     *         'before' => 'Value Before Change',
     *         'after'  => 'Value After Change',
     *     ],
     * ];
     * ---
     * @param   array       Trackable fields in a ['field_name' => 'Field Label'] format.
     * @param   closure     [?] Callback function used for comparing field values.
     * @return  array|null  NULL if no changes found, array otherwise.
     */
    public function changes(array $fields, Closure $callback = NULL)
        : ? array
    {
        // (bool) Record has not been properly loaded or reloaded, stop here
        if (empty($this->before) || empty($this->after)) {
            return NULL;
        }

        // (array) Begin compiling changes
        $changes = [];

        // Loop through each field in the latest version of the record
        foreach ($this->after as $k => $after) {
            // Don't record changes to this field since it's not labeled as a trackable field
            if ( ! isset($fields[$k])) {
                continue;
            }

            // Field hasn't changed; goto next
            if (($before = $this->before[$k]) === $after) {
                continue;
            }

            // Field changed but both versions are considered empty; ignore change, goto next
            if ($this->isEmpty($before) && $this->isEmpty($after)) {
                continue;
            }

            // callback($before, $after, $k); TRUE = values match, FALSE = values don't match
            if ($callback && $callback($before, $after, $k)) {
                continue;
            }

            // (array) Compile log entry for this field's changes
            $changes[] = [
                'field'  => $k,
                'label'  => $fields[$k],
                'before' => $this->isEmpty($before) ? self::FIELD_EMPTY : $before,
                'after'  => $this->isEmpty($after)  ? self::FIELD_EMPTY : $after,
            ];
        }

        // (array|bool) Compiled list of changes or FALSE if no changes found
        return empty($changes) ? NULL : $changes;
    }

    /**
     * See if this record has been loaded.
     * ---
     * @return  bool  TRUE if record has been loaded, FALSE otherwise.
     */
    public function isLoaded()
        : bool
    {
        return ! empty($this->before);
    }

    /**
     * See if a specified value should be recorded as "empty" or "none".
     * ---
     * @param   mixed  Value to analyze.
     * @return  bool   TRUE if value is considered empty, FALSE otherwise.
     */
    public function isEmpty($value)
        : bool
    {
        return empty($value)
            || FALSE === $value
            || NULL === $value
            || [] === $value
            || '' === trim($value);
    }

    /**
     * Create database table for managing logins.
     * ---
     * @return  void
     */
    public function createTable()
        : void
    {
        (new Tell_Schema($this->db))->create(
            $this::TRACK_TABLE,
            function (Tell_Schema_Column $column) {
                $column->int($this::FIELD_PRIMARY)->primary()->increment();
                $column->varchar($this::FIELD_PARENT_TABLE, 64)->null()->default(NULL);
                $column->int($this::FIELD_PARENT_ID)->null()->default(NULL);
                $column->varchar($this::FIELD_AUTHOR_TABLE, 64)->null()->default(NULL);
                $column->int($this::FIELD_AUTHOR_ID)->null()->default(NULL);
                $column->fullName($this::FIELD_AUTHOR_NAME)->null()->default(NULL);
                $column->text($this::FIELD_CHANGES)->null()->default(NULL);
                $column->text($this::FIELD_ACTIONS)->null()->default(NULL);
                $column->ipAddress($this::FIELD_IP);
                $column->dateTime($this::FIELD_MODIFIED);
                $column->dateTime($this::FIELD_CREATED);
                $column->dateTime($this::FIELD_DELETED)->null()->default(NULL);
            }
        );
    }
}

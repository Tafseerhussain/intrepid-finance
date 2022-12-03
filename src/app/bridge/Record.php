<?php

/**
 * Baseline Record
 * ---
 * @author  Tell Konkle <tellkonkle@gmail.com>
 * ---
 * @method-duck  array  onLoad(...$args)  Load additional data and/or modify the raw data.
 * @method-duck  int    onSave(...$args)  Pass save data to event method (should return record ID).
 */
abstract class Record extends Bridge
{
    /**
     * @abstract  string
     */
    const TABLE = NULL;

    /**
     * @abstract  string
     */
    const WHERE = NULL;

    /**
     * @abstract  string
     */
    const PRIMARY = NULL;

    /**
     * The loaded record's primary key value (usually an integer).
     * ---
     * @var  int
     */
    protected $id = NULL;

    /**
     * Loaded record's primary data.
     * ---
     * @var  array
     */
    private $raw = [];

    /**
     * Loaded record's primary data and additional supplementary data.
     * ---
     * @var  array
     */
    private $row = [];

    /**
     * Load primary record's data via a supplied primary key value.
     * ---
     * @param   int    Record's database ID.
     * @param   bool   [F] Return instance of record? Default = FALSE.
     * @return  mixed  FALSE if record not found, array or object otherwise.
     */
    public function load($input, bool $instance = FALSE)
    {
        // (array; array; scalar) Reset loaded record
        $this->raw = [];
        $this->row = [];
        $this->id  = NULL;

        // (string) Query a specified record
        $sql = 'SINGLE * '
             . 'FROM ' . $this->db->quote(static::TABLE) . ' '
             . 'WHERE ' . static::WHERE;

        // (bool) Load primary record or stop here
        if ( ! $this->raw = $this->row = $this->db->query($sql)->bind($input)->run()) {
            return FALSE;
        }

        // (int) Retain row's primary key
        $this->id = Tell_Inflect::int($this->raw[static::PRIMARY], FALSE);

        // (bool) Load additional information or stop here
        if ( ! $this->row = $this->loadExtra($this->row)) {
            return FALSE;
        }

        // (object|array) Chainable instance of self or data array
        return $instance ? $this : $this->row;
    }

    /**
     * Load primary record's data via a custom TELQL WHERE statement.
     * ---
     * @param   string        Custom TELQL WHERE statement.
     * @param   mixed         Query bind(s).
     * @param   bool          [F] Return instance of record? Default = FALSE.
     * @return  array|object  FALSE if record not found, array or object otherwise.
     */
    public function where($where, $input, bool $instance = FALSE)
    {
        // (array; array; scalar) Reset loaded record
        $this->raw = [];
        $this->row = [];
        $this->id  = NULL;

        // (string) Query a specified record using a custom WHERE statement
        $sql = 'SINGLE * '
             . 'FROM ' . $this->db->quote(static::TABLE) . ' '
             . 'WHERE ' . $where;

        // (bool) Load primary record or stop here
        if ( ! $this->raw = $this->row = $this->db->query($sql)->bind($input)->run()) {
            return FALSE;
        }

        // (int) Retain row's primary key
        $this->id = Tell_Inflect::int($this->raw[static::PRIMARY], FALSE);

        // (bool|array) Load additional information or stop here
        if ( ! $this->row = $this->loadExtra($this->row)) {
            return FALSE;
        }

        // (object|array) Chainable instance of self or data array
        return $instance ? $this : $this->row;
    }

    /**
     * Get a specified field from loaded record, or get all fields from loaded record.
     * ---
     * @param   string  [?] Field name. Default = NULL (all fields).
     * @param   bool    [F] Escape result for HTML output? Default = FALSE.
     * @param   mixed   [F] Value to return if key not found. Default = FALSE.
     * @return  mixed   Specified field value or array of all field values.
     */
    public function get(string $key = NULL, bool $escape = FALSE, $fallback = FALSE)
    {
        // (mixed) Nothing has been loaded into this build, use fallback value
        if ( ! $this->isLoaded()) {
            return $fallback;
        }

        // (mixed) Applicable field's value (or all data if $key is NULL)
        $data = Tell_Arr::get($this->row, $key, $fallback);

        // (mixed) [recursion] Escape all data data for HTML output?
        return $escape ? Tell_Html::escape($data) : $data;
    }

    /**
     * See if the current record has been loaded.
     * ---
     * @return  bool  TRUE if record has been loaded, FALSE otherwise.
     */
    public function isLoaded()
        : bool
    {
        return ! empty($this->row);
    }

    /**
     * Update a record's primary fields, or insert a new record.
     * ---
     * @param   array     Record's new or updated fields.
     * @return  int|bool  Record's database ID or FALSE.
     */
    public function save(array $raw)
    {
        // (bool|int) Insert or update record as applicable
        $save = $this->isLoaded() ? $this->update($raw) : $this->insert($raw);

        // (mixed) Pass data to event method (ideally this method should return an integer)
        if ($save && Tell_Reflect::methodExists($this, 'onSave')) {
            $save = $this->ioc->resolve([$this, 'onSave'], $this->get(), $raw, $save);
        }

        // (mixed) Saved data result (usually a database ID integer or FALSE)
        return $save;
    }

    /**
     * Insert a new record into the database.
     * ---
     * @param   array     Record's initial field data.
     * @return  int|bool  Record's database ID or FALSE.
     */
    protected function insert(array $raw)
    {
        // (int|bool) Record's insert ID or FALSE
        $id = $this->db
            ->query('INSERT INTO ' . $this->db->quote(static::TABLE))
            ->data($raw)
            ->run();

        // (bool|int) Database ID if record inserted and loaded successfully, FALSE otherwise
        return $id && $this->load($id) ? $this->id : FALSE;
    }

    /**
     * Update an existing database record.
     * ---
     * @param   array     Record's new or updated field data.
     * @return  int|bool  Record's database ID or FALSE.
     */
    protected function update(array $raw)
    {
        // (array; array) Combine with primary record's existing data
        $this->raw = array_merge($this->raw, $raw);
        $this->row = array_merge($this->row, $raw);

        // Query to update the loaded record
        $sql = 'UPDATE ' . $this->db->quote(static::TABLE) . ' '
             . 'WHERE ' . $this->db->quote(static::PRIMARY) . ' = ? '
             . 'LIMIT 1';

        // (bool) Execute query
        $save = $this->db->query($sql)
            ->data($this->raw)
            ->bind($this->id)
            ->run();

        // (bool|int) Database ID if record updated and loaded successfully, FALSE otherwise
        return $save && $this->load($this->id) ? $this->id : FALSE;
    }

    /**
     * Load additional primary record data.
     * ---
     * @param   array  Loaded primary record data.
     * @return  array  Modified primary record data.
     */
    protected function loadExtra(array $row)
        : array
    {
        // (array) Load additional data and/or modify the raw data?
        $load = Tell_Reflect::methodExists($this, 'onLoad')
              ? $this->ioc->resolve([$this, 'onLoad'], $row)
              : $row;

        // (array) Failed to load record; unable to get data
        if (FALSE === $load) {
            return [];
        }

        // (array) onLoad() method didn't return a modified row (may be modified by reference)
        if ( ! is_array($load)) {
            return $row;
        }

        // (array) Record data, modified as applicable
        return $load;
    }
}

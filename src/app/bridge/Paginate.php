<?php

/**
 * Pagination Helper
 */
class Paginate
{
    /**
     * The total number of rows in the result set.
     * ---
     * @var  int
     */
    protected $totalRows = 0;

    /**
     * The number of rows to display per page.
     * ---
     * @var  int
     */
    protected $rowsPerPage = 10;

    /**
     * The current (active) page number.
     * ---
     * @var  int
     */
    protected $activePage = 1;

    /**
     * The max number of page numbers to show.
     * ---
     * @var  int
     */
    protected $linkSpan = 5;

    /**
     * Meta data for pagination results.
     * ---
     * @var  array
     */
    protected $info = [];

    /**
     * Set the total number of rows in the result set.
     * ---
     * @param   int     Total rows in result set.
     * @return  object  Chainable instance of self.
     */
    public function setTotalRows(int $totalRows)
        : self
    {
        // (int) Set parameter
        $this->totalRows = $totalRows;

        // (object) Instance of self for chainability
        return $this;
    }

    /**
     * Set the number of rows to display per page.
     * ---
     * @param   int     Number of rows per page.
     * @return  object  Chainable instance of self.
     */
    public function setRowsPerPage(int $rowsPerPage)
        : self
    {
        // (int) Set parameter
        $this->rowsPerPage = $rowsPerPage;

        // (object) Instance of self for chainability
        return $this;
    }

    /**
     * Set the currently active page number.
     * ---
     * @param   int     Currently active page number.
     * @return  object  Chainable instance of self.
     */
    public function setActivePage(int $activePage)
        : self
    {
        // (int) Set parameter
        $this->activePage = $activePage;

        // (object) Instance of self for chainability
        return $this;
    }

    /**
     * Set the max number of page numbers to show.
     * ---
     * @param   int     Max page numbers to show.
     * @return  object  Chainable instance of self.
     */
    public function setLinkSpan(int $linkSpan)
        : self
    {
        // (int) Set parameter
        $this->linkSpan = $linkSpan;

        // (object) Instance of self for chainability
        return $this;
    }

    /**
     * Get the display text for page results. Example:
     * ---
     * ) 'Page 1 of 32'
     * ---
     * @return  string  Display text.
     */
    public function getDisplayText()
        : string
    {
        return $this->compileMeta()['display'];
    }

    /**
     * Get the query LIMIT for querying the applicable rows for the page. Example:
     * ---
     * ) 'LIMIT 1, 10'
     * ---
     * @return  string  SQL query compatible query limit.
     */
    public function getQueryLimit()
        : string
    {
        return $this->compileMeta()['limit'];
    }

    /**
     * Get the currently active page number.
     * ---
     * @return  int  Active page number.
     */
    public function getActivePage()
        : int
    {
        return $this->compileMeta()['active'];
    }

    /**
     * Get the previous page number.
     * ---
     * @return  int  Previous page number.
     */
    public function getPreviousPage()
        : int
    {
        return $this->compileMeta()['previous'];
    }

    /**
     * Get the next page number.
     * ---
     * @return  int  Next page number.
     */
    public function getNextPage()
        : int
    {
        return $this->compileMeta()['next'];
    }

    /**
     * Get the total number of pages.
     * ---
     * @return  int  Total number of pages.
     */
    public function getTotalPages()
        : int
    {
        return $this->compileMeta()['total'];
    }

    /**
     * Get an array containing the page numbers surrounding the active page. Example:
     * ---
     * ) [
     * )     [0] => 1,
     * )     [1] => 2,
     * )     [2] => 3,
     * )     [3] => 4,
     * )     [4] => 5,
     * ) ];
     * ---
     * @return  array  Surrounding page numbers.
     */
    public function getPages()
        : array
    {
        return $this->compileMeta()['pages'];
    }

    /**
     * Compile an array containing all of the pagination info needed to paginate a result
     * set. Below is an example of the array output:
     * ---
     * ) [
     * )     'display'  => 'Page 1 of 18',
     * )     'limit'    => 'LIMIT 0, 10',
     * )     'active'   => 1,
     * )     'previous' => 1,
     * )     'next'     => 2,
     * )     'last'     => 18,
     * )     'pages'    => [
     * )         [0] => 1,
     * )         [1] => 2,
     * )         [2] => 3,
     * )         [3] => 4,
     * )         [4] => 5,
     * )     ],
     * ) ];
     * ---
     * @return  array  Pagination info.
     */
    public function compileMeta()
    {
        // (array) Use info that's already been generated (@performance)
        if ( ! empty($this->info)) {
            return $this->info;
        }

        // Calculate the last page in the pagination span
        $lastPage = ceil($this->totalRows / $this->rowsPerPage);

        // There is always at least one page, even if there are no results
        if ($lastPage < 1) {
            $lastPage = 1;
        }

        // Current page can't be last than 1
        if ($this->activePage < 1) {
            $this->activePage = 1;
        // Current page can't be greater than max number of pages
        } elseif ($this->activePage > $lastPage) {
            $this->activePage = $lastPage;
        }

        // Compile all of the pagination info
        $limit     = ($this->activePage * $this->rowsPerPage) - $this->rowsPerPage;
        $limit     = 'LIMIT ' . ($limit) . ', ' . $this->rowsPerPage;
        $active    = $this->activePage;
        $previous  = 1 === $this->activePage ? 1 : ($this->activePage - 1);
        $next      = $lastPage === $this->activePage ? $lastPage : ($this->activePage + 1);
        $next      = $next >= $lastPage ? $lastPage : $next;
        $pageSpan  = $this->getPageSpan($this->activePage, $lastPage, $next);
        $showFirst = $this->showFirstPage($pageSpan);
        $showLast  = $this->showLastPage($pageSpan, $lastPage);
        $pages     = array_merge($showFirst, $pageSpan, $showLast);

        // Compile an array containing all of the pagination info
        $this->info = [
            'display'  => 'Page ' . $active . ' of ' . $lastPage,
            'limit'    => $limit,
            'active'   => $active,
            'total'    => $lastPage,
            'previous' => $previous,
            'next'     => $next,
            'pages'    => $pages,
        ];

        // All the pagination info needed for queries and link display
        return $this->info;
    }

    /**
     * Return only the items of an array that match the page specifications.
     * ---
     * @param   array  The array of items to splice.
     * @param   int    The current page.
     * @param   int    The number of items per page.
     * @return  array  Sliced array (empty if no items could be spliced).
     */
    public function sliceArray(array $items, int $page, int $limit)
        : array
    {
        // Nothing to work with; stop here
        if (empty($items)) {
            return [];
        }

        // Controls
        $offset = $page > 1 ? (($page - 1) * $limit) : 0;
        $length = $limit > 0 ? $limit : 10;

        // Slice the array as applicable
        $slice = array_slice($items, $offset, $length, TRUE);

        // No items to return; stop here
        if (empty($slice) || count($slice) < 1 || ! is_array($slice)) {
            return [];
        }

        // Return slice
        return $slice;
    }

    /**
     * Compile an array containing the page numbers surrounding the active page.
     * ---
     * @param   int    Current page number.
     * @param   int    Previous page number.
     * @param   int    Next page number.
     * @return  array  Array of surrounding page numbers.
     */
    protected function getPageSpan($activePage, $lastPage, $nextPage)
        : array
    {
        // Start with an empty array
        $pages = [];

        // When active page is the first page
        if (1 === $activePage) {
            // There's only one page, stop here
            if ($nextPage == $activePage) {
                return [1];
            }

            // Compile an array of page numbers
            for ($i = 0; $i < $this->linkSpan; $i++) {
                // Link span is greater than page count, stop loop once it's reached
                if ($lastPage == $i) {
                    break;
                }

                // Add this page number (count started at 0, so add 1)
                $pages[] = $i + 1;
            }

            // Array of applicable page numbers
            return $pages;
        }

        // When active page is the last page
        if ($lastPage === $activePage) {
            // The page to start showing links at
            $startPage = $lastPage - $this->linkSpan;

            // Link span is greater than page count, so start at first page
            if ($startPage < 1) {
                $startPage = 0;
            }

            // Compile an array of page numbers (count starts at 0, so always add 1)
            for ($i = $startPage; $i < $lastPage; $i++) {
                $pages[] = $i + 1;
            }

            // Array of applicable page numbers
            return $pages;
        }

        // Calculate the start page
        $startPage = $activePage - $this->linkSpan;

        // Link span is greater than page count, so start at first page
        if ($startPage < 1) {
            $startPage = 0;
        }

        // Compile page numbers BEFORE active page (count starts at 0, so always add 1)
        for ($i = $startPage; $i < $activePage; $i++) {
            $pages[] = $i + 1;
        }

        // Compile page numbers AFTER active page
        for ($i = ($activePage + 1); $i < ($activePage + $this->linkSpan); $i++) {
            // Stop loop if page reaches the last page for the result set
            if ($i >= ($lastPage + 1)) {
                break;
            }

            // Add this page number
            $pages[] = $i;
        }

        // Array of applicable page numbers
        return $pages;
    }

    /**
     * Show the first page in the pagination list? An array is returned in one of the
     * following formats:
     * ---
     * ) [
     * )     0 => 1,
     * )     1 => '...',
     * ) ];
     * ---
     * ) [
     * )     0 => 1,
     * ) ];
     * ---
     * @param   array  A page span array generated by getPageSpan().
     * @return  array  Empty if first page number is already being shown.
     */
    protected function showFirstPage(array $pages)
        : array
    {
        // The first page number shown in the page list
        $firstShown = $pages[0];

        // Show first page, plus a ... text span
        if ($firstShown >= 3) {
            return [1, '...'];
        }

        // Show first page with no ... text span
        if ($firstShown === 2) {
            return [1];
        }

        // First page is already being shown
        return [];
    }

    /**
     * Show the last page in the pagination list? An array is returned in one of the
     * following automated formats:
     * ---
     * ) [
     * )     0 => '...',
     * )     1 => 13,
     * ) ];
     * ---
     * ) [
     * )     0 => 13,
     * ) ];
     * ---
     * @param   array  A page span array generated by getPageSpan().
     * @param   int    The last page in the result set.
     * @return  array  Empty if last page number is already being shown, or array.
     */
    protected function showLastPage(array $pages, $lastPage)
        : array
    {
        // The last page number shown in the page list
        $lastShown = $pages[count($pages) - 1];

        // Show last page, preceded a ... text span
        if ($lastShown <= ($lastPage - 2)) {
            return ['...', $lastPage];
        }

        // Show last page with no ... text span
        if ($lastShown === ($lastPage - 1)) {
            return [$lastPage];
        }

        // Last page is already being shown
        return [];
    }
}

<?php

class Repo_Admins extends Bridge
{
    protected $paginate = NULL;

    protected $sortBy = 'first_name';

    protected $sortType = 'asc';

    protected $search = NULL;

    public function __construct()
    {
        $this->paginate = (new Paginate())
            ->setActivePage(1)
            ->setRowsPerPage(50)
            ->setLinkSpan(3);
    }

    public function setPage(int $page)
        : self
    {
        $this->paginate->setActivePage($page);

        return $this;
    }

    public function setPerPage(int $perPage)
        : self
    {
        $this->paginate->setRowsPerPage($perPage);

        return $this;
    }

    public function setSortBy(?string $sortBy, ?string $sortType = NULL)
        : self
    {
        if (in_array($sortBy, ['first_name', 'last_name', 'email', 'status', 'access_level'])) {
            $this->sortBy = $sortBy;
        }

        if (in_array($sortType, ['asc', 'desc'])) {
            $this->sortType = $sortType;
        }

        return $this;
    }

    public function setSearch(?string $search)
        : self
    {
        $this->search = $search;

        return $this;
    }

    public function getList()
        : array
    {
        $where = ['a.id != 0'];
        $binds = [];

        if ($this->search) {
            $where[] = '(a.first_name LIKE ? OR a.last_name LIKE ? OR a.email LIKE ?)';
            $binds[] = '%' . $this->search . '%';
            $binds[] = '%' . $this->search . '%';
            $binds[] = '%' . $this->search . '%';
        }

        $tal = 'TALLY ';

        $sel = '
            SELECT a.id
                , a.first_name
                , a.last_name
                , a.email
                , a.status
                , a.access_level
            FROM
        ';

        $sql = '
            admins AS a
            WHERE ' . implode(' AND ', $where) . '
            AND a.deleted IS NULL
            ORDER BY a.' . $this->sortBy . ' ' . strtoupper($this->sortType) . '
        ';

        $count = $this->db
            ->query($tal . $sql)
            ->bind($binds)
            ->run();

        $this->paginate->setTotalRows($count);

        $rows = $this->db
            ->query($sel . $sql . $this->paginate->getQueryLimit())
            ->bind($binds)
            ->run();

        return $rows;
    }

    public function getResult()
        : array
    {
        $meta = [];

        $meta['list'] = $this->getList();

        $meta['pages'] = $this->paginate->getPages();

        $meta['text'] = $this->paginate->getDisplayText();

        return $meta;
    }
}

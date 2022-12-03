<?php

class Repo_Users extends Bridge
{
    protected $paginate = NULL;

    protected $sortBy = 'created';

    protected $sortType = 'DESC';

    protected $search = NULL;

    protected $formType = NULL;

    protected $statuses = [];

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
        if (in_array($sortBy, [
            'created',
            'company_name',
            'email',
            'request_amount',
            'form_type',
            'status',
        ])) {
            $this->sortBy = $sortBy;
        }

        if (in_array($sortType, ['asc', 'desc'])) {
            $this->sortType = strtoupper($sortType);
        }

        return $this;
    }

    public function setSearch(?string $search)
        : self
    {
        if ($search) {
            $this->search = $search;
        }

        return $this;
    }

    public function setFormType(?string $formType)
        : self
    {
        if ($formType) {
            $this->formType = $formType;
        }

        return $this;
    }

    public function setStatuses(?array $statuses)
        : self
    {
        $whitelist = array_keys(Lexicon::users_status());

        $list = [];

        if ( ! empty($statuses)) {
            foreach ($statuses as $status => $bool) {
                if ('true' === $bool && in_array($status, $whitelist)) {
                    $list[] = $status;
                }
            }
        }

        $this->statuses = $list;

        return $this;
    }

    public function getList()
        : array
    {
        $where = ["form_verified = 'Y'"];
        $binds = [];

        if ($this->search) {
            $where[] = '(reference_number LIKE ? OR company_name LIKE ? OR first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)';
            $binds[] = '%' . $this->search . '%';
            $binds[] = '%' . $this->search . '%';
            $binds[] = '%' . $this->search . '%';
            $binds[] = '%' . $this->search . '%';
            $binds[] = '%' . $this->search . '%';
        }

        if ($this->formType) {
            $where[] = 'form_type = ?';
            $binds[] = $this->formType;
        }

        if ( ! empty($this->statuses)) {
            $in = [];

            foreach ($this->statuses as $status) {
                $in[]    = '?';
                $binds[] = $status;
            }

            $where[] = 'status IN (' . implode(',', $in) . ')';
        }

        $tal = 'TALLY ';

        $sel = '
            SELECT id
                , form_type
                , request_amount
                , company_name
                , first_name
                , last_name
                , email
                , status
                , created
            FROM
        ';

        $sql = '
            users AS u
            WHERE ' . implode(' AND ', $where) . '
            AND deleted IS NULL
            ORDER BY ' . $this->sortBy . ' ' . $this->sortType . '
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

        foreach ($rows as $k => $v) {
            if (is_numeric($v['request_amount'])) {
                $v['request_amount'] = currencyFormat($v['request_amount'], TRUE, TRUE);
            }

            $v['form_type'] = 'venture_capital' === $v['form_type']
                            ? 'Venture'
                            : 'Commercial';

            $v['created'] = Tell_Date::show('m/d/y', $v['created']);

            $rows[$k] = $v;
        }

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

<?php

class Repo_UsersChanges extends Bridge
{
    protected $tracker = NULL;

    protected $crypto = NULL;

    protected $userId = NULL;

    public function __construct(Tracker $tracker, Tell_Crypto $crypto)
    {
        $this->tracker = $tracker;
        $this->crypto  = $crypto;
    }

    public function setUserId($userId)
        : self
    {
        if ($userId) {
            $this->userId = (int) $userId;
        }

        return $this;
    }

    public function getResult()
        : array
    {
        $rows = $this->tracker
            ->parent(User::TABLE, $this->userId)
            ->getHistory(TRUE, $this->crypto);

        foreach ($rows as $k => $row) {
            $created = $this->tracker::FIELD_CREATED;

            $rows[$k][$created] = Tell_Date::show('D, M jS Y - g:i A', $row[$created]);
        }

        return $rows;
    }
}

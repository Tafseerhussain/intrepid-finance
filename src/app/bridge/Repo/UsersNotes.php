<?php

class Repo_UsersNotes extends Bridge
{
    protected $crypto = NULL;

    protected $userId = NULL;

    public function __construct(Tell_Crypto $crypto)
    {
        $this->crypto = $crypto;
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
        $where = ['n.id != 0'];
        $binds = [];

        if ($this->userId) {
            $where[] = 'n.user_id = ?';
            $binds[] = $this->userId;
        }

        $sql = '
            SELECT n.id
                , n.user_id
                , n.admin_id
                , n.author_type
                , n.author_name
                , n.note
                , n.ip_address
                , n.created
            FROM users_notes AS n
            WHERE ' . implode(' AND ', $where) . '
            AND n.deleted IS NULL
            ORDER BY n.created ASC
        ';

        $rows = $this->db
            ->query($sql)
            ->bind($binds)
            ->run();

        foreach ($rows as $k => $row) {
            if ($this->crypto->isEncrypted($row['note'])) {
                $rows[$k]['note'] = $this->crypto->decrypt($this->crypto->removeFlag($row['note']));
            }

            $rows[$k]['created'] = Tell_Date::show('D, M jS Y - g:i A', $row['created']);
        }

        return $rows;
    }
}

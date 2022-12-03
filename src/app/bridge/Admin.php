<?php

class Admin extends Record
{
    const TABLE   = 'admins';
    const WHERE   = 'id = ? AND deleted IS NULL';
    const PRIMARY = 'id';

    public function getSafeFields()
        : array
    {
        $fields = ! $this->isLoaded() ? [] : [
            'id',
            'first_name',
            'last_name',
            'email',
            'status',
            'access_level',
        ];

        $data = [];

        foreach ($fields as $field) {
            $data[$field] = $this->get($field);
        }

        return $data;
    }

    public function verifyEmailAvailable(?string $email)
        : bool
    {
        if ($this->isLoaded()) {
            $sql = '
                GRAB id
                FROM admins
                WHERE id != ?
                AND email = ?
                AND deleted IS NULL
            ';

            $row = $this->db->query($sql)
                ->bind($this->get('id'), $email)
                ->run();
        } else {
            $sql = '
                GRAB id
                FROM admins
                WHERE email = ?
                AND deleted IS NULL
            ';

            $row = $this->db->query($sql)
                ->bind($email)
                ->run();
        }

        return ! $row;
    }
}

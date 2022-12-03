<?php

class UserNote extends Record
{
    const TABLE   = 'users_notes';
    const WHERE   = 'id = ? AND deleted IS NULL';
    const PRIMARY = 'id';

    protected function onLoad(Tell_Crypto $crypto, array $row)
        : array
    {
        if ($crypto->isEncrypted($row['note'])) {
            $row['note'] = $crypto->decrypt($crypto->removeFlag($row['note']));
        }

        return $row;
    }
}

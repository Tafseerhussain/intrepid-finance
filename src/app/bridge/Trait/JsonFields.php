<?php

trait Trait_JsonFields
{
    public function parseJsonFields(array $row)
        : array
    {
        foreach (static::$jsonFields as $field) {
            if (isset($row[$field])) {
                $row[$field] = Tell_Json::decode($row[$field], TRUE);
            }
        }

        return $row;
    }
}

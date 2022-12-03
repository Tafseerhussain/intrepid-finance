<?php

trait Trait_CurrencyFields
{
    public function formatCurrencyFields(array $row)
        : array
    {
        foreach (static::$currencyFields as $field) {
            if (isset($row[$field])) {
                $row[$field] = currencyFormat($row[$field], FALSE, FALSE);
            }
        }

        return $row;
    }
}

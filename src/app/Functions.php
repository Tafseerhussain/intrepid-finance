<?php

/**
 * Convert currency value from its base unit to it's smallest unit.
 * ---
 * @param   string|int|float  Value in currency's base unit.
 * @param   string            [?] 3-letter currency code. Default currency if NULL.
 * @return  int|float         Value in smallest unit for applicable currency.
 */
function toCurrencyBase($units, string $currency = NULL)
{
    $bridge = Tell_Bridge_Currency::factory($currency);

    return $units * $bridge->unit();
}

/**
 * Convert currency value stored as smallest unit to the currency's base unit.
 * ---
 * @param   string|int|float  Value in smallest unit for applicable currency.
 * @param   string            [?] 3-letter currency code. Default currency if NULL.
 * @return  int|float         Value in currency's base unit.
 */
function fromCurrencyBase($units, string $currency = NULL)
{
    $bridge = Tell_Bridge_Currency::factory($currency);

    return $units / $bridge->unit();
}

/**
 * Format currency value, with the smallest currency unit as the amount.
 * ---
 * @param   string|int|float  Value in smallest unit for applicable currency.
 * @param   bool              [F] Include unit separator (usually ',')? Default = FALSE.
 * @param   bool              [F] — Include symbol (i.e. '$')? Default = FALSE.
 * @param   string            [?] 3-letter currency code. Default currency if NULL.
 * @return  string            Formatted currency amount.
 */
function currencyFormat(
    $units,
    bool   $separator = FALSE,
    bool   $symbol    = FALSE,
    string $currency  = NULL
) : string {
    $amount = fromCurrencyBase($units, $currency);

    return currency($amount, $separator, $symbol, $currency);
}

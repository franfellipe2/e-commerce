<?php
/**
 * Passa um numero para o formato de Real brasileiro, exemplo: 2.000,10
 * @param float $value
 * @return type
 */
function formatPrice(float $value) {
    return number_format($value, 2, ',', '.');
}

/**
 * Passa um numero para o formato mysql, exemplo: 2000.10
 * @param float $value
 * @return type
 */
function formatValueToDecimal(float $value) {
    $value = str_replace('.', '', $value);
    return str_replace(',', '.', $value);
}
<?php

function formatPrice(float $value) {
    return number_format($value, 2, ',', '.');
}

function formatValueToDecimal(float $value) {
    $value = str_replace('.', '', $value);
    return str_replace(',', '.', $value);
}

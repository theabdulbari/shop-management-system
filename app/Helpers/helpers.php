<?php

if (!function_exists('formatWeight')) {
    /**
     * Format weight in grams to kg + gm
     *
     * @param float|int $grams
     * @return string
     */
function formatWeight($value, $unit = 'gm')
{
    if ($value <= 0) return "0 $unit";

    // --- GRAM LOGIC (gm → kg + gm) ---
    if ($unit === 'gm') {

        // If less than 1 gm (decimal), convert to gm
        if ($value < 1) {
            $gm = round($value * 100);
            return "{$gm} gm";
        }

        $kg = floor($value / 1000);
        $gm = $value % 1000;

        if ($kg > 0 && $gm > 0) {
            return "{$kg} kg {$gm} gm";
        } elseif ($kg > 0) {
            return "{$kg} kg";
        } else {
            return "{$gm} gm";
        }
    }

    // --- MILLILITER LOGIC (ml → liter + ml) ---
    if ($unit === 'ml') {

        // If less than 1 ml (decimal), convert to ml
        if ($value < 1) {
            $ml = round($value * 100);
            return "{$ml} ml";
        }

        $liter = floor($value / 1000);
        $ml = $value % 1000;

        if ($liter > 0 && $ml > 0) {
            return "{$liter} L {$ml} ml";
        } elseif ($liter > 0) {
            return "{$liter} L";
        } else {
            return "{$ml} ml";
        }
    }

    // fallback
    return "$value $unit";
}

}

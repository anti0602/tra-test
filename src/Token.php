<?php

namespace App\Services\GoogleTranslate\src;


use GoogleTranslate;

class Token
{
    public static function generate($text, $windowTkk)
    {
//        $windowTkk = "412320.3361919554";
        // STEP 1: spread the the query char codes on a byte-array, 1-3 bytes per char
        $bytesArray = GoogleTranslate\mb_str_to_array($text);

        // STEP 2: starting with TKK index, add the array from last step one-by-one, and do 2 rounds of shift+add/xor
        $d = explode('.', $windowTkk);

        $tkkIndex = intval($d[0]);
        $tkkIndex = $tkkIndex ? $tkkIndex : 0;

        $tkkKey = intval($d[1]);
        $tkkKey = $tkkKey ? $tkkKey : 0;

        $encondingRound1 = array_reduce($bytesArray, function ($acc, $current) {
            $acc += $current;
            return GoogleTranslate\shiftLeftOrRightThenSumOrXor($acc, "+-a^+6");
        }, $tkkIndex);
        $encondingRound2 = GoogleTranslate\shiftLeftOrRightThenSumOrXor($encondingRound1, "+-3^+b+-f");
        $encondingRound2 = GoogleTranslate\intval32($encondingRound2 ^ (int)$d[1]);
        0 > $encondingRound2 && ($encondingRound2 = ($encondingRound2 & 2147483647) + 2147483648);
        $encondingRound2 %= 1E6;
        return $encondingRound2 . '.' . ($encondingRound2 ^ $tkkIndex);
    }
}
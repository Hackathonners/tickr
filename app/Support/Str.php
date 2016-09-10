<?php

namespace App\Support;

class Str extends \Illuminate\Support\Str
{
    /**
     * Returns the searchable string.
     *
     * @param  mixed $array
     * @param bool $trim
     * @return mixed $array
     */
    public static function searchable($string, $replace = false, $min = 3, $charReplace = '%')
    {
        // Remove duplicated spaces
        $string = preg_replace('/\s+/', ' ', self::lower(self::ascii(trim($string))));
        $splitString = explode(' ', $string);

        $terms = array_filter($splitString, function ($term) use ($min) {
            return strlen($term) >= $min;
        });

        // There are terms with 3 chars, concatenate them and use it as search string.
        if (count($terms) > 0) {
            return implode($replace ? $charReplace : ' ', $terms);
        }

        return $string;
    }
}

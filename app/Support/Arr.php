<?php

namespace App\Support;

class Arr extends \Illuminate\Support\Arr
{
    /**
     * Recursively remove empty keys from array.
     *
     * @param  mixed $array
     * @param bool $trim
     * @return mixed $array
     */
    public static function clean($array, $trim = false)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = static::clean($array[$key]);
            } else {
                $array[$key] = $trim ? trim($array[$key]) : $array[$key];
            }

            if (empty($array[$key])) {
                unset($array[$key]);
            }
        }

        return $array;
    }
}

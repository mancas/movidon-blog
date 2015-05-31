<?php

namespace Movidon\FrontendBundle\Util;

class ArrayHelper
{
    public static function multiLevelArrayToSingleLevel($multiArray)
    {
        $singleArray = array();
        foreach ($multiArray as $key => $value) {
            if (!is_array($value)) {
                $singleArray[$key] = $value;
            } else {
                $singleArray = array_merge($singleArray, self::multiLevelArrayToSingleLevel($value, $singleArray));
            }
        }

        return $singleArray;
    }

    public static function removeObjectFromArray($objects, $objeto)
    {
        foreach ($objects as $k => $object) {
            if ($objeto->getId() == $object['id']) {
                unset($objects[$k]);
                break;
            }
        }

        return $objects;
    }

    public static function getBoundPosition($value, $arrayValues)
    {
        $result = 0;
        $min = min($arrayValues);
        $max = max($arrayValues);
        if ($max - $min < 2) {
            if ($value == $min) {
                $result = 1;
            } else {
                $result = 3;
            }
        } elseif ($max - $min < 3) {
            if ($value == $min) {
                $result = 1;
            } elseif ($value == $min + 1) {
                $result = 2;
            } else {
                $result = 3;
            }
        } elseif ($max - $min < 4) {
            ld($value);
            if ($value == $min) {
                $result = 1;
            } elseif ($value == $min + 1) {
                $result = 2;
            } elseif ($value == $min + 2) {
                $result = 3;
            } else {
                $result = 4;
            }
        } else {
            $range = ($max - $min) / 5;
            $range1 = $min + $range;
            $range2 = $range1 + $range;
            $range3 = $range2 + $range;
            $range4 = $range3 + $range;
            $result = 0;
            if ($value < $range1) {
                $result = 1;
            } elseif ($value < $range2) {
                $result = 2;
            } elseif ($value < $range3) {
                $result = 3;
            } elseif ($value < $range4) {
                $result = 4;
            } else {
                $result = 5;
            }
        }

        return $result;
    }
}
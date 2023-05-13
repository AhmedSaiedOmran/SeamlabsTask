<?php

namespace App\CustomClasses;

class TaskHelper
{
    public static function countNumWithRange($startNumber, $endNumber)
    {
        $rangeArray = range($startNumber, $endNumber);

        $rangeArrayWithoutFive = array_filter($rangeArray, function ($item) {
            return !preg_match('/5/', $item);
        });

        return count($rangeArrayWithoutFive);
    }

    public static function countNumWithRangeWithChunks($startNumber, $endNumber)
    {
        $totalNumbersElements = abs($startNumber) + abs($endNumber);

        $numOfChunks = intval($totalNumbersElements / 1000000);
        $chunksSteps = intval($totalNumbersElements / $numOfChunks);

        $rangeArray = range($startNumber, $endNumber, $chunksSteps);
        $countRange = 0;
        for ($i = 1; $i < count($rangeArray); $i++) {
            $subRange = [];
            if ($i == 1) {
                $subRange = range($startNumber, $rangeArray[1]);
            } else if ($i == count($rangeArray) - 1) {
                $startNumberSubRange = $rangeArray[$i - 1] + 1;
                $subRange = range($startNumberSubRange, $endNumber);
            } else {
                $startNumberSubRange = $rangeArray[$i - 1] + 1;
                $subRange = range($startNumberSubRange, $rangeArray[$i]);
            }
            $rangeArrayWithoutFive = array_filter($subRange, function ($item) {
                return !preg_match('/5/', $item);
            });
            $countRange += count($rangeArrayWithoutFive);
        }
        return $countRange;
    }

    public static function indexOfString($inputString)
    {
        $inputString = strtoupper($inputString);

        $indexCounter = 0;

        $strArrayRev = array_reverse(str_split($inputString));

        for ($i = 0; $i < strlen($inputString); $i++) {
            $indexCounter += (ord($strArrayRev[$i]) - 64) * pow(26, $i);
        }
        return $indexCounter;
    }

    public static function calculateSteps($number)
    {
        $steps = 0;
        do {
            if ( TaskHelper::primeCheck($number) || $number <= 3 ) {
                $number--;
            } else {
                for($i = 2; $i <= $number/2 ; $i++) {
                    if($number % $i == 0) {
                        $number = max([$i, ($number/$i)]);
                        break;
                    }
                }
            }
            $steps++;
        } while ($number > 0);
        return $steps;
    }

    public static function primeCheck($number)
    {
        if ($number == 1) return 0;

        for ($i = 2; $i <= $number / 2; $i++) {
            if ($number % $i == 0)
                return 0;
        }

        return 1;
    }
}

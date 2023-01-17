<?php

namespace App\Service;

use DateTime;

class HelperService
{

    public function getDates(string $dates): array
    {
        $resultDates = explode('>', $dates);

        return [
            'checkin' => trim($resultDates[0]),
            'checkout' => trim($resultDates[1])
        ];
    }

    public function transformDates(string $currentFormat, string $date, $newFormat): string
    {
        return DateTime::createFromFormat($currentFormat, $date)->format($newFormat);
    }
}
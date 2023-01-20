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

    public function transformDates(string $currentFormat, string $date,string $newFormat): string
    {
        return DateTime::createFromFormat($currentFormat, $date)->format($newFormat);
    }

    /**
     * @throws \Exception
     */
    public function getNumberOfNights(string $checkin, string $checkout): string
    {
        $checkinDate = new DateTime($checkin);
        $checkoutDate = new DateTime($checkout);
        return $checkoutDate->diff($checkinDate)->format("%a");
    }
}
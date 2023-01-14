<?php

namespace App\Service;

use App\Entity\Amenity;
use App\Entity\Booking;
use App\Entity\RoomType;
use App\Repository\BookingRepository;
use App\Repository\RoomTypeRepository;
use DateTime;

class SearchService implements SearchServiceInterface
{
    public function __construct(
        private readonly BookingRepository $bookingRepository,
        private readonly RoomTypeRepository $roomTypeRepository,
        private readonly HelperService $helperService
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function searchRoomTypes(array $parameters, array $selectedAmenities): array
    {
        $dates = $this->helperService->getDates($parameters['dates']);
        $adults = $parameters['adults'];
        $childs = $parameters['childs'];

        $checkin = $this->helperService->transformDates('m-d-y', $dates['checkin'], 'd-m-Y');
        $checkout = $this->helperService->transformDates('m-d-y', $dates['checkout'], 'd-m-Y');

        $resRoom = $this->roomTypeRepository->findValidRoomTypes($adults, $childs);

        $validRoomTypes = [];
        /** @var RoomType $roomType */
        foreach ($resRoom as $roomType) {
            if($this->checkRoomType($roomType, $checkin, $checkout, $selectedAmenities)) {
                $validRoomTypes[] = $roomType;
            }
        }
        return $validRoomTypes;
    }

    public function checkRoomType(RoomType $roomType, string $checkin, string $checkout, array $selectedAmenities = []): bool
    {
        $keepRoomType = false;
        $rooms = $roomType->getRooms();
        foreach ($rooms as $room){
            $bookedRooms = $room->getBookings()->toArray();
            if(
                $this->isFreeRoom($checkin, $checkout, $bookedRooms) &&
                $this->hasAllAmenities($roomType->getAmenities()->toArray(), $selectedAmenities)
            ) {
                $keepRoomType = true;
                break;
            }
        }

        return $keepRoomType;
    }

    private function hasAllAmenities(array $roomAmenities, array $selectedAmenities): bool
    {

        //dd($roomAmenities,$selectedAmenities);
        if(\count($selectedAmenities) === 0) {
            return true;
        }
        $hasAllAmenities = true;
        /** @var Amenity $selectedAmenity */
        foreach ($selectedAmenities as $selectedAmenity) {
            $amenityExists = false;
            /** @var Amenity $roomAmenity */
            foreach ($roomAmenities as $roomAmenity) {
                if($roomAmenity->getId() === $selectedAmenity->getId()) {
                    $amenityExists = true;
                    break;
                }
            }
            if($amenityExists === false) {
                $hasAllAmenities = false;
            }
        }

        return $hasAllAmenities;

    }
    private function isFreeRoom(string $checkin, string $checkout, array $bookedRooms): bool
    {
        if(\count($bookedRooms) === 0) {
            return true;
        }
        $keepRoom = false;
        /** @var Booking $bookedRoom */
        foreach ($bookedRooms as $bookedRoom) {

            $bookCheckin = $bookedRoom->getCheckin()->format('d-m-Y');
            $bookCheckout = $bookedRoom->getCheckout()->format('d-m-Y');

            $overlap = false;


            if($checkin >= $bookCheckin && $checkin < $bookCheckout && $checkout > $bookCheckout) {
                $overlap = true;
            }

            if(
                $checkin <= $bookCheckout &&
                $checkin >= $bookCheckin &&
                $checkout >= $bookCheckin &&
                $checkout <= $bookCheckout
            ) {
                $overlap = true;
            }

            if($checkin <= $bookCheckin && $checkout >= $bookCheckout) {
                $overlap = true;
            }

            if($checkin < $bookCheckin && $checkout > $bookCheckin && $checkout <= $bookCheckout) {
                $overlap = true;
            }

            if($overlap === false) {
                $keepRoom = true;
                break;
            }
        }

        return $keepRoom;
    }
}
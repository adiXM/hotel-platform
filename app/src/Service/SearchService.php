<?php

namespace App\Service;

use App\Entity\Amenity;
use App\Entity\Booking;
use App\Entity\Room;
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
    public function searchRoomTypes(array $bookingData, array $selectedAmenities): array
    {

        $adults = $bookingData['adults'];
        $childs = $bookingData['childs'];
        $checkin = $this->helperService->transformDates('d-m-Y', $bookingData['checkin'], 'Y-m-d');
        $checkout = $this->helperService->transformDates('d-m-Y', $bookingData['checkout'], 'Y-m-d');

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

    public function checkRoomType(RoomType $roomType, string $checkin, string $checkout, array $selectedAmenities = [], Room &$roomEntity = null): bool
    {
        $checkin = $this->helperService->transformDates('d-m-Y', $checkin, 'Y-m-d');
        $checkout = $this->helperService->transformDates('d-m-Y', $checkout, 'Y-m-d');

        $keepRoomType = false;
        $rooms = $roomType->getRooms();
        foreach ($rooms as $room){
            $bookedRooms = $room->getBookings()->toArray();
            if(
                $this->isFreeRoom($checkin, $checkout, $bookedRooms) &&
                $this->hasAllAmenities($roomType->getAmenities()->toArray(), $selectedAmenities)
            ) {
                $roomEntity = $room;
                $keepRoomType = true;
                break;
            }
        }

        return $keepRoomType;
    }

    private function hasAllAmenities(array $roomAmenities, array $selectedAmenities): bool
    {

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
        $keepRoom = true;

        /** @var Booking $bookedRoom */
        foreach ($bookedRooms as $bookedRoom) {

            $bookCheckin = $bookedRoom->getCheckin()->format('Y-m-d');
            $bookCheckout = $bookedRoom->getCheckout()->format('Y-m-d');

            $overlap = false;

            if($checkin >= $bookCheckin && $checkin < $bookCheckout && $checkout >= $bookCheckout) {
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

            if($overlap === true) {
                $keepRoom = false;
                break;
            }
        }

        return $keepRoom;
    }
}
<?php

namespace App\DataFixtures;

use App\Entity\Room;
use App\Entity\RoomType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoomFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {

        $roomType = new RoomType();
        $roomType->setName('Regular');
        $roomType->setDescription('This is a regular room');

        $room = new Room();
        $room->setActive(true);
        $room->setPrice(63);
        $room->setRoomNumber(101);
        $room->setRoomType($roomType);

        $manager->persist($roomType);
        $manager->persist($room);
        $manager->flush();
    }
}
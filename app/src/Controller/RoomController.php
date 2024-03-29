<?php

namespace App\Controller;

use App\Entity\Amenity;
use App\Entity\Media;
use App\Entity\RoomType;
use App\Form\Frontend\Homepage\SearchFormType;
use App\Service\Frontend\DataManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends AbstractController
{
    public function __construct(private readonly DataManagerInterface $dataManager)
    {
    }

    #[Route('/room/{id}', name: 'app_room_details')]
    public function index(RoomType $room, Request $request): Response
    {
        $amenities = $room->getAmenities()->getValues();
        $amenitiesList = [];
        foreach ($amenities as $amenity) {
            $amenitiesList[] = [
                'name' => $amenity->getName(),
                'icon_class' => $amenity->getIconClass()
            ];
        }

        $mediaList = $room->getMedia();
        $mediaRoom = [];
        /** @var Media $media */
        foreach($mediaList as $media) {
            $mediaRoom[] = $this->getParameter('public_media_directory').'/'.$media->getFileName();
        }

        return $this->render('pages/rooms/singleroom.html.twig', [
            'room' => [
                'name' => $room->getName(),
                'description' => $room->getDescription(),
                'price' => $room->getPrice(),
                'amenities' => $amenitiesList,
                'media' => $mediaRoom
            ]
        ]);
    }
}

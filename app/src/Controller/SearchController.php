<?php

namespace App\Controller;

use App\Entity\Amenity;
use App\Entity\Room;
use App\Entity\RoomType;
use App\Form\Customer\CustomerFormType;
use App\Form\Frontend\Homepage\AmenityFormType;
use App\Form\Frontend\Homepage\SearchFormType;
use App\Service\Frontend\DataManagerInterface;
use App\Service\SearchServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    public function __construct(
        private readonly SearchServiceInterface $searchService,
        private readonly DataManagerInterface $dataManager)
    {
    }

    #[Route('/search', name: 'app_search_result')]
    public function index(Request $request): Response
    {

        $queryAmenities = $request->query->get('amenities');

        $amenitiesForm = $this->createForm(AmenityFormType::class,
            $this->dataManager->getAllAmenities($queryAmenities)
        );

        $amenitiesForm->handleRequest($request);

        $selectedAmenities = $amenitiesForm->get('amenities')->getData();
        if($selectedAmenities === null) {
            $selectedAmenities = new ArrayCollection();
        }
        if ($amenitiesForm->isSubmitted() && $amenitiesForm->isValid()) {
            $selectedAmenities = $amenitiesForm->get('amenities')->getData();
            $amenityIds = [];
            /** @var Amenity $selectedAmenity */
            foreach ($selectedAmenities as $selectedAmenity) {
                $amenityIds[] = $selectedAmenity->getId();
            }
            $request->query->set('amenities', implode(',', $amenityIds));
            return $this->redirectToRoute('app_search_result', $request->query->all());
        }

        $result = $this->searchService->searchRoomTypes($request->query->all(), $selectedAmenities->toArray());
        $roomTypes = [];
        /** @var RoomType $roomType */
        foreach($result as $roomType) {
            $roomTypes[] = [
                'id' => $roomType->getId(),
                'name' => $roomType->getName(),
                'price' => $roomType->getPrice(),
                'description' => $roomType->getDescription(),
                'adults' => $roomType->getAdults(),
                'childs' => $roomType->getChilds()
            ];
        }

        return $this->render('pages/search/search_result.html.twig', [
            'roomTypes' => $roomTypes,
            'amenityFields' => $amenitiesForm->createView(),
            'selected' => $selectedAmenities
        ]);
    }
}
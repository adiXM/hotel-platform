<?php

namespace App\Controller;

use App\Form\Frontend\Homepage\SearchFormType;
use App\Service\Frontend\DataManagerInterface;
use App\Service\HelperService;
use App\Service\ValidatorServiceInterface;
use Cassandra\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;


class HomepageController extends AbstractController
{
    public function __construct(
        private readonly DataManagerInterface $dataManager,
        private readonly HelperService $helperService,
    )
    {
    }

    #[Route('/', name: 'app_homepage')]
    public function index(Request $request, SessionInterface $session): Response
    {
        $searchForm = $this->createForm(SearchFormType::class);

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            $dates = $searchForm->get('dates')->getData();
            $dates = $this->helperService->getDates($dates);
            $checkin = $this->helperService->transformDates('m-d-y', $dates['checkin'], 'd-m-Y');
            $checkout = $this->helperService->transformDates('m-d-y', $dates['checkout'], 'd-m-Y');

            $data = [
                'checkin'   => $checkin,
                'checkout'  => $checkout,
                'adults'    => $searchForm->get('adults')->getData(),
                'childs'    => $searchForm->get('childs')->getData()
            ];

            $this->dataManager->setBookingData($session, $data);

            return $this->redirectToRoute('app_search_result');
        }

        $roomTypes = $this->dataManager->getHomepageRooms();

        foreach($roomTypes as $key => $roomType) {
            $roomTypes[$key]['main_image'] = $this->getParameter('public_media_directory').'/'.$roomType['main_image'];
        }

        return $this->render('pages/homepage/index.html.twig', [
            'search_form' => $searchForm->createView(),
            'rooms' => $roomTypes,
        ]);
    }
}

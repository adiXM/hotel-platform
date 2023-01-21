<?php

namespace App\Controller\Admin;


use App\Entity\Amenity;
use App\Entity\Room;
use App\Form\AmenityFormType;
use App\Form\DeleteFormType;
use App\Form\RoomEditType;
use App\Form\RoomFormType;
use App\Service\EntityManagerServices\AmenityManagerInterface;
use App\Service\TableService;
use App\Table\AmenityTableType;
use App\Table\RoomTableType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AmenityController extends AbstractController
{
    public function __construct(
        private readonly TableService $tableService,
        private readonly AmenityManagerInterface $amenityManagerService,
    )
    {
    }

    #[Route('/admin/room/amenities', name: 'admin_room_amenities')]
    public function index(Request $request): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $table = $this->tableService->createTableType(AmenityTableType::class)
            ->createAdapter(ArrayAdapter::class, $this->amenityManagerService->getAmenitiesList())->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        $amenity = new Amenity();

        $amenityForm = $this->createForm(AmenityFormType::class, $amenity);
        $amenityForm->handleRequest($request);

        if ($amenityForm->isSubmitted() && $amenityForm->isValid()) {

            /** @var Amenity $amenityFormData */
            $amenityFormData = $amenityForm->getData();

            try {
                $this->amenityManagerService->updateAmenity($amenityFormData);

                $this->addFlash('success', 'Your changes were saved!');

            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());
            }

            return $this->redirectToRoute('admin_room_amenities');
        }

        $deleteForm = $this->createForm(DeleteFormType::class);
        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {

            $roomId = $deleteForm->get('rowId')->getData();

            try {
                $this->amenityManagerService->removeAmenity($roomId);

                $this->addFlash('success', 'Your changes were saved!');

            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());
            }

            return $this->redirectToRoute('admin_room_amenities');
        }

        return $this->render('admin/pages/amenities/index.html.twig', [
            'form' => $amenityForm->createView(),
            'deleteForm' => $deleteForm->createView(),
            'roomsTable' => $table
        ]);
    }

    #[Route('/admin/amenity/{id}', name: 'admin_edit_amenity')]
    public function edit(Amenity $amenity, Request $request)
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $amenityEditForm = $this->createForm(AmenityFormType::class, $amenity);

        $amenityEditForm->handleRequest($request);

        if ($amenityEditForm->isSubmitted() && $amenityEditForm->isValid()) {

            /** @var Amenity $amenityData */
            $amenityData = $amenityEditForm->getData();

            try {
                $this->amenityManagerService->updateAmenity($amenityData);

                $this->addFlash('notice', 'Your changes were saved!');

            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());
            }

            return $this->redirectToRoute('admin_edit_amenity', ['id' => $amenityData->getId()]);
        }

        return $this->render('admin/pages/amenities/singlepage.html.twig', [
            'form' => $amenityEditForm->createView(),
        ]);
    }

}
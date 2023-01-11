<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use App\Entity\RoomType;
use App\Form\DeleteFormType;
use App\Form\RoomEditType;
use App\Form\RoomFormType;
use App\Form\RoomTypeEditType;
use App\Form\RoomTypeFormType;
use App\Service\EntityManagerServices\RoomTypeManagerInterface;
use App\Service\TableService;
use App\Table\RoomTypeTableType;
use Doctrine\ORM\PersistentCollection;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoomTypeController extends AbstractController
{
    public function __construct(
        private readonly TableService $tableService,
        private readonly RoomTypeManagerInterface $roomTypeManagerService,
    )
    {
    }

    #[Route('/admin/room/types', name: 'admin_room_types')]
    public function index(Request $request): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $table = $this->tableService->createTableType(RoomTypeTableType::class)
            ->createAdapter(ArrayAdapter::class, $this->roomTypeManagerService->getRoomTypeList())->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        $roomType = new RoomType();

        $roomTypeForm = $this->createForm(RoomTypeFormType::class, $roomType);
        $roomTypeForm->handleRequest($request);

        if ($roomTypeForm->isSubmitted() && $roomTypeForm->isValid()) {

            /** @var RoomType $roomTypeFormData */
            $roomTypeFormData = $roomTypeForm->getData();

            try {
                $this->roomTypeManagerService->updateRoomType($roomTypeFormData);

                $this->addFlash('success', 'Your changes were saved!');

            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());
            }

            return $this->redirectToRoute('admin_room_types');
        }

        $deleteForm = $this->createForm(DeleteFormType::class);
        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {

            $roomId = $deleteForm->get('rowId')->getData();

            try {
                $this->roomTypeManagerService->removeRoomType($roomId);

                $this->addFlash('success', 'Your changes were saved!');

            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());
            }

            return $this->redirectToRoute('admin_room_types');
        }

        return $this->render('admin/pages/room_types/index.html.twig', [
            'form' => $roomTypeForm->createView(),
            'deleteForm' => $deleteForm->createView(),
            'roomsTable' => $table
        ]);
    }

    #[Route('/admin/rooms/type/{id}', name: 'admin_edit_room_type')]
    public function edit(RoomType $room, Request $request)
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $roomTypeEditForm = $this->createForm(RoomTypeEditType::class, $room);

        $roomTypeEditForm->handleRequest($request);

        if ($roomTypeEditForm->isSubmitted() && $roomTypeEditForm->isValid()) {


            $nr = new RoomType();
            /** @var RoomType $roomTypeData */
            $roomTypeData = $roomTypeEditForm->getData();

            try {
                $this->roomTypeManagerService->updateRoomType($roomTypeData);

                $this->addFlash('notice', 'Your changes were saved!');

            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());
            }

            return $this->redirectToRoute('admin_edit_room_type', ['id' => $roomTypeData->getId()]);
        }

        return $this->render('admin/pages/room_types/singlepage.html.twig', [
            'form' => $roomTypeEditForm->createView(),
        ]);
    }
}
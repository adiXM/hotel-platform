<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use App\Form\DeleteFormType;
use App\Form\RoomEditType;
use App\Form\RoomFormType;
use App\Service\EntityManagerServices\RoomManagerInterface;
use App\Service\TableService;
use App\Table\RoomTableType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends AbstractController
{
    public function __construct(
        private readonly TableService $tableService,
        private readonly RoomManagerInterface $roomManagerService,
    )
    {
    }

    #[Route('/admin/rooms', name: 'admin_rooms')]
    public function index(Request $request): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $table = $this->tableService->createTableType(RoomTableType::class)
            ->createAdapter(ArrayAdapter::class, $this->roomManagerService->getRoomList())->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        $room = new Room();

        $roomForm = $this->createForm(RoomFormType::class, $room);
        $roomForm->handleRequest($request);

        if ($roomForm->isSubmitted() && $roomForm->isValid()) {

            /** @var Room $roomFormData */
            $roomFormData = $roomForm->getData();

            try {
                $this->roomManagerService->updateRoom($roomFormData);

                $this->addFlash('success', 'Your changes were saved!');

            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());
            }

            return $this->redirectToRoute('admin_rooms');
        }

        $deleteForm = $this->createForm(DeleteFormType::class);
        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {

            $roomId = $deleteForm->get('roomId')->getData();

            try {
                $this->roomManagerService->removeRoom($roomId);

                $this->addFlash('success', 'Your changes were saved!');

            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());
            }

            return $this->redirectToRoute('admin_rooms');
        }

        return $this->render('admin/pages/rooms/index.html.twig', [
            'form' => $roomForm->createView(),
            'deleteForm' => $deleteForm->createView(),
            'roomsTable' => $table
        ]);
    }

    #[Route('/admin/rooms/{id}', name: 'admin_edit_room')]
    public function edit(Room $room, Request $request)
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $roomEditForm = $this->createForm(RoomEditType::class, $room);

        $roomEditForm->handleRequest($request);

        if ($roomEditForm->isSubmitted() && $roomEditForm->isValid()) {

            /** @var Room $roomData */
            $roomData = $roomEditForm->getData();

            try {
                $this->roomManagerService->updateRoom($roomData);

                $this->addFlash('notice', 'Your changes were saved!');

            } catch (\Exception | UniqueConstraintViolationException $ex) {
                if($ex instanceof  UniqueConstraintViolationException) {
                    $this->addFlash('danger', sprintf("Room with number %s already exists.", $roomData->getRoomNumber()));
                } else {
                    $this->addFlash('danger', $ex->getMessage());
                }

            }

            return $this->redirectToRoute('admin_edit_room', ['id' => $roomData->getId()]);
        }

        return $this->render('admin/pages/rooms/singlepage.html.twig', [
            'form' => $roomEditForm->createView(),
        ]);
    }
}
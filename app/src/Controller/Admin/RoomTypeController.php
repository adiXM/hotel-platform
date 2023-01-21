<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Entity\RoomType;
use App\Form\DeleteFormType;
use App\Form\RoomTypeEditType;
use App\Form\RoomTypeFormType;
use App\Service\EntityManagerServices\RoomTypeManagerInterface;
use App\Service\MediaServiceInterface;
use App\Service\TableService;
use App\Table\RoomTypeTableType;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoomTypeController extends AbstractController
{
    public function __construct(
        private readonly TableService $tableService,
        private readonly RoomTypeManagerInterface $roomTypeManagerService,
        private readonly MediaServiceInterface $mediaService
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

            /** @var Media[] $mediaRoomTypeList */
            $mediaRoomTypeList = $roomTypeForm->get('media')->getData();

            try {

                $mediaNames = [];
                /** @var UploadedFile $mediaFile */
                foreach ($mediaRoomTypeList as $mediaFile) {
                    $mediaNames[] = $this->mediaService->uploadMedia($mediaFile, $this->getParameter('media_directory'));
                }

                $this->roomTypeManagerService->updateRoomType($roomTypeFormData, $mediaNames);

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
    public function edit(RoomType $room, Request $request): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $roomTypeEditForm = $this->createForm(RoomTypeEditType::class, $room, [
            'roomTypeId' => $room->getId(),
            'public_media_directory' => $this->getParameter('public_media_directory'),
            'media_directory' => $this->getParameter('media_directory')
        ]);


        $roomTypeEditForm->handleRequest($request);

        if ($roomTypeEditForm->isSubmitted() && $roomTypeEditForm->isValid()) {

            /** @var RoomType $roomTypeData */
            $roomTypeData = $roomTypeEditForm->getData();

            /** @var Media[] $mediaRoomTypeList */
            $mediaRoomTypeList = $roomTypeEditForm->get('media_select')->getData();;

            $mediaNames = [];
            /** @var UploadedFile $mediaFile */
            foreach ($mediaRoomTypeList as $mediaFile) {
                $mediaNames[] = $this->mediaService->uploadMedia($mediaFile, $this->getParameter('media_directory'));
            }

            try {
                $this->roomTypeManagerService->updateRoomType($roomTypeData, $mediaNames);

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
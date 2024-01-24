<?php

namespace App\Controller\Back;

use DateTimeImmutable;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MediaAdminController extends AbstractController {
    /**
     * @Route("/backoffice/media/{id}/update", name="backoffice_media_update")
     */
    public function index(Request $request, MediaRepository $mediaRepository, EntityManagerInterface $em, $id): Response {

        $media = $mediaRepository->find($id);

        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createdAt = new DateTimeImmutable();
            $media->setUpdatedAt($createdAt);

            $em->persist($media);
            $em->flush();

            $this->addFlash('success', 'Media modifié');
            return $this->redirectToRoute('app_backoffice');
        }

        return $this->renderForm('back/medias/update.html.twig', [
            'form' => $form,
            'media' => $media
        ]);
    }
}

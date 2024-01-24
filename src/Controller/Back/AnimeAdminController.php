<?php

namespace App\Controller\Back;

use App\Entity\Media;
use App\Form\AnimeType;
use DateTimeImmutable;
use App\Service\MediaService;
use App\Repository\MediaRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backoffice/animes/", name="backoffice_animes_")
 */
class AnimeAdminController extends AbstractController {

    private $mediaService;
    private $mediaRepository;
    private $em;
    // injection des services
    public function __construct(MediaService $mediaService, MediaRepository $mediaRepository, EntityManagerInterface $em) {

        $this->mediaService = $mediaService;
        $this->mediaRepository = $mediaRepository;
        $this->em = $em;
    }

    /**
     * @Route("list", name="list")
     */
    public function list(): Response {
        $animes = $this->mediaService->getAllMedias('anime');
        //
        return $this->render('back/animes/list.html.twig', [
            'animes' => $animes,
        ]);
    }


    /**
     * @Route("add", name="add", methods={"GET", "POST"})
     */

    public function add(Request $request, CategoryRepository $categoryRepository): Response {

        $media = new Media();
        $categoryId = $this->mediaRepository->getCategoryId('anime');
        $category = $categoryRepository->find($categoryId);

        $form = $this->createForm(AnimeType::class, $media);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createdAt = new DateTimeImmutable();
            $media->setCreatedAt($createdAt);

            $media->setCategory($category);

            $this->em->persist($media);
            $this->em->flush();

            $this->addFlash('success', 'Animé ajouté');
            return $this->redirectToRoute('backoffice_animes_list');
        }
        return $this->renderForm('back/animes/add.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("{id}/update", name="update", methods={"GET", "POST"})
     */

    public function update(Request $request, $id): Response {

        $anime = $this->mediaRepository->find($id);

        $form = $this->createForm(AnimeType::class, $anime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updatedAt = new DateTimeImmutable();
            $anime->setUpdatedAt($updatedAt);

            $this->em->persist($anime);
            $this->em->flush();

            $this->addFlash('success', 'L\'anime a bien été modifié');
            return $this->redirectToRoute('backoffice_animes_list');
        }

        return $this->renderForm('back/animes/update.html.twig', [
            'form' => $form,
            'anime' => $anime,
        ]);
    }

    /**
     * @Route("{id}/delete", name="delete", methods={"GET"})
     */

    public function delete($id): Response {

        $anime = $this->mediaRepository->find($id);
        $this->mediaRepository->remove($anime, true);
        $this->addFlash('success', 'Animé supprimée');

        return $this->redirectToRoute('backoffice_animes_list', [], Response::HTTP_SEE_OTHER);
    }
}

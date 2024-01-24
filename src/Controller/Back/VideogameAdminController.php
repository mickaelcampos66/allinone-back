<?php

namespace App\Controller\Back;

use App\Form\VideoGameType;
use App\Entity\Media;
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
 * @Route("/backoffice/videogames/", name="backoffice_videogames_")
 */
class VideogameAdminController extends AbstractController {

    private $mediaService;
    private $mediaRepository;
    private $em;

    public function __construct(MediaService $mediaService, MediaRepository $mediaRepository, EntityManagerInterface $em) {

        $this->mediaService = $mediaService;
        $this->mediaRepository = $mediaRepository;
        $this->em = $em;
    }

    /**
     * @Route("list", name="list")
     */
    public function list(): Response {

        $videogames = $this->mediaService->getAllMedias('jeu video');

        return $this->render('back/videogames/list.html.twig', [
            'videogames' => $videogames,
        ]);
    }

    /**
     * @Route("add", name="add", methods={"GET", "POST"})
     */

    public function create(Request $request, CategoryRepository $categoryRepository): Response {

        $media = new Media();

        $categoryId = $this->mediaRepository->getCategoryId('jeu video');
        $category = $categoryRepository->find($categoryId);

        $form = $this->createForm(VideoGameType::class, $media);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createdAt = new DateTimeImmutable();

            $media->setCreatedAt($createdAt);

            $media->setCategory($category);

            $this->em->persist($media);
            $this->em->flush();

            $this->addFlash('success', 'Jeu vidéo ajouté');
            return $this->redirectToRoute('backoffice_videogames_list');
        }
        return $this->renderForm('back/videogames/add.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("{id}/update", name="update", methods={"GET", "POST"})
     */

    public function update(Request $request, $id): Response {

        $videoGame = $this->mediaRepository->find($id);

        $form = $this->createForm(VideoGameType::class, $videoGame);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $updatedAt = new DateTimeImmutable();
            $videoGame->setUpdatedAt($updatedAt);

            $this->em->persist($videoGame);
            $this->em->flush();

            $this->addFlash('success', 'Le jeu vidéo a bien été modifié');
            return $this->redirectToRoute('backoffice_videogames_list');
        }

        return $this->renderForm('back/videogames/update.html.twig', [
            'form' => $form,
            'videoGame' => $videoGame,
        ]);
    }

    /**
     * @Route("{id}/delete", name="delete", methods={"GET", "POST"})
     */

    public function delete($id): Response {

        $videoGame = $this->mediaRepository->find($id);

        $this->mediaRepository->remove($videoGame, true);

        $this->addFlash('success', 'Jeu vidéo supprimé');

        return $this->redirectToRoute('backoffice_videogames_list', [], Response::HTTP_SEE_OTHER);
    }
}

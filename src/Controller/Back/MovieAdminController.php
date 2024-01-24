<?php

namespace App\Controller\Back;

use App\Entity\Media;
use DateTimeImmutable;
use App\Form\MovieType;
use App\Service\MediaService;
use App\Repository\MediaRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backoffice/movies/", name="backoffice_movies_")
 */
class MovieAdminController extends AbstractController {

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

        $movies = $this->mediaService->getAllMedias('film');

        return $this->render('back/movies/list.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * @Route("add", name="add", methods={"GET", "POST"})
     */

    public function add(Request $request, CategoryRepository $categoryRepository): Response {

        $media = new Media();

        $categoryId = $this->mediaRepository->getCategoryId('film');
        $category = $categoryRepository->find($categoryId);

        $form = $this->createForm(MovieType::class, $media);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createdAt = new DateTimeImmutable();

            $media->setCreatedAt($createdAt);

            $media->setCategory($category);

            $this->em->persist($media);
            $this->em->flush();

            $this->addFlash('success', 'Film ajouté');
            return $this->redirectToRoute('backoffice_movies_list');
        }
        return $this->renderForm('back/movies/add.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("{id}/update", name="update", methods={"GET", "POST"})
     */

    public function update(Request $request, $id): Response {

        $movie = $this->mediaRepository->find($id);

        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $updatedAt = new DateTimeImmutable();
            $movie->setUpdatedAt($updatedAt);

            $this->em->persist($movie);
            $this->em->flush();

            $this->addFlash('success', 'Le film a bien été modifié');
            return $this->redirectToRoute('backoffice_movies_list');
        }

        return $this->renderForm('back/movies/update.html.twig', [
            'form' => $form,
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("{id}/delete", name="delete", methods={"GET"})
     */

    public function delete($id): Response {

        $movie = $this->mediaRepository->find($id);

        $this->mediaRepository->remove($movie, true);

        $this->addFlash('success', 'Film supprimé');

        return $this->redirectToRoute('backoffice_movies_list', [], Response::HTTP_SEE_OTHER);
    }
}

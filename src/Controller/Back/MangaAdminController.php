<?php

namespace App\Controller\Back;

use App\Entity\Media;
use App\Form\MangaType;
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
 * @Route("/backoffice/mangas/", name="backoffice_mangas_")
 */
class MangaAdminController extends AbstractController {

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

        $mangas = $this->mediaService->getAllMedias('manga');
        return $this->render('back/mangas/list.html.twig', [
            'mangas' => $mangas,
        ]);
    }

    /**
     * @Route("add", name="add", methods={"GET", "POST"})
     */

    public function add(Request $request, CategoryRepository $categoryRepository): Response {

        $media = new Media();
        $categoryId = $this->mediaRepository->getCategoryId('manga');
        $category = $categoryRepository->find($categoryId);

        $form = $this->createForm(MangaType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createdAt = new DateTimeImmutable();

            $media->setCreatedAt($createdAt);

            $media->setCategory($category);

            $this->em->persist($media);
            $this->em->flush();

            $this->addFlash('success', 'Manga ajouté');
            return $this->redirectToRoute('backoffice_mangas_list');
        }
        return $this->renderForm('back/mangas/add.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("{id}/update", name="update", methods={"GET", "POST"})
     */

    public function update(Request $request, $id): Response {

        $manga = $this->mediaRepository->find($id);

        $form = $this->createForm(MangaType::class, $manga);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $updatedAt = new DateTimeImmutable();
            $manga->setUpdatedAt($updatedAt);

            $this->em->persist($manga);
            $this->em->flush();

            $this->addFlash('success', 'Le manga a bien été modifié');
            return $this->redirectToRoute('backoffice_mangas_list');
        }

        return $this->renderForm('back/mangas/update.html.twig', [
            'form' => $form,
            'manga' => $manga,
        ]);
    }

    /**
     * @Route("{id}/delete", name="delete", methods={"GET"})
     */

    public function delete($id): Response {

        $manga = $this->mediaRepository->find($id);
        $this->mediaRepository->remove($manga, true);
        $this->addFlash('success', 'Manga supprimée');

        return $this->redirectToRoute('backoffice_mangas_list', [], Response::HTTP_SEE_OTHER);
    }
}

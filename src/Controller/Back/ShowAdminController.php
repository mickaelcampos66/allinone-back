<?php

namespace App\Controller\Back;

use App\Entity\Media;
use App\Form\ShowType;
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
 * @Route("/backoffice/shows/", name="backoffice_shows_")
 */
class ShowAdminController extends AbstractController {

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

        $shows = $this->mediaService->getAllMedias('serie');

        return $this->render('back/shows/list.html.twig', [
            'shows' => $shows,
        ]);
    }


    /**
     * @Route("add", name="add", methods={"GET", "POST"})
     */

    public function add(Request $request, CategoryRepository $categoryRepository): Response {

        $media = new Media();

        $categoryId = $this->mediaRepository->getCategoryId('serie');
        $category = $categoryRepository->find($categoryId);

        $form = $this->createForm(ShowType::class, $media);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createdAt = new DateTimeImmutable();

            $media->setCreatedAt($createdAt);

            $media->setCategory($category);

            $this->em->persist($media);
            $this->em->flush();

            $this->addFlash('success', 'Série ajoutée');
            return $this->redirectToRoute('backoffice_shows_list');
        }
        return $this->renderForm('back/shows/add.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("{id}/update", name="update", methods={"GET", "POST"})
     */

    public function update(Request $request, $id): Response {

        $show = $this->mediaRepository->find($id);

        $form = $this->createForm(showType::class, $show);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $updatedAt = new DateTimeImmutable();
            $show->setUpdatedAt($updatedAt);

            $this->em->persist($show);
            $this->em->flush();

            $this->addFlash('success', 'La série a bien été modifiée');
            return $this->redirectToRoute('backoffice_shows_list');
        }

        return $this->renderForm('back/shows/update.html.twig', [
            'form' => $form,
            'show' => $show,
        ]);
    }

    /**
     * @Route("{id}/delete", name="delete", methods={"GET"})
     */

    public function delete($id): Response {

        $show = $this->mediaRepository->find($id);

        $this->mediaRepository->remove($show, true);

        $this->addFlash('success', 'Série supprimée');

        return $this->redirectToRoute('backoffice_shows_list', [], Response::HTTP_SEE_OTHER);
    }
}

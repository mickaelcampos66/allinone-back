<?php

namespace App\Controller\Back;

use App\Entity\Season;
use DateTimeImmutable;
use App\Form\SeasonType;
use App\Repository\SeasonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backoffice/seasons/", name="backoffice_seasons_")
 */
class SeasonAdminController extends AbstractController {


    private $em;

    public function __construct(EntityManagerInterface $em) {

        $this->em = $em;
    }

    /**
     * @Route("list", name="list")
     */
    public function list(SeasonRepository $seasonRepository): Response {

        $seasons = $seasonRepository->findAll();

        return $this->render('back/seasons/list.html.twig', [
            'seasons' => $seasons,
        ]);
    }


    /**
     * @Route("add", name="add", methods={"GET", "POST"})
     */

    public function add(Request $request): Response {

        $season = new Season();

        $form = $this->createForm(SeasonType::class, $season);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createdAt = new DateTimeImmutable();

            $season->setCreatedAt($createdAt);

            $this->em->persist($season);
            $this->em->flush();

            $this->addFlash('success', 'Saison ajoutée');
            return $this->redirectToRoute('backoffice_seasons_list');
        }
        return $this->renderForm('back/seasons/add.html.twig', [
            'form' => $form
        ]);
    }

    /**

     * @Route("{id}/update", name="update", methods={"GET", "POST"})
     */

    public function update(Request $request, $id, SeasonRepository $seasonRepository): Response {

        $season = $seasonRepository->find($id);

        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $updatedAt = new DateTimeImmutable();
            $season->setUpdatedAt($updatedAt);

            $this->em->persist($season);
            $this->em->flush();

            $this->addFlash('success', 'La saison a bien été modifiée');
            return $this->redirectToRoute('backoffice_seasons_list');
        }

        return $this->renderForm('back/seasons/update.html.twig', [
            'form' => $form,
            'season' => $season,
        ]);
    }

    /**
     * @Route("{id}/delete", name="delete", methods={"GET"})
     */

    public function delete($id, SeasonRepository $seasonRepository): Response {

        $season = $seasonRepository->find($id);

        $seasonRepository->remove($season, true);

        $this->addFlash('success', 'Auteur supprimé');

        return $this->redirectToRoute('backoffice_seasons_list', [], Response::HTTP_SEE_OTHER);
    }
}

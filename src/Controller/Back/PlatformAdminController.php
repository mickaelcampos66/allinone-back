<?php

namespace App\Controller\Back;

use App\Entity\Platform;
use DateTimeImmutable;
use App\Form\PlatformType;
use App\Repository\PlatformRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backoffice/platforms/", name="backoffice_platforms_")
 */
class PlatformAdminController extends AbstractController {


    private $em;

    public function __construct(EntityManagerInterface $em) {

        $this->em = $em;
    }

    /**
     * @Route("list", name="list")
     */
    public function list(PlatformRepository $platformRepository): Response {

        $platforms = $platformRepository->findAll();

        return $this->render('back/platforms/list.html.twig', [
            'platforms' => $platforms,
        ]);
    }


    /**
     * @Route("add", name="add", methods={"GET", "POST"})
     */

    public function add(Request $request): Response {

        $platform = new Platform();

        $form = $this->createForm(PlatformType::class, $platform);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createdAt = new DateTimeImmutable();

            $platform->setCreatedAt($createdAt);

            $this->em->persist($platform);
            $this->em->flush();

            $this->addFlash('success', 'Plateforme ajoutée');
            return $this->redirectToRoute('backoffice_platforms_list');
        }
        return $this->renderForm('back/platforms/add.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("{id}/update", name="update", methods={"GET", "POST"})
     */

    public function update(Request $request, $id, PlatformRepository $platformRepository): Response {

        $platform = $platformRepository->find($id);

        $form = $this->createForm(PlatformType::class, $platform);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $updatedAt = new DateTimeImmutable();
            $platform->setUpdatedAt($updatedAt);

            $this->em->persist($platform);
            $this->em->flush();

            $this->addFlash('success', 'La plateforme a bien été modifié');
            return $this->redirectToRoute('backoffice_platforms_list');
        }

        return $this->renderForm('back/platforms/update.html.twig', [
            'form' => $form,
            'platform' => $platform,
        ]);
    }

    /**
     * @Route("{id}/delete", name="delete", methods={"GET"})
     */

    public function delete($id, PlatformRepository $platformRepository): Response {

        $platform = $platformRepository->find($id);

        $platformRepository->remove($platform, true);

        $this->addFlash('success', 'Plateforme supprimée');

        return $this->redirectToRoute('backoffice_platforms_list', [], Response::HTTP_SEE_OTHER);
    }
}

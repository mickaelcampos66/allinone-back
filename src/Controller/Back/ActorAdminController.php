<?php

namespace App\Controller\Back;

use App\Entity\Actor;
use DateTimeImmutable;
use App\Form\ActorType;
use App\Repository\ActorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backoffice/actors/", name="backoffice_actors_")
 */
class ActorAdminController extends AbstractController {


    private $em;

    public function __construct(EntityManagerInterface $em) {

        $this->em = $em;
    }

    /**
     * get all actors
     * @Route("list", name="list")
     */
    public function list(ActorRepository $actorRepository): Response {

        $actors = $actorRepository->findAll();

        return $this->render('back/actors/list.html.twig', [
            'actors' => $actors,
        ]);
    }


    /**
     * get an actor
     * @Route("add", name="add", methods={"GET", "POST"})
     */

    public function add(Request $request): Response {

        $actor = new Actor();

        $form = $this->createForm(ActorType::class, $actor);
        // handle request if POST method
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createdAt = new DateTimeImmutable();
            $actor->setCreatedAt($createdAt);
            $this->em->persist($actor);
            $this->em->flush();

            // add a success message
            $this->addFlash('success', 'Acteur ajouté');
            // redirect to actors list
            return $this->redirectToRoute('backoffice_actors_list');
        }
        // renders form if GET method
        return $this->renderForm('back/actors/add.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * update an actor
     * @Route("{id}/update", name="update", methods={"GET", "POST"})
     */

    public function update(Request $request, $id, ActorRepository $actorRepository): Response {

        $actor = $actorRepository->find($id);

        $form = $this->createForm(ActorType::class, $actor);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $updatedAt = new DateTimeImmutable();
            $actor->setUpdatedAt($updatedAt);

            $this->em->persist($actor);
            $this->em->flush();



            $this->addFlash('success', 'L\'acteur a bien été modifié');
            return $this->redirectToRoute('backoffice_actors_list');
        }

        return $this->renderForm('back/actors/update.html.twig', [
            'form' => $form,
            'actor' => $actor,
        ]);
    }

    /**
     * remove an actor
     * @Route("{id}/delete", name="delete", methods={"GET"})
     */

    public function delete($id, ActorRepository $actorRepository): Response {

        $actor = $actorRepository->find($id);
        $actorRepository->remove($actor, true);
        $this->addFlash('success', 'Acteur supprimée');

        return $this->redirectToRoute('backoffice_actors_list', [], Response::HTTP_SEE_OTHER);
    }
}

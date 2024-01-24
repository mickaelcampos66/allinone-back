<?php

namespace App\Controller\Back;


use DateTimeImmutable;
use App\Entity\Character;
use App\Form\CharacterType;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backoffice/characters/", name="backoffice_characters_")
 */
class CharacterAdminController extends AbstractController {


    private $em;

    public function __construct(EntityManagerInterface $em) {

        $this->em = $em;
    }

    /**
     * @Route("list", name="list")
     */
    public function list(CharacterRepository $characterRepository): Response {

        $characters = $characterRepository->findAll();

        return $this->render('back/characters/list.html.twig', [
            'characters' => $characters,
        ]);
    }


    /**
     * @Route("add", name="add", methods={"GET", "POST"})
     */

    public function add(Request $request): Response {

        $character = new Character();
        $form = $this->createForm(CharacterType::class, $character);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $createdAt = new DateTimeImmutable();

            $character->setCreatedAt($createdAt);

            $this->em->persist($character);
            $this->em->flush();


            $this->addFlash('success', 'Personnage ajouté');
            return $this->redirectToRoute('backoffice_characters_list');
        }
        return $this->renderForm('back/characters/add.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("{id}/update", name="update", methods={"GET", "POST"})
     */

    public function update(Request $request, $id, CharacterRepository $characterRepository): Response {

        $character = $characterRepository->find($id);

        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $updatedAt = new DateTimeImmutable();
            $character->setUpdatedAt($updatedAt);

            $this->em->persist($character);
            $this->em->flush();



            $this->addFlash('success', 'Le personnage a bien été modifié');
            return $this->redirectToRoute('backoffice_characters_list');
        }

        return $this->renderForm('back/characters/update.html.twig', [
            'form' => $form,
            'character' => $character,
        ]);
    }

    /**
     * @Route("{id}/delete", name="delete", methods={"GET"})
     */

    public function delete($id, CharacterRepository $characterRepository): Response {


        $character = $characterRepository->find($id);

        $characterRepository->remove($character, true);

        $this->addFlash('success', 'Personnage supprimée');

        return $this->redirectToRoute('backoffice_characters_list', [], Response::HTTP_SEE_OTHER);
    }
}

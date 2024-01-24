<?php

namespace App\Controller\Back;

use App\Form\SearchType;
use App\Repository\MediaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SearchAdminController extends AbstractController {
    /**
     * @Route("/backoffice/search", name="backoffice_search")
     */
    public function searchBar(): Response {
        $form = $this->createForm(SearchType::class, null, [
            'action' => $this->generateUrl('backoffice_handleSearch'), // route pour gerer la recherche
            'method' => 'POST',
        ]);

        return $this->renderForm('back/searchs/index.html.twig', [
            'form' => $form,
        ]);
    }


    /**
     * @Route("/backoffice/handleSearch", name="backoffice_handleSearch")
     */
    public function handleSearch(Request $request, MediaRepository $mediaRepository): Response {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $keyword = $form->getData();
            // search by keyword
            $results = $mediaRepository->findByKeyword($keyword);

            return $this->render('back/searchs/results.html.twig', [
                'results' => $results,
            ]);
        }

        return $this->redirectToRoute('app_backoffice');
    }
}

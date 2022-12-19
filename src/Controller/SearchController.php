<?php

namespace App\Controller;

use App\Entity\Works;
use App\Form\SearchType;
use App\Repository\WorksRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search2', name: 'app_search2')]
    public function index(Request $request, ManagerRegistry $doctrine)
    {
        $title = $request->request->all('search')['query'];
        //dd($request->request->all('search'));

        $whereElement = '%'.$title.'%';
        $requete = $doctrine->getRepository(Works::class)->createQueryBuilder('w')
            ->where('w.title LIKE :title')
            ->setParameter('title', $whereElement)
            ->getQuery()
            ->getResult();
        $response = $requete[0]->getTitle();

        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
            'response' => $response
        ]);
    }

    // Décaler la fonction sur la page Home
    #[Route('/search', name: 'app_search')]
    public function searchBar(Request $request): Response
    {
        $form = $this->createForm(SearchType::class, [
            'action' => $this->generateUrl('app_search2')
        ]);
        //dd($form);

        return $this->renderForm('search/searchBar.html.twig', [
            'form' => $form,
        ]);
    }
}

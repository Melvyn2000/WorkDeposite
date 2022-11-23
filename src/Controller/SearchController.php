<?php

namespace App\Controller;

use App\Entity\Works;
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
    #[Route('/search', name: 'app_search')]
    public function index(Request $request, ManagerRegistry $doctrine)
    {
        $query = $request->request->all('form')['query'];

        $title = 'Im';
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

    #[Route('/search2', name: 'app_search2')]
    public function searchBar(): Response
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('app_search'))
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez un mot-clé'
                ]
            ])
            ->add('recherche', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm();
        return $this->render('search/searchBar.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

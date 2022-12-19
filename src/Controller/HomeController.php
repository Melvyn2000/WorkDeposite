<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Works;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()) {
            $categories = $this->getCategories($doctrine);
            $works = $this->getWorks($doctrine);
            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
                'categories' => $categories,
                'works' => $works,
            ]);
        }
        return $this->redirectToRoute('app_login');
    }

    /**
     * Return all data of table "category".
     *
     * @param ManagerRegistry $doctrine
     * @return array
     */
    public function getCategories(ManagerRegistry $doctrine): array
    {
        return $doctrine->getRepository(Categorie::class)->findAll();
    }

    /**
     * return all data of table "works".
     *
     * @param ManagerRegistry $doctrine
     * @return array
     *
     */
    public function getWorks(ManagerRegistry $doctrine): array
    {
        return $doctrine->getRepository(Works::class)->findAll();
    }
}

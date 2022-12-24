<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Works;
use App\Entity\WorksLikes;
use App\Repository\WorksLikesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
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

    /**
     * Allow like or dislike Works.
     *
     * @param $id
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/json/{id_post}', name: 'app_json')]
    public function likes($id_post, ManagerRegistry $doctrine): Response
    {
        //Get ID user
        $userId = $this->getUser()->getId();

        //Get Works liked by the users
        $worksLikedByUser = $doctrine->getRepository(WorksLikes::class)->getWorksLikedByUser($userId, $id_post);

        //Start creation of Manager entity
        $entityManager = $doctrine->getManager();

        $entityWork = $doctrine->getRepository(Works::class)->findBy(['id' => $id_post]);
        //dd($worksLikedByUser);
        if (empty($entityWork)) {
            dd('Works n\'existe pas ou la requête renvoie null');
        } else {
            if (!empty($worksLikedByUser)) {
                // Delete Works Liked in WorksLikes table in database
                $entityManager->remove($worksLikedByUser[0]);
                $entityManager->flush();
                return $this->json('Travail disliké !', 200);
            } else {
                // Add new Works Liked in WorksLikes table in database
                $like = new WorksLikes();
                $like->setLikes($entityWork[0]);
                $like->setUser($this->getUser());
                $entityManager->persist($like);
                $entityManager->flush();
                return $this->json('Travail liké !', 200);
            }
        }
    }

}

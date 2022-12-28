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
use Doctrine\ORM\Query\ResultSetMapping;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()) {
            $categories = $this->getCategories($doctrine);
            $works = $this->getWorks($doctrine);
            $worksMostLikedByUser = $this->worksMostLiked($doctrine);
            //dd($worksMostLikedByUser);
            return $this->render('home/index.html.twig', [
                'controller_name' => 'HomeController',
                'categories' => $categories,
                'works' => $works,
                'worksMostLiked' => $worksMostLikedByUser
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
    #[Route('/json/{id_work}', name: 'json_like')]
    public function likes($id_work, ManagerRegistry $doctrine): Response
    {
        //Get ID user
        $userId = $this->getUser()->getId();

        //Get Works liked by the users
        $worksLikedByUser = $doctrine->getRepository(WorksLikes::class)->getWorksLikedByUser($userId, $id_work);

        //Start creation of Manager entity
        $entityManager = $doctrine->getManager();

        $entityWork = $doctrine->getRepository(Works::class)->findBy(['id' => $id_work]);
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

    public function worksMostLiked(ManagerRegistry $doctrine): array
    {
        /*
         * Impossible d'exécuter avec la fonction createQueryBuilder(), car elle ne retoune que les données brutes, pas les objets.
         * SELECT likes_id, COUNT(likes_id) from works_likes GROUP BY likes_id ORDER BY COUNT(likes_id) DESC
         */
        $conn = $doctrine->getConnection();
        $sql = 'SELECT likes_id, COUNT(likes_id) from works_likes GROUP BY likes_id ORDER BY COUNT(likes_id) DESC';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $resultWorksLiked = $resultSet->fetchAll();
        // returns an array of arrays (i.e. a raw data set)
        //dd($resultSet->fetchAll());

        //Create an array to recovers entity works must liked by users
        $worksMostLikedArray = [];
        foreach ($resultWorksLiked as $key => $value) {
            $worksMostLikedArray[] = $doctrine->getRepository(Works::class)->find($value['likes_id']);
        }
        //dd($worksMostLikedArray);
        return $worksMostLikedArray;
    }
}

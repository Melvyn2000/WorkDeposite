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
            $worksMostLikedByUser = $this->WorksMostLiked($doctrine);
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

    public function WorksMostLiked(ManagerRegistry $doctrine): array
    {
        //Recovers all workLikes data
        $worksLikes = $doctrine->getRepository(WorksLikes::class)->findAll();
        //Create array workArray
        $workArray = [];
        //Add in this array, the ID of works liked by the users
        foreach ($worksLikes as $key => $value) {
            $workArray[$key] = $value->getLikes()->getId();
        }
        //Counts all the values of an array workArray
        $workASC = array_count_values($workArray);
        //Sorts the values in descending order
        arsort($workASC);
        //Create an array to recovers entity works must liked by users
        $worksMostLikedArray = [];
        foreach ($workASC as $key => $value) {
            $worksMostLikedArray[] = array_values($doctrine->getRepository(Works::class)->findBy(['id'=>$key]));
        }
        //dd($worksMostLikedArray);
        return $worksMostLikedArray;
    }
}

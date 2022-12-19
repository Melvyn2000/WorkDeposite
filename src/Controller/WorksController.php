<?php

namespace App\Controller;

use App\Entity\Works;
use App\Form\WorksType;
use App\Repository\WorksRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class WorksController extends AbstractController
{
    #[Route('/', name: 'app_works_index', methods: ['GET'])]
    public function index(WorksRepository $worksRepository): Response
    {
        return $this->render('works/index.html.twig', [
            'works' => $worksRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_works_new', methods: ['GET', 'POST'])]
    public function new(Request $request, WorksRepository $worksRepository): Response
    {
        $work = new Works();
        $work->setUser($this->getUser());
        $form = $this->createForm(WorksType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $filesOrlinks */
            $filesOrlinks = $form->get('filesOrlinks')->getData();
            //dd($filesOrlinks);
            if ($filesOrlinks) {
                $originalFilename = pathinfo($filesOrlinks->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'.'.$filesOrlinks->guessExtension();
                //dd($newFilename);

                try {
                    $filesOrlinks->move($this->getParameter('upload_directory'), $newFilename);
                } catch (FileException $e) {
                    dd($e);
                    // ... handle exception if something happens during file upload
                }
                $work->setFilesOrlinks($newFilename);
            }

            $worksRepository->save($work, true);

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('works/new.html.twig', [
            'work' => $work,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_works_show', methods: ['GET'])]
    public function show(Works $work): Response
    {
        return $this->render('works/show.html.twig', [
            'work' => $work,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_works_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Works $work, WorksRepository $worksRepository): Response
    {
        $form = $this->createForm(WorksType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $worksRepository->save($work, true);

            return $this->redirectToRoute('app_works_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('works/edit.html.twig', [
            'work' => $work,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_works_delete', methods: ['POST'])]
    public function delete(Request $request, Works $work, WorksRepository $worksRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$work->getId(), $request->request->get('_token'))) {
            $worksRepository->remove($work, true);
        }

        return $this->redirectToRoute('app_works_index', [], Response::HTTP_SEE_OTHER);
    }
}

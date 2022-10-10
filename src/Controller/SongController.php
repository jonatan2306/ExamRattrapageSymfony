<?php

namespace App\Controller;

use App\Entity\Song;
use App\Form\SongFilterType;
use App\Form\SongType;
use App\Repository\SongRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SongController extends AbstractController
{
    #[Route('/song', name: 'app_song')]
    public function index(Request $request, SongRepository $songRepository): Response
    {
        $search = $this->createForm(SongFilterType::class);
        $search->handleRequest($request);

        if($search->isSubmitted() && $search->isValid()) {
            $searchName = $search->get('songName')->getData();
            $songs = $songRepository->searchSong($searchName);
        } else {
            $songs = $songRepository->findAll();
        }

        return $this->render('song/index.html.twig', [
            'songs' => $songs,
            'searchTitle' => $search->createView(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/song/new', name: 'app_new_song', methods: ['POST', 'GET'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $song = new Song();
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($song);
            $entityManager->flush();

            $this->addFlash('success', 'La musique ' . $song->getName() . ' a bien été ajoutée ! ');
            return $this->redirectToRoute('app_song', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('song/new.html.twig', [
            'form' => $form,
            'song' => $song,
        ]);
    }

    #[Route('/song/{id}', name: 'app_detail_song')]
    public function detail(Song $song): Response
    {
        return $this->render('song/details.html.twig', [
            'song' => $song,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/song/{id}/edit', name: 'app_edit_song')]
    public function edit(Request $request, Song $song, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_song', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('song/edit.html.twig', [
            'song' => $song,
            'form' =>$form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/song/{id}/delete', name: 'app_delete_song', methods: ['POST'])]
    public function delete(Request $request, Song $song, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$song->getId(), $request->request->get('_token'))) {
            $entityManager->remove($song);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_song', [], Response::HTTP_SEE_OTHER);
    }
}

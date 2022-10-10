<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\SongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home') ]
    public function index(SongRepository $songRepository, AlbumRepository $albumRepository): Response
    {
        $songs = $songRepository->findAll();
        $albums = $albumRepository->findAll();
        // j'appelle la function findLongestSong() qui se trouve dans SongRepository qui vas permttre de selectionner la musique la plus longue dans l'album
        $topDurationSong = $songRepository->findLongestSong();
        return $this->render('home/index.html.twig', [
            'songs' => $songs,
            'topDuration' => $topDurationSong,
            'albums' => $albums
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\JeuxRepository;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(JeuxRepository $JeuxRepository): Response
    {
        $lastJeux=$JeuxRepository->findLastJeux(2);
        //dd($lastJeux);
        return $this->render('accueil/index.html.twig', [
            'lastJeux' => $lastJeux,
        ]);
    }
}

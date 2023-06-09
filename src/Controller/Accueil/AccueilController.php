<?php

namespace App\Controller\Accueil;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\JeuxRepository;
use App\Entity\Jeux;



class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'accueil')]
    public function index(JeuxRepository $JeuxRepository): Response
    {
        $lastJeux=$JeuxRepository->findLastJeux(10);
        //dd($lastJeux);
        return $this->render('accueil/index.html.twig', [
            'lastJeux' => $lastJeux,
        ]);
    }

    #[Route('/accueil/{slug}', name: 'details')]
    public function details(Jeux $Jeux): Response
    {
        //$title=$Jeux->getTitle();
        //dd($title);
        return $this->render('accueil/details.html.twig', [
            'jeux' => $Jeux,
        ]);
    }
}

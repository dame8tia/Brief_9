<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\JeuxRepository;
use App\Entity\Jeux;

#[Route('/jeux', name: 'jeux_')]
class JeuxController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(JeuxRepository $JeuxRepository): Response
    {
        $lastJeux=$JeuxRepository->findLastJeux(10);
        //dd($lastJeux);
        return $this->render('jeux/index.html.twig', [
            'lastJeux' => $lastJeux,
        ]);
    }

    #[Route('/{slug}', name: 'details')]
    public function details(Jeux $Jeux): Response
    {
        //$title=$Jeux->getTitle();
        //dd($title);
        return $this->render('jeux/details.html.twig', [
            'jeux' => $Jeux,
        ]);
    }
}

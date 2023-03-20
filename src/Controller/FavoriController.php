<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\JeuxRepository;

class FavoriController extends AbstractController
{
    #[Route('/favori', name: 'app_favori')]
    public function index(): Response
    {
        return $this->render('favori/index.html.twig', [
            'controller_name' => 'FavoriController - lsite des jeux favoris',
        ]);
    }

    #[Route('/favori/{slug}', name: 'add_favori')]
    public function add($slug, SessionInterface $session, jeuxRepository $jeuxRepository): Response
    {   
        $favoris = [];   
        $favoris = $session->get('favori',[]);// On récupère le panier ou un tableau vide
        //$session->set('favori', $slug);
        
        if (!empty($favoris)){
            array_push($favoris, $slug);
            $session->set('favori', $favoris);
        }
        else {$session->set('favori', [$slug]);}
        dd($session);
        dd($favoris);

        $jeuRepo = $jeuxRepository->findOneBy(['slug'=>$slug]);
        //dd($jeuRepo);
        return $this->render('favori/add.html.twig', [
            'session' => $session,
            'jeuRepo' => $jeuRepo
        ]);
    }
}

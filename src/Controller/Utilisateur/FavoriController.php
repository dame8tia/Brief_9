<?php

namespace App\Controller\Utilisateur;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Repository\JeuxRepository;
use App\Entity\Jeux;

class FavoriController extends AbstractController
{
    #[Route('/favori', name: 'favori')]
    #[IsGranted('ROLE_USER')]
    public function index(SessionInterface $session, JeuxRepository $jeuxRepository): Response
    {
        $panier = $session->get("favori", []);
        //dd($panier);
        $dataPanier = [];

        foreach($panier as $slug){
            $jeu = $jeuxRepository->findBy(['slug' => $slug]);
            //dd($jeu);
            $dataPanier[] = [ // Equivaut au array_push
                "jeu" => $jeu,
            ];
        }
        return $this->render('favori/index.html.twig',compact("dataPanier") );// compact a un intérêt si plus d'un paramètre
    }

    #[Route('/favori/{slug}', name: 'add_favori')]
    #[IsGranted('ROLE_USER')]
    public function add($slug, SessionInterface $session, Jeux $jeu): Response
    {
        $slug = $jeu->getSlug();// permet de vérifier que le slug existe bien. On pourrait s'en passer
        $favoris = [];   
        $favoris = $session->get('favori',[]);// On récupère le panier ou un tableau vide
        //$session->set('favori', $slug);
        if (!empty($favoris)){
            if (in_array($slug, $favoris)) {
                // rien
            }
            else {
                array_push($favoris, $slug);
                $session->set('favori', $favoris);
            }
        }
        else {
            $session->set('favori', [$slug]);
        }

        return $this->redirectToRoute('favori');
    }

    #[Route('/favori/delete/{slug}', name: 'delete_favori')]
    #[IsGranted('ROLE_USER')]
    public function delete($slug, SessionInterface $session, Jeux $jeu)
    {
        // On récupère le panier actuel
        $favoris = $session->get("favori", []);
        
        foreach ($favoris as $key => $value){
            //dd($value, $key);
            if ($value == $slug){
                unset($favoris[$key]);
            }
        }

        // On sauvegarde dans la session
        $session->set("favori", $favoris);

        return $this->redirectToRoute("favori");
    }
}

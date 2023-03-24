<?php

namespace App\Controller\Utilisateur;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AvisRepository;
use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\JeuxRepository;
use App\Repository\UtilisateursRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\User\UserInterface;
//use Doctrine\ORM\Mapping\Id;
use Knp\Component\Pager\PaginatorInterface;

class AvisUtilController extends AbstractController
{
    #[Route('/avis/{slug}', name: 'avis.tous', methods:['GET'])]
    public function index_tous(
        AvisRepository $avisRepository
        , PaginatorInterface $paginator
        , JeuxRepository $jeuRepository
        , Request $request
        , $slug
        ): Response
    {    

        $jeu = $jeuRepository->findOneBy(['slug' => $slug]);
        $titleJeu = $jeu->getTitle();
        $mesAvis = $avisRepository->findBy(['jeu'=>$jeu]);

        //calcul de la moyenne
        $count = 0;
        $sum = 0;
        $moy = 0 ;
        foreach ($mesAvis as $key => $value){
            //dd($value, $key);
            $note = $value->getNote();
            $sum = $sum + $note;
            $count +=1 ;

            $moy = $sum/$count ;
        }

        $avis = $paginator->paginate(
            $mesAvis, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('avis/index.html.twig', 
            [
                'avis'=>$avis,
                'avisJeu' =>$titleJeu,
                'moy'=> $moy                
            ]);
    }

    /**
     * @param AvisRepository $avisRepository
     * @param PaginatorInterface $paginator
     * @param UtilisateursRepository $utilRepo
     * @param UserInterface $user
     * @param Request $request
     * @return Response
     * 
     * This function displays all video play
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/avis', name: 'avis', methods:['GET'])]
    public function index(
        AvisRepository $avisRepository
        , UtilisateursRepository $utilRepo
        , PaginatorInterface $paginator
        , Request $request
        , UserInterface $user
        ): Response
    {    

        // On récupère le pseudo de l'utilisateur connecté
        $idUserConnected = $user->getUserIdentifier(); // le mail
        $userEntity = $utilRepo->findOneBy(['email' => $idUserConnected]);
        

        $user = $userEntity->getId();
        
        $tousMesAvis = $avisRepository->findBy(['utilisateur'=>$user]);
        //dd($tousMesAvis);

        $avis = $paginator->paginate(
            $tousMesAvis, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('avis/index.html.twig', 
            [
                'avis'=>$avis,
                'AvisJeu' =>""
            ]);
    }

    /**
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param JeuxRepository $jeuRepository
     * @param UtilisateursRepository $utilRepo
     * @param UserInterface $user
     * @return Response
     * 
     * This function to insert a new notification about video play
     */

    #[IsGranted('ROLE_USER')]
    #[Route('/avis/nouveau/{slug}', name: 'avis.new', methods:['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
        JeuxRepository $jeuRepository,
        UtilisateursRepository $utilRepo,
        AvisRepository $AvisRepository,
        UserInterface $user,
        $slug, 
        ): Response
    {
        $avis = new Avis;

        // on récupère le jeu sur lequel un avis est donnée
        $jeu = $jeuRepository->findOneBy(['slug' => $slug]);

        // Récupération des avis existants
        $tousAvis = $AvisRepository->findAll();

        // On récupère le pseudo de l'utilisateur connecté
        $idUserConnected = $user->getUserIdentifier(); // le mail
        $userEntity = $utilRepo->findOneBy(['email' => $idUserConnected]);
        $pseudo = $userEntity->getPseudo();

        $existant = false;
        // On vérifie si un avis à déjà été déposé par cet utilisateur
        foreach ($tousAvis as $key => $value){
            $pseudoAvisExistant = $value->getUtilisateur()->getPseudo();            
            if ($pseudoAvisExistant == $pseudo)
            {
                $jeuAvisExistant = $value->getJeu()->getTitle();
                $jeuAvisEnCours = $jeu->getTitle();
                if( $jeuAvisExistant == $jeuAvisEnCours){
                    $existant = true;
                    $this->addFlash(
                        'fail',
                        'Vous avez déjà donné votre avis sur ce jeu. Vous pouvez le modifier'
                    );
                    return $this->redirectToRoute('avis');
                }
                else { continue ;}                
            }
            else {continue ;}
        }

        // on envoie les mes informations connues, l'utilisateur, 
        $avis->setJeu($jeu);
        $avis->setUtilisateur($user);
        $avis->setIs_Valid(1);
        // Rajouter une contrainte d'unicité sur user/Jeu : Fait depuis Avis.php
    
        $form = $this->createForm(AvisType:: class, $avis);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
            $avis = $form->getData();

            $manager->persist($avis);
            $manager->flush();

/*             $this->addFlash(
                'success',
                'L\'avis a bien été ajouté'
            ); */

            return $this->redirectToRoute('avis');

        }

        $titleJeu = $jeu->getTitle();
        $slug = $jeu->getSlug();

        return $this->render('avis/new.html.twig',
            [
                'form' => $form->createView(),
                'titleJeu' => $titleJeu,
                'slug'=>$slug,
                'existant' => $existant
            ]
        );
    }

    /**
     * 
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * 
     * This function to update a video play
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/avis/edition/{id}', name: 'avis.edit', methods:['GET', 'POST'])]
    public function edit(
        Avis $avis,
        Request $request,
        EntityManagerInterface $manager, 
        jeuxRepository $jeuxRepository,
        ): Response 
    {
        $form = $this->createForm(AvisType:: class, $avis);
        $form->handleRequest($request);
        $JeuAvis = $avis->getJeu();

        $jeu = $jeuxRepository->findOneBy(['id' => $JeuAvis]);
        $titleJeu = $jeu->getTitle();

        if ($form->isSubmitted() && $form->isValid()){

            $avis = $form->getData();
            
            $manager->persist($avis);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'avis a bien été modifié'
            );

            return $this->redirectToRoute('avis');
        }
        else {
            // A faire
        }

        return $this->render('avis/update.html.twig',
            [
                'form' => $form->createView(),
                'title' => $titleJeu
            ]
        );
    }


    /**
     * 
     * @param EntityManagerInterface $manager
     * @return Response
     * 
     * This function to update a video play
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/avis/suppression/{id}', name: 'avis.delete', methods:['GET'])]
    public function delete(
        Avis $avis,
        EntityManagerInterface $manager):Response
    {
        $manager->remove($avis);
        $manager->flush();

        $this->addFlash(
            'success',
            'L\'avis a bien été supprimé'
        );
        return $this->redirectToRoute('avis');
    }
    
}

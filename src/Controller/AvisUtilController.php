<?php

namespace App\Controller;

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
//use Doctrine\ORM\Mapping\Id;
use Knp\Component\Pager\PaginatorInterface;

class AvisUtilController extends AbstractController
{
    
    /**
     * @param AvisRepository $avisRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * 
     * This function displays all video play
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/avis', name: 'avis', methods:['GET'])]
    public function index(
        AvisRepository $avisRepository
        , PaginatorInterface $paginator
        , Request $request
        ): Response
    {    
        $avis = $paginator->paginate(
            $avisRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('avis/index.html.twig', 
            [
                'avis'=>$avis,
            ]);
    }

    /**
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param JeuxRepository $jeuRepository
     * @param UtilisateursRepository $utilRepo
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
        $slug, 
        ): Response
    {
        $avis = new Avis;
        
        $jeu = $jeuRepository->findOneBy(['slug' => $slug]);
        $avis->setJeu($jeu);
        $user = $utilRepo->findOneBy(['pseudo' => 'Utilisateur']);// Récupérer la variable app.user
        $avis->setUtilisateur($user);
        $avis->setIs_Valid(1);
        // Rajouter une contrainte d'unicité sur user/Jeu.
    
        // On récupére le slug et l'utilisateur avant de créer le formulaire.
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
        else {
            //
        }

        $titleJeu = $jeu->getTitle();

        return $this->render('avis/new.html.twig',
            [
                'form' => $form->createView(),
                'titleJeu' => $titleJeu
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

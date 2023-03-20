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
    #[Route('/avis', name: 'avis', methods:['GET'])]
    public function index(AvisRepository $avisRepository, PaginatorInterface $paginator, Request $request): Response
    {    
        $avis = $paginator->paginate(
            $avisRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        //dd($avis);

        return $this->render('avis/index.html.twig', 
            [
                'avis'=>$avis,
            ]);
    }

    /**
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     * 
     * This function to insert a new video play
     */
    #[Route('/avis/nouveau/{slug}', name: 'avis.new', methods:['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
        JeuxRepository $jeuRepository,
        $slug, 
        ): Response
    {
        $avis = new Avis;
        $jeu = $jeuRepository->findOneBy(['slug' => $slug]);
        $form = $this->createForm(AvisType:: class, $avis);
        $form->handleRequest($request);
        //dd($form);
        if ($form->isSubmitted() && $form->isValid()){
            $avis = $form->getData();
            $avis->setJeu($jeu);
            $avis->setUtilisateur('utilisateur');
            //$manager->persist($jeu);

            $manager->persist($avis);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'avis a bien été ajouté'
            );

            return $this->redirectToRoute('avis');

        }
        else {
            //
        }

        return $this->render('avis/new.html.twig',
            [
                'form' => $form->createView(),
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
    #[Route('/avis/edition/{id}', name: 'avis.edit', methods:['GET', 'POST'])]
    public function edit(
        Avis $avis,
        Request $request,
        EntityManagerInterface $manager
        ): Response 
    {

        $form = $this->createForm(AvisType:: class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            //dd($form->getData());
            $jeu = $form->getData();

            $manager->persist($jeu);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'avis a bien été modifié'
            );

            return $this->redirectToRoute('console');
        }
        else {
            // A faire
        }

        return $this->render('avis/update.html.twig',
            [
                'form' => $form->createView(),
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

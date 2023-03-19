<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\JeuxRepository;
use App\Entity\Jeux;
use App\Form\CrudType;
use Knp\Component\Pager\PaginatorInterface;

class CrudController extends AbstractController
{
    /**
     * @param JeuxRepository $jeuxRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * 
     * This function displays all video play
     */
    #[Route('/console', name: 'console', methods:['GET'])]
    public function index(JeuxRepository $jeuxRepository, PaginatorInterface $paginator, Request $request): Response
    {    
        $jeux = $paginator->paginate(
            $jeuxRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        //dd($jeux);

        return $this->render('crud/index.html.twig', 
            [
                'jeux'=>$jeux,
            ]);
    }

    /**
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     * 
     * This function to insert a new video play
     */
    #[Route('/console/nouveau', name: 'console.new', methods:['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
        ): Response
    {
        $jeu = new Jeux;
        $form = $this->createForm(CrudType:: class, $jeu);
        $form->handleRequest($request);

        //dd($form->getData());
        
        if ($form->isSubmitted() && $form->isValid()){
            //dd($form->getData());
            $jeu = $form->getData();

            $manager->persist($jeu);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le jeu a bien été ajouté'
            );

            return $this->redirectToRoute('console');

        }
        else {
            //
        }

        return $this->render('crud/new.html.twig',
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
    #[Route('/console/edition/{slug}', name: 'console.edit', methods:['GET', 'POST'])]
    public function edit(
        //JeuxRepository $jeuxRepository, // on utilise paramConverter
        Jeux $jeu,//string $slug,
        Request $request,
        EntityManagerInterface $manager
        ): Response 
    {
        
        //$jeu = $jeuxRepository->findOneBy(['slug' => $slug])

        $form = $this->createForm(CrudType:: class, $jeu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            //dd($form->getData());
            $jeu = $form->getData();

            $manager->persist($jeu);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le jeu a bien été modifié'
            );

            return $this->redirectToRoute('console');
        }
        else {
            // A faire
        }

        return $this->render('crud/update.html.twig',
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
    #[Route('/console/suppression/{slug}', name: 'console.delete', methods:['GET'])]
    public function delete(
        Jeux $jeu,
        EntityManagerInterface $manager):Response
    {
        $manager->remove($jeu);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le jeu a bien été supprimé'
        );

        return $this->redirectToRoute('console');
    }
    
}

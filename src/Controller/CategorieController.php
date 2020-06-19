<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;



/**
 * @Route("/categorie")
 */
class CategorieController extends AbstractController
{    
    /**
     * @Route("/", name="categorie_index", methods={"GET", "POST"})
     */
    public function index(CategorieRepository $categorieRepository): Response
    {
        // $id = $this->getUser()->getId();
        // $scores = $this->getDoctrine()->getRepository(\App\Entity\Score::class)->findBy(['id_user' => $id]) ;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
            // 'scores' => $scores,
        ]);
    }

    /**
     * @Route("/user/cat/new", name="categorie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('question_new');
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/cat/{id}", name="categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie, Request $request): Response
    {   
        
        $reponses = $this->getDoctrine()->getRepository(\App\Entity\Reponse::class)->findAll();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        if(!empty($request->query->get('id')))
        {
        $prop = $request->query->get('id');
        $id_question = $request->query->get('id_question');
        $session =$this->get('session');
        $session->set($id_question, $id_question); 
        }
        else
        {
            $prop='';
            $session='';
        } 
        
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
            'reponses' => $reponses,
            'prop' => $prop,
            'session' => $session
        ]);
    }

    /**
     * @Route("/admin/cat/{id}/edit", name="categorie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Categorie $categorie): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/cat/{id}", name="categorie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Categorie $categorie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_index');
    }
}

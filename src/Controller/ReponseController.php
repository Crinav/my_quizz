<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Entity\Score;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/reponse")
 */
class ReponseController extends AbstractController
{
    
    /**
     * @Route("/", name="reponse_index", methods={"GET"})
     */
    public function index(ReponseRepository $reponseRepository, Request $request): Response
    {
        $score =0;
        $tabId_questions = $request->query->get('id_question');
        $tabId_reponses=[];
        $reponses =[];
        foreach($tabId_questions as $val)
        { 
          $tabId_reponses[$val] = $request->query->get($val);
          $reponses[$val]=$reponseRepository->findByIdQuestion([$val]);
          if($reponses[$val])
          {
              
              foreach($reponses[$val] as $v)
              {
                  $id_cat = $v->getQuestion()->getCategorie()->getId();
                  if($v->getReponse_expected() == 1)
                  {
                      if($tabId_reponses[$val] == $v->getId())
                      
                      $score++;
                      
                  }
                  
              }
              
          }
        }
        $session =$this->get('session');
        $session->set('score'.$id_cat, $score);
        $id_user = $this->getUser()->getId();
        $bddscore = new Score();
        $bddscore->setIdUser($id_user);
        $bddscore->setIdCategorie($id_cat);
        $bddscore->setScore($score);
        $date = new \DateTime();
        $bddscore->setDate($date);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($bddscore);
        $entityManager->flush();
        return $this->render('reponse/index.html.twig', [
            'reponses' => $reponses,
            'tabId_questions' => $tabId_questions,
            'tabId_reponses' => $tabId_reponses
        ]);
    }

    /**
     * @Route("/user/new/reponse", name="reponse_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $nb = $request->query->get('nb');
        if(!isset($nb))
        {
            static $nb = 1;
            $session = $this->get('session');
            $session->set('nb',$nb);
        }
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reponse);
            $entityManager->flush();
            $nb++;
            $session =$this->get('session');
            $session->set('nb',$nb);
            if($nb == 4)
            {
                return $this->redirectToRoute('question_new');
            }
           
            
        }

        return $this->render('reponse/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
            'nb' => $nb,
        ]);
    }

    /**
     * @Route("/admin/{id}", name="reponse_show", methods={"GET"})
     */
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    /**
     * @Route("/admin/{id}/edit", name="reponse_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reponse $reponse): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reponse_index');
        }

        return $this->render('reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}", name="reponse_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Reponse $reponse): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reponse_index');
    }
}

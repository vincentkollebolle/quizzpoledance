<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Player; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class QuizzController extends AbstractController
{
    /**
     * @Route("/", name="quizz")
     */
    public function index(Request $request)
    {
        $player = new Player();
        
        $repository = $this->getDoctrine()->getRepository(Player::class);
        $players = $repository->findAll();

        $form = $this->createFormBuilder($player)
            ->add(
                'email', 
                EmailType::class,
                [
                    'help' => 'Donnez votre e-mail et soyez informé.e lors de la sortie du quizz !',
                ])
            ->add('save', SubmitType::class, ['label' => 'Prevenez-moi !'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
                // $form->getData() holds the submitted values
                // but, the original `$task` variable has also been updated
                $player = $form->getData();
               
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($player);
                $entityManager->flush();
                

                //usage du flashbag ;) 
                $this->addFlash(
                    'success',
                    'Vous serez notifié.e des que le quizz est en ligne !'
                );
                

                return $this->redirectToRoute('quizz');
        }

        return $this->render('quizz/index.html.twig', [
            'form' => $form->createView(),
            'players' => $players
        ]);
    }
}

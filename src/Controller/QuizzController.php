<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Player; 
use App\Entity\Quizz; 
use App\Entity\Question; 
use App\Entity\Answer; 
use App\Entity\Playeranswer;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use App\Repository\QuestionRepository;
use App\Repository\AnswerRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class QuizzController extends AbstractController
{
    /**
     * @Route("/", name="home")
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

    /**
     * @Route("/quizz", name="quizz")
     */
    public function quizz(Request $request)
    {
        //form start quizz
        $quizz = new Quizz();
        $quizz->setScore(0);

        $form = $this->createFormBuilder($quizz)
            ->add('playername', TextType::class, ['label' => 'Pseudonyme'])
            ->add('start', SubmitType::class, ['label' => 'Démarrer le Quizz'])
            ->getForm();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $quizz = $form->getData();
            $quizz->setDate(new DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quizz);
            $entityManager->flush();
    
            return $this->redirectToRoute('quizz_show',array('id' => $quizz->getId()));
        }
        
        return $this->render(
            'quizz/quizz.html.twig', 
            [ 
                'form' => $form->createView()
            ]);
    }

      /**
     * @Route("/quizz/{quizz_id}/validate/{question_id}/{answer_id}", name="validate", methods={"GET"})
     * @Entity("quizz", expr="repository.find(quizz_id)")
     * @Entity("question", expr="repository.find(question_id)")
     * @Entity("answer", expr="repository.find(answer_id)")
     */
    public function validate(
        Quizz $quizz,
        Question $question,
        Answer $answer): Response
    {
       
        $playeranswer = new Playeranswer(); 
        $playeranswer->setQuizz($quizz);
        $playeranswer->setQuestion($question);

        //on compare playerAnswer et goodanswer
        if($question->getGoodanswer() === $answer) {
            $playeranswer->setStatus("yes");
            // Enregistrer le score
            $quizz->setScore($quizz->getScore()+1);
            //usage du flashbag ;) 
            $this->addFlash(
                'success',
                'Trop fort.e !'
            );
        } else {
            $playeranswer->setStatus("no");
            $this->addFlash(
                'error',
                "ha bah non c'est pas ça !"
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($playeranswer);
        $entityManager->flush();
        
        //put message in the session; 

        return $this->redirectToRoute('quizz_show',array('id' => $quizz->getId()));
    }

   // ISSUE #18: Historique des Quizzs passés
    /**
     * @Route("/quizz/history", name="quizz_history")
     */
    public function history(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('playername', TextType::class)
            ->add('submit', SubmitType::class, ['label' => 'Chercher Player'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('quizz_userhistory',['playername' => $form['playername']->getData()]);
        }
        $quizzRepository = $this->getDoctrine()->getRepository(Quizz::class);
        return $this->render('quizz/history.html.twig', [
            'quizzs' => $quizzRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }
    // ISSUE #18: Historique des Quizzs passés par utilisateur
    /**
     * @Route("/quizz/history/{playername}", name="quizz_userhistory")
     */
    public function userHistory(string $playername, Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('playername', TextType::class)
            ->add('submit', SubmitType::class, ['label' => 'Chercher Player'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('quizz_userhistory',['playername' => $form['playername']->getData()]);
        }
        $quizzRepository = $this->getDoctrine()->getRepository(Quizz::class);
        return $this->render('quizz/history.html.twig', [
            'quizzs' => $quizzRepository->findBy(['playername' => $playername]),
            'playername' => $playername,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/quizz/{id}", name="quizz_show")
     */
    public function showquizz(Request $request, Quizz $quizz)
    {
        //get unanswered questions
        //toutes les questions qui ne sont pas dans l'history du player
        $repository = $this->getDoctrine()->getRepository(Question::class);
        $questions = $repository->findAll();
        
        $repository = $this->getDoctrine()->getRepository(Playeranswer::class);
        $playeranswers = $repository->findByQuizz($quizz);
        
        //on construit un tableau d'id des question auxquelles il a déjà été répondu.
        $arrayAnsweredQuestion = [];
        foreach($playeranswers as $playeranswer ) {
            $arrayAnsweredQuestion[] =  $playeranswer->getQuestion()->getId();        
        }

        //on construit un tableau des questions qu'il reste à poser
        $arrayQuestion2ask = [];
        foreach($questions as $question) {
           
            if(! in_array($question->getID(), $arrayAnsweredQuestion) ) {
                $arrayQuestion2ask[] = $question;
            }
        }
        return $this->render(
            'quizz/quizzshow.html.twig', 
            [ 
                'quizz' => $quizz,
                'questions' => $questions,
                'questions2ask' => $arrayQuestion2ask
            ]);
    }
}

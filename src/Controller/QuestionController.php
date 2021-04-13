<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Answer;
use App\Form\QuestionType;
use App\Form\AnswerType;
use App\Repository\QuestionRepository;
use App\Repository\AnswerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/question")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="question_index", methods={"GET"})
     */
    public function index(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/index.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="question_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $question = new Question();
        $answer = new Answer();

        $formQuestion = $this->createForm(QuestionType::class, $question);
        $formQuestion->handleRequest($request);

        if ($formQuestion->isSubmitted() && $formQuestion->isValid()) {
            $question = $formQuestion->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('question_index');
        }

        return $this->render('question/new.html.twig', [
            'question' => $question,
            'formquestion' => $formQuestion->createView(),
        ]);
    }



    /**
     * @Route("/{id}", name="question_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    
     /**
     * @Route("/validate_answer", name="validate_answer", methods={"POST"})
     */
    public function validate(
        Request $request,
        QuestionRepository $questionRepository,
        AnswerRepository $answerRepository): Response
    {
        //get Question
        $postId = $request->request->get('questionid');
        $question = $questionRepository->find($postId);

        //get Answer
        $answerId = $request->request->get('answerId');
        $playeranswer = $answerRepository->find($answerId);

        //on compare playerAnswer et goodanswer
        if($question->getGoodanswer() === $playeranswer) {
            $resultat = 'BRAVO';
            //mise à jour entité quizz (qui li a un player)
            //mettre à jour le score .... 
            //rediriger vers la question suivante
        } else {
            $resultat = 'FAUX !';
            //mise à jour entité quizz (qui li a un player)
            //mettre à jour le score .... 
            //rediriger vers la question suivante
        }
        
        return $this->render(
            'question/validate.html.twig',
            ['resultat' => $resultat]
        );
    }

    /**
     * @Route("/onequestion", name="question_test", methods={"GET"})
     */
    public function onequestion(QuestionRepository $questionRepository): Response
    {
        $question = $questionRepository->find(1);

        return $this->render(
            'question/onequestion.html.twig',
            ['question' => $question]);
    }


    /**
     * @Route("/{id}/edit", name="question_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Question $question): Response
    {
        $formquestion = $this->createForm(QuestionType::class, $question);
        $formquestion->handleRequest($request);

        if ($formquestion->isSubmitted() && $formquestion->isValid()) {
            $question = $formquestion->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('question_index');
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'formquestion' => $formquestion->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="question_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Question $question): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('question_index');
    }
}

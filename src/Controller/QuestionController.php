<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Answer;
use App\Entity\Quizz;
use App\Entity\Playeranswer;
use App\Form\QuestionType;
use App\Form\AnswerType;
use App\Repository\QuestionRepository;
use App\Repository\AnswerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;

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
    public function new(Request $request, FileUploader $file_uploader): Response
    {
        $question = new Question();
        $answer = new Answer();

        $formQuestion = $this->createForm(QuestionType::class, $question);
        $formQuestion->handleRequest($request);

        if ($formQuestion->isSubmitted() && $formQuestion->isValid()) {
            $question = $formQuestion->getData();
            
            $file = $formQuestion['upload_file']->getData();
            $question->setMediaurl($file);
            if ($file) 
            {
                $file_name = $file_uploader->upload($file);
                if (null !== $file_name) // for example
                {
                $directory = $file_uploader->getTargetDirectory();
                $full_path = $directory.'/'.$file_name;
                // Do what you want with the full path file...
                // Why not read the content or parse it !!!
                }
                else
                {
                // Oups, an error occured !!!
                }
            }
            
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

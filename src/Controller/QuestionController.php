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
use App\Repository\PlayeranswerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;

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
        $package = new PathPackage('/uploads', new StaticVersionStrategy(''));

        $formQuestion = $this->createForm(QuestionType::class, $question);
        $formQuestion->handleRequest($request);

        if ($formQuestion->isSubmitted() && $formQuestion->isValid()) {
            $question = $formQuestion->getData();
             // ISSUE #1: Auto ajout de la bonne réponse
             if(!($question->getAnswers()->contains($question->getGoodanswer()))){
                $question->addAnswer($question->getGoodanswer());
            }
            $file = $formQuestion['upload_file']->getData();
            if ($file) 
            {
                
                $file_name = $file_uploader->upload($file);
                $question->setMediaurl($package->getUrl($file_name));
                if (null !== $file_name) // for example
                {
                $directory = $file_uploader->getTargetDirectory();
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
             // ISSUE #1: Auto ajout de la bonne réponse
             if(!($question->getAnswers()->contains($question->getGoodanswer()))){
                $question->addAnswer($question->getGoodanswer());
            }
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
    public function delete(Request $request, Question $question, PlayeranswerRepository $playeranswerRepository, $id): Response
    {

        $playerAnswers =  $playeranswerRepository->findBy(['question' => $id]);

        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach($playerAnswers as $playerAnswer){
                $entityManager->remove($playerAnswer);
            }
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('question_index');
    }
}

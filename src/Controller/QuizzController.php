<?php

namespace App\Controller;

use App\Repository\PlayeranswerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Administrator;
use App\Entity\Quizz;
use App\Entity\Question;
use App\Entity\Answer;
use App\Entity\Playeranswer;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DifficultyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Repository\QuestionRepository;
use App\Repository\AnswerRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class QuizzController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function home(Request $request)
    {
        // redirects to the "quizz" route
        return $this->redirectToRoute('quizz');
    }

    /**
     * @Route("/quizz", name="quizz")
     */
    public function quizz(Request $request)
    {
        //form start quizz
        $quizz = new Quizz();
        $quizz->setScore(0);
        $quizz->setCombo(1);
        $quizz->difficulty = 15;

        $form = $this->createFormBuilder($quizz)
            ->add('playername', TextType::class, ['attr' => array('placeholder' => 'Pseudonyme')])
            ->add('start', SubmitType::class, ['label' => 'Démarrer le Quizz'])
            ->getForm();

        $form->handleRequest($request);
        $error = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $quizz = $form->getData();

            $quizzRepository = $this->getDoctrine()->getRepository(Quizz::class);
            if ($quizzRepository->playernameAvailable($quizz->getPlayername())) {
                $quizz->setDate(new DateTime('now'));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($quizz);
                $entityManager->flush();
                return $this->redirectToRoute('quizz_settings', array('id' => $quizz->getId()));
                // return $this->redirectToRoute('quizz_nextquestion', array('id' => $quizz->getId()));1
            }
            $error = "Le nom d'utilisateur est déjà pris";
        }

        return $this->render(
            'quizz/quizz.html.twig',
            [
                'form' => $form->createView(),
                'error' => $error,
            ]
        );
    }


    /**
     * @Route("/credits", name="credits")
     */
    public function credits(Request $request)
    {
        return $this->render(
            'credits/credits.html.twig',
            []
        );
    }


    /**
     * @Route("/quizz/{quizz_id}/validate/{question_id}/{answer_id}", name="validate", methods={"GET"} , requirements={"quizz_id"="\d+"})
     * @Entity("quizz", expr="repository.find(quizz_id)")
     * @Entity("question", expr="repository.find(question_id)")
     * @Entity("answer", expr="repository.find(answer_id)")
     */
    public function validate(
        Quizz $quizz,
        Question $question,
        Answer $answer
    ): Response {

        $playeranswerRepository = $this->getDoctrine()->getRepository(Playeranswer::class);
        if ($playeranswerRepository->questionAlreadyAnswered($quizz->getId(), $question->getId())) {
            return $this->quizzNextQuestion($quizz);
        }

        $playeranswer = new Playeranswer();
        $playeranswer->setQuizz($quizz);
        $playeranswer->setQuestion($question);
        $playeranswer->setPickedanswer($answer);

        //on compare playerAnswer et goodanswer
        $bonus = 0;
        if ($question->getGoodanswer() === $answer) {
            $playeranswer->setStatus("yes");
            // Enregistrer le score
            $bonus = 100 * $quizz->getCombo();
            if ($quizz->getCombo() != 8) {
                $quizz->setCombo($quizz->getCombo() * 2);
            }
            $quizz->setScore($quizz->getScore() + $bonus);
        } else {
            $playeranswer->setStatus("no");
            // Enregistrer le score
            $bonus = -50;
            $quizz->setCombo(1);
            if ($quizz->getScore() >= 50) {
                $quizz->setScore($quizz->getScore() + $bonus);
            }
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($playeranswer);
        $entityManager->flush();

        //put message in the session; 

        return $this->redirectToRoute('quizz_answer', array(
            'id' => $quizz->getId(),
            'playeranswer_id' => $playeranswer->getId(),
            'bonus' => $bonus
        ));
    }

    // ISSUE #18: Historique des Quizzs passés
    /**
     * @Route("/quizz/history", name="quizz_history")
     */
    public function history(Request $request): Response
    {
        $quizzRepository = $this->getDoctrine()->getRepository(Quizz::class);
        return $this->render('quizz/history.html.twig', [
            'quizzs' => $quizzRepository->findAll(),
        ]);
    }

    /**
     * @Route("/quizz/{id}/question/{slug}", name="quizz_question")
     * @Entity("quizz", expr="repository.find(id)")
     */
    public function quizzQuestion(
        Request $request,
        Quizz $quizz,
        $slug
    ) {
        // ISSUE #91: non usage du param converter pour $question voir : https://github.com/vincentkollebolle/quizzpoledance/issues/91
        $questionRepository = $this->getDoctrine()->getRepository(Question::class);
        $question = $questionRepository->findOneBySlug($slug);

        $playeranswerRepository = $this->getDoctrine()->getRepository(Playeranswer::class);
        if ($playeranswerRepository->questionAlreadyAnswered($quizz->getId(), $question->getId())) {
            return $this->quizzNextQuestion($quizz);
        }

        $repository = $this->getDoctrine()->getRepository(Question::class);
        $questions = $repository->findAccording2Difficulty($quizz->difficulty);

        $repository = $this->getDoctrine()->getRepository(Playeranswer::class);
        $playeranswers = $repository->findByQuizz($quizz);


        return $this->render(
            'quizz/quizzquestion.html.twig',
            [
                'quizz' => $quizz,
                'question' => $question,
                'questions' => $questions,
                'playeranswers' => $playeranswers

            ]
        );
    }


    /**
     * @Route("/quizz/{id}/answer/{playeranswer_id}/{bonus}", name="quizz_answer")
     * @Entity("playeranswer", expr="repository.find(playeranswer_id)")
     */
    public function quizzAnswer(
        Request $request,
        Quizz $quizz,
        Playeranswer $playeranswer,
        $bonus
    ) {
        $repository = $this->getDoctrine()->getRepository(Question::class);
        $questions = $repository->findAccording2Difficulty($quizz->difficulty);

        return $this->render(
            'quizz/quizzanswer.html.twig',
            [
                'quizz' => $quizz,
                'questions' => $questions,
                'playeranswer' => $playeranswer,
                'bonus' => $bonus
            ]
        );
    }

    /**
     * @Route("/quizz/{id}/settings", name="quizz_settings")
     */
    public function quizzSettings(Quizz $quizz, Request $request)
    {
        $form = $this->createFormBuilder($quizz)
            ->add('difficulty', ChoiceType::class, [
                'choices'  => [
                    'Débutant' => 15,
                    'Intermédiaire' => 25,
                    'Avancé' => 50,
                ],
            ])
            ->add('start', SubmitType::class, ['label' => 'Démarrer le Quizz'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $quizz = $form->getData();
            $quizz->setDate(new DateTime('now'));
            // $quizz->setDifficulty( $quizz->difficulty )
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quizz);
            $entityManager->flush();
            // return $this->redirectToRoute('quizz_settings', array('id' => $quizz->getId() ));
            return $this->redirectToRoute('quizz_nextquestion', array('id' => $quizz->getId()));
        }

        return $this->render(
            'quizz/settings.html.twig',
            [
                'form' => $form->createView(),
                'quizz' => $quizz
            ]
        );
    }

    /**
     * @Route("/quizz/{id}/nextquestion", name="quizz_nextquestion")
     */
    public function quizzNextQuestion(Quizz $quizz)
    {
        //get unanswered questions
        //toutes les questions qui ne sont pas dans l'history du player
        $repository = $this->getDoctrine()->getRepository(Question::class);
        $questions = $repository->findAccording2Difficulty($quizz->difficulty);

        $repository = $this->getDoctrine()->getRepository(Playeranswer::class);
        $playeranswers = $repository->findByQuizz($quizz);

        //on construit un tableau d'id des question auxquelles il a déjà été répondu.
        $arrayAnsweredQuestion = [];
        foreach ($playeranswers as $playeranswer) {
            $arrayAnsweredQuestion[] =  $playeranswer->getQuestion()->getSlug();
        }

        //on construit un tableau des questions qu'il reste à poser
        $arrayQuestion2ask = [];
        //Action qui permet de rendre l'apparation des questions aléatoire.
        shuffle($questions);
        foreach ($questions as $question) {
            if (!in_array($question->getSlug(), $arrayAnsweredQuestion)) {
                $arrayQuestion2ask[] = $question;
            }
        }

        //
        if (count($arrayQuestion2ask) > 0) {
            return $this->redirectToRoute(
                'quizz_question',
                array(
                    'id' => $quizz->getId(),
                    'slug' => $arrayQuestion2ask[0]->getSlug()
                )
            );
        } else {
            return $this->render(
                'quizz/endofquizz.html.twig',
                [
                    'quizz' => $quizz
                ]
            );
        }
    }
}

<?php
namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Goutte\Client;

class QuestionsAnswersFixtures extends Fixture
{
    private $figuresInfos;

    public function load(ObjectManager $manager)
    {
        $client = new Client();
        $url='http://www.poledancestars.fr/album/debutant/pole-dance-debutant/';
        for($i = 1; $i<4; $i++){
            $i == 1 ? $page = '' : $page = $i;
            $crawler = $client->request('GET', $url.$page);
            $this->figuresInfos = [];
            $crawler->filter('.media-object > a')->each(function ($node) {
                $img = $node->attr('href');
                $content = $node->attr('title');
                $this->figuresInfos[$content] = $img;
            });

            foreach($this->figuresInfos as $content => $img){
                $question = new Question();
                $question->setTitle($content);
                $question->setContent('Quel est le nom de cette figure?');
                $content_img = file_get_contents($img);
                
                //Store in the filesystem.
                if (!file_exists('public/uploads/')) {
                    mkdir('public/uploads/');
                }
                $fp = fopen("public/uploads/".basename(parse_url($img, PHP_URL_PATH)), "w");
                fwrite($fp, $content_img);
                fclose($fp);

                $question->setMediaurl("/uploads/".basename(parse_url($img, PHP_URL_PATH)));
                $answer = new Answer;
                $answer->setContent($content);
                $manager->persist($answer);
                $question->setGoodanswer($answer);
                $manager->persist($question);
                $manager->flush();
            }
        }
        $questionRepository = $manager->getRepository(Question::class);
        $answerRepository = $manager->getRepository(Answer::class);
        $questions = $questionRepository->findAll();
        
        foreach($questions as $question){
            $answers = $answerRepository->findRandomAnswers($question->getGoodAnswer()->getId());
            $randomAnswers = array_rand($answers, 4);
            $goodAnswer = $answerRepository->find($question->getGoodAnswer());
            $question->addAnswer($goodAnswer);
            foreach($randomAnswers as $randomAnswer){
                $question->addAnswer($answers[$randomAnswer]);
            }
            $manager->persist($question);
            $manager->flush();
        }
    }
}
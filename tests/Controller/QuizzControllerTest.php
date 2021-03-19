<?php 
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuizzControllerTest extends WebTestCase
{
    /*
    * Scénarion : on peut afficher la page pour être prévenu de la sortie du quizz
    * Given: Un utilisateur est sur la page  
    * and || but
    * When: Il accède à la page
    * Then: la page web se charge (200 OK)
    * and: Un titre h1 contenant le mot quizz apparait 
    * and: on lui propose un formulaire pour ajouter son mail
    */
    public function testPrintSubscribePage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        //Then: la page web se charge (200 OK)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        //and: Un titre h1 contenant le mot quizz apparait 
        $this->assertSelectorTextContains('html h1', 'quizz');

        //and: on lui propose un formulaire pour ajouter son mail
        $this->assertCount(1, $crawler->filter('form.subscribeForm'));
    }

    /*
    * Scénario : Un utilisateur peut laisser son email pour être notifier de la sortie du quizz.
    */
    
}
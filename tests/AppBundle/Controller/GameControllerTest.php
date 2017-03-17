<?php declare(strict_types = 1);

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameControllerTest extends WebTestCase
{
    public function testListAndShowView()
    {
        $client = self::createClient();
        $client->followRedirects(true);

        $fixturePath = $client
                ->getContainer()
                ->getParameter('kernel.root_dir').'/Resources/fixtures/test.yml';

        $client
            ->getContainer()
            ->get('app.fixtures.loader')
            ->loadFixtures($fixturePath);

        // check list
        $crawler = $client->request('GET', '/en/game/list');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals($crawler->filter('section.list')->count(), 1);
        $this->assertEquals($crawler->filter('section.game')->count(), 8);
        $this->assertEquals($crawler->filter('section.game a')->count(), 8);


        // check game
        $crawler = $client->click($crawler->filter('section.game a')->link());
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals($crawler->filter('#game')->count(), 1);
    }
}

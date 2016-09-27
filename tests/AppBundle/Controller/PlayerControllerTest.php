<?php

namespace tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlayerControllerTest extends WebTestCase
{
    public function testListAndShowAndVersusView()
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
        $crawler = $client->request('GET', '/en/player');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(4, $crawler->filter('section.user a')->count());


        // check show
        $crawler = $client->click($crawler->filter('section.user a')->link());
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(3, $crawler->filter('section.vs a')->count());

        // check versus
        $crawler = $client->request('GET', '/en/player/torben/vs/tamara');
        $this->assertTrue($client->getResponse()->isSuccessful());
        echo $client->getResponse()->getContent();
        $this->assertEquals(4, $crawler->filter('section.list')->count());
    }
}
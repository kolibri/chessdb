<?php declare(strict_types = 1);

namespace Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = self::createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testHomepage()
    {
        $client = self::createClient();

        $crawler = $client->request('GET', '/de/');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}

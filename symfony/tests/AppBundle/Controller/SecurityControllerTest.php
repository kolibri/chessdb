<?php

namespace Tests\AppBundle\Controller;

use Doctrine\Bundle\DoctrineBundle\DataCollector\DoctrineDataCollector;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector;

class SecurityControllerTest extends WebTestCase
{
    public function testRegistrationProcess()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $client->followRedirects(false);

        $crawler = $client->request('GET', '/register');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $this->assertCount(1, $crawler->filter('.register form'));

        $form = $crawler->selectButton('Register')->form();
        $form['user_registration[username]'] = 'torbentester';
        $form['user_registration[emailAddress]'] = 'torben@tester.dev';
        $form['user_registration[rawPassword][first]'] = 'password';
        $form['user_registration[rawPassword][second]'] = 'password';

        $client->submit($form);

//        echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->isRedirect(), 'Submit form should be redirect');

        /** @var MessageDataCollector $mailCollector */
        $mailCollector = $client->getProfile()->getCollector('swiftmailer');
        $this->assertSame(1, $mailCollector->getMessageCount());

        /** @var DoctrineDataCollector $doctrineCollector */
        $doctrineCollector = $client->getProfile()->getCollector('db');
        $this->assertEquals(3, $doctrineCollector->getQueryCount());
        $this->assertEquals(
            ['default' => ['AppBundle\Entity\User' => 'AppBundle\Entity\User']],
            $doctrineCollector->getEntities()
        );

        $client->followRedirect();

        $this->assertEquals('/', $client->getRequest()->getRequestUri());
    }
}

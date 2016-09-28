<?php

namespace AppBundle\Features\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Behat\Symfony2Extension\Driver\KernelDriver;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector;
use Symfony\Component\HttpKernel\Profiler\Profile;
use PHPUnit_Framework_ExpectationFailedException as AssertException;
/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext
{
    private $kernel;
    use KernelDictionary;

    public function setKernel($kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @return Profile
     * @throws UnsupportedDriverActionException
     */
    public function getSymfonyProfile()
    {
        $profile = $this->getClient()->getProfile();
        if (false === $profile) {
            throw new \RuntimeException(
                'The profiler is disabled. Activate it by setting '.
                'framework.profiler.only_exceptions to false in '.
                'your config'
            );
        }

        return $profile;
    }

    /**
     * @When I load :arg1 fixtures
     */
    public function iLoadFixtures($arg1)
    {
        $fixturePath = $this
                ->getContainer()
                ->getParameter('kernel.root_dir').'/Resources/fixtures/'.$arg1.'.yml';

        $this
            ->getContainer()
            ->get('app.fixtures.loader')
            ->loadFixtures($fixturePath);
    }

    /**
     * @Given /^I should get an email on "(?P<email>[^"]+)" with:$/
     */
    public function iShouldGetAnEmail($email, PyStringNode $text)
    {
        $error     = sprintf('No message sent to "%s"', $email);
        $profile   = $this->getSymfonyProfile();
        /** @var MessageDataCollector $collector */
        $collector = $profile->getCollector('swiftmailer');

        /** @var \Swift_Message[] $messages */
        $messages = $collector->getMessages();

        if (empty($messages)) {
            throw new ExpectationException($error, $this->getSession());
        }

        foreach ($messages as $message) {
            if (!array_key_exists($email, $message->getTo())) {
                continue;
            }

            \PHPUnit_Framework_Assert::assertContains($text->getRaw(), $message->getBody());
        }
    }

    /**
     * @Then the response should be a redicrect
     */
    public function theResponseShouldBeARedicrect()
    {
        \PHPUnit_Framework_Assert::assertTrue($this->getClient()->getResponse()->isRedirect());
    }

    /**
     * @When I disable redirects
     */
    public function iDisableRedirects()
    {
        $this->getClient()->followRedirects(false);
    }

    /**
     * @When I enable redirects
     */
    public function iEnableRedirects()
    {
        $this->getClient()->followRedirects(true);
    }

    /**
     * @When I follow the redirect
     */
    public function iFollowTheRedirect()
    {
        $this->getClient()->followRedirect();
    }

    /**
     * @return Client
     * @throws UnsupportedDriverActionException
     */
    private function getClient()
    {
        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof KernelDriver) {
            throw new UnsupportedDriverActionException(
                'You need to tag the scenario with '.
                '"@mink:symfony2". Using the profiler is not '.
                'supported by %s', $driver
            );
        }

        return $driver->getClient();
    }
}

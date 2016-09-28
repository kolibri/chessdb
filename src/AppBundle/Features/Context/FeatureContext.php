<?php

namespace AppBundle\Features\Context;

use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;

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
}

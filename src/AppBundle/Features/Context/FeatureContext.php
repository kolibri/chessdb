<?php

namespace AppBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use \Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\HttpKernel\KernelInterface;
use \Behat\MinkExtension\Context\MinkContext;
/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext
//class FeatureContext implements Context, KernelAwareContext
{


}

<?php

namespace SpomkyLabs\OAuth2ServerBundle\Features\Context;

use Behat\Gherkin\Node\PyStringNode;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Command\CleanCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

trait ApplicationContext
{
    private $application = null;
    private $command_output = null;

    /**
     * Returns HttpKernel service container.
     *
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    abstract public function getContainer();

    /**
     * Returns the kernel.
     *
     * @return \Symfony\Component\HttpKernel\KernelInterface
     */
    abstract public function getKernel();

    /**
     * @param string $command_output
     *
     * @return self
     */
    protected function setCommandOutput($command_output)
    {
        $this->command_output = $command_output;

        return $this;
    }

    /**
     * @return null|string
     */
    protected function getCommandOutput()
    {
        return $this->command_output;
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Console\Application
     */
    protected function getApplication()
    {
        if (null === $this->application) {
            $this->application = new Application($this->getKernel());
            $this->application->add(new CleanCommand());
        }

        return $this->application;
    }

    /**
     * @When I run :line command
     */
    public function iRunCommand($line)
    {
        $command = $this-> getApplication()->find($line);
        $tester = new CommandTester($command);
        $tester->execute(['command' => $command->getName()]);
        $this->setCommandOutput($tester->getDisplay());
    }

    /**
     * @Then I should see
     */
    public function iShouldSee(PyStringNode $result)
    {
        if ($this->getCommandOutput() !== $result->getRaw()) {
            throw new \Exception('The output of the command is not the same as expected. I got '.$this->command_output.'');
        }
    }
}

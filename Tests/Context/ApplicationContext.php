<?php

namespace SpomkyLabs\OAuth2ServerBundle\Features\Context;

use Behat\Gherkin\Node\PyStringNode;
use SpomkyLabs\OAuth2ServerBundle\Command\CleanCommand;
use SpomkyLabs\OAuth2ServerBundle\Command\PasswordClientCommand;
use SpomkyLabs\OAuth2ServerBundle\Command\PublicClientCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

trait ApplicationContext
{
    private $application = null;
    private $command_output = null;
    private $command_exception = null;
    private $command_exit_code = null;
    private $command_parameters = [];

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
     * @return null|array
     */
    protected function getCommandParameters()
    {
        return $this->command_parameters;
    }

    /**
     * @return null|int
     */
    protected function getCommandException()
    {
        return $this->command_exception;
    }

    /**
     * @return null|int
     */
    protected function getCommandExitCode()
    {
        return $this->command_exit_code;
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Console\Application
     */
    protected function getApplication()
    {
        if (null === $this->application) {
            $this->application = new Application($this->getKernel());
            $this->application->add(new CleanCommand());
            $this->application->add(new PublicClientCommand());
            $this->application->add(new PasswordClientCommand());
        }

        return $this->application;
    }

    /**
     * @When I run command :line
     */
    public function iRunCommand($line)
    {
        $command = $this->getApplication()->find($line);
        $tester = new CommandTester($command);

        try {
            $this->exit_code = $tester->execute($this->getCommandParams($command));
            $this->command_exception = null;
            $this->command_exit_code = null;
        } catch (\Exception $e) {
            $this->command_exception = $e;
            $this->command_exit_code = $e->getCode();
        }
        $this->setCommandOutput($tester->getDisplay());
    }
    /**
     * @Given I run command :line with parameters
     */
    public function iRunACommandWithParameters($line, PyStringNode $parameterJson)
    {
        $this->command_parameters = json_decode($parameterJson->getRaw(), true);
        if (null === $this->command_parameters) {
            throw new \InvalidArgumentException(
                "PyStringNode could not be converted to json."
            );
        }

        $this->iRunCommand($line);
    }

    /**
     * @Then I should see
     */
    public function iShouldSee(PyStringNode $result)
    {
        if ($this->getCommandOutput() !== $result->getRaw()) {
            throw new \Exception('The output of the command is not the same as expected. I got '.$this->getCommandOutput().'');
        }
    }

    /**
     * @Then I should see something like :pattern
     */
    public function iShouldSeeSomethingLike($pattern)
    {
        $result = preg_match($pattern, $this->getCommandOutput(), $matches);
        if (0 === $result) {
            throw new \Exception(sprintf('The command output "%s" does not match with the pattern', $this->getCommandOutput()));
        }
    }

    /**
     * @Then The command exception :exception should be thrown
     */
    public function theCommandExceptionShouldBeThrown($exception)
    {
        /*$this->checkThatCommandHasRun();
        $this
            ->getSubcontext('exception')
            ->setException($this->commandException)
            ->assertException($exceptionClass)*/
        ;
    }
    /**
     * @Then The command exit code should be :code
     */
    public function theCommandExitCodeShouldBe($code)
    {
        /*$this->checkThatCommandHasRun();
        assertEquals($exitCode, $this->exitCode);*/
    }
    /**
     * @Then I should see :regexp in the command output
     */
    public function iShouldSeeInTheCommandOutput($regexp)
    {
        /*$this->checkThatCommandHasRun();

        assertRegExp($regexp, $this->tester->getDisplay());*/
    }
    /**
     * @Then The command exception :exception with message :message should be thrown
     */
    public function theCommandExceptionWithMessageShouldBeThrown($exception, $message)
    {
        /*$this->checkThatCommandHasRun();
        $this
            ->getSubcontext('exception')
            ->setException($this->getCommandException())
            ->assertException($exception)
        ;
        $this
            ->getSubcontext('exception')
            ->assertExceptionMessage($message)
        ;*/
    }

    /**
     * @param string $command
     *
     * @return array
     */
    private function getCommandParams($command)
    {
        return array_merge(
            $this->getCommandParameters(),
            ['command' => $command]
        );
    }
}

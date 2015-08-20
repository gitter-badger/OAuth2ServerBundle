<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Command;

use SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Model\SimpleStringAccessTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanAccessTokensCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('spomky:oauth2-server:simple-string-tokens:clean')
            ->setDescription('Clean expired Simple String Access Tokens')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command will remove expired OAuth2 Simple String Access Tokens.

  <info>php %command.full_name%</info>
EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $service = $this->getContainer()->get('oauth2_server.simple_string_access_token.manager');
        if (!$service instanceof SimpleStringAccessTokenManagerInterface) {
            throw new \Exception('The access token manager is not Simple String Access Token Manager');
        }
        $result = $service->deleteExpired();
        $output->writeln(sprintf('Removed <info>%d</info> expired access tokens from storage.', $result));
    }
}

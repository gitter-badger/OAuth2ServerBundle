<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTAccessTokenPlugin\Command;

use SpomkyLabs\OAuth2ServerBundle\Plugin\JWTAccessTokenPlugin\Model\JWTAccessTokenManagerInterface;
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
            ->setName('spomky:oauth2-server:jwt-tokens:clean')
            ->setDescription('Clean expired JWT Access Tokens')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command will remove expired OAuth2 JWT Access Tokens.

  <info>php %command.full_name%</info>
EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $service = $this->getContainer()->get('oauth2_server.jwt_access_token.manager');
        if (!$service instanceof JWTAccessTokenManagerInterface) {
            throw new \Exception('The access token manager is not JWT Access Token Manager');
        }
        $result = $service->deleteExpired();
        $output->writeln(sprintf('Removed <info>%d</info> expired access tokens from storage.', $result));
    }
}

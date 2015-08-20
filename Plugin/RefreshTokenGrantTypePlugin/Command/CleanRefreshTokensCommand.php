<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\Command;

use SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\Model\RefreshTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanRefreshTokensCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('spomky:oauth2-server:refresh-tokens:clean')
            ->setDescription('Clean expired Refresh Tokens')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command will remove expired OAuth2 Refresh Tokens.

  <info>php %command.full_name%</info>
EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $service = $this->getContainer()->get('oauth2_server.refresh_token.token_manager');
        if (!$service instanceof RefreshTokenManagerInterface) {
            throw new \Exception('The refresh token manager is not an instance of RefreshTokenManagerInterface');
        }
        $result = $service->deleteExpired();
        $output->writeln(sprintf('Removed <info>%d</info> expired refresh token from storage.', $result));
    }
}

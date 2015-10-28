<?php

namespace SpomkyLabs\OAuth2ServerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('spomky-labs:oauth2-server:clean')
            ->setDescription('Clean expired tokens')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command will remove expired tokens.

  <info>php %command.full_name%</info>
EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $service = $this->getContainer()->get('oauth2_server.cleaner');
        $result = $service->clean();
        foreach ($result as $cleaner => $data) {
            foreach ($data as $name => $counter) {
                $output->writeln(sprintf('Cleaner <info>"%s"</info> removed <info>%d %s</info> from storage.', $cleaner, $counter, $name));
            }
        }
    }
}

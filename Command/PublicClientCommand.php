<?php

namespace SpomkyLabs\OAuth2ServerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublicClientCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('spomky-labs:oauth2-server:public-client:create')
            ->setDescription('Create a new public client')
            ->addArgument(
                'allowed_grant_types',
                InputArgument::OPTIONAL,
                'Allowed grant types (comma separated)'
            )
            ->addArgument(
                'redirect_uris',
                InputArgument::IS_ARRAY|InputArgument::OPTIONAL,
                'Redirect URIs'
            )
            ->setHelp(<<<EOT
The <info>%command.name%</info> command will create a new public client.

  <info>php %command.full_name%</info>
EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (false === $this->getContainer()->has('oauth2_server.public_client.manager')) {
            $output->writeln('Public client plugin is not enabled.');
            return;
        }
        /**
         * @var $service \SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model\PublicClientManagerInterface
         */
        $service = $this->getContainer()->get('oauth2_server.public_client.manager');
        $client = $service->createClient();
        foreach(['redirect_uris'=>'setRedirectUris'] as $argument=>$method) {
            if (null !== ($value = $input->getArgument($argument))) {
                $client->$method($value);
            }
        }
        if (null !== ($value = $input->getArgument('allowed_grant_types'))) {
            $client->setAllowedGrantTypes(explode(',',$value));
        }
        $service->saveClient($client);

        $output->writeln(sprintf('Public client successfully created. Public ID is <info>"%s"</info>.', $client->getPublicId()));
    }
}

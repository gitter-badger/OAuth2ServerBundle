<?php

namespace SpomkyLabs\OAuth2ServerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
            ->addOption(
                'allowed_grant_types',
                null,
                InputOption::VALUE_REQUIRED|InputOption::VALUE_IS_ARRAY,
                'Allowed grant types',
                []
            )
            ->addOption(
                'redirect_uris',
                null,
                InputOption::VALUE_REQUIRED|InputOption::VALUE_IS_ARRAY,
                'Redirect URIs',
                []
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
        /**
         * @var $service \SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model\PublicClientManagerInterface
         */
        $service = $this->getContainer()->get('oauth2_server.public_client.client_manager');
        $client = $service->createClient();
        foreach(['allowed_grant_types'=>'setAllowedGrantTypes', 'redirect_uris'=>'setRedirectUris'] as $option=>$method) {
            $client->$method($input->getOption($option));
        }
        $service->saveClient($client);

        $output->writeln(sprintf('Public client successfully created. Public ID is <info>"%s"</info>.', $client->getPublicId()));
    }

    public function isEnabled()
    {
        return $this->getContainer()->has('oauth2_server.public_client.client_manager');
    }
}

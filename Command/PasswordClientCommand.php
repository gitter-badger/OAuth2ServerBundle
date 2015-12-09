<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PasswordClientCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('spomky-labs:oauth2-server:password-client:create')
            ->setDescription('Create a new password client')
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                'The password'
            )
            ->addOption(
                'allowed_grant_types',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Allowed grant types',
                []
            )
            ->addOption(
                'redirect_uris',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Redirect URIs',
                []
            )
            ->setHelp(<<<EOT
The <info>%command.name%</info> command will create a new password client.

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
         * @var \SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model\PasswordClientManagerInterface
         */
        $service = $this->getContainer()->get('oauth2_server.password_client.client_manager');
        $client = $service->createClient();
        foreach (['password' => 'setPlaintextSecret'] as $argument => $method) {
            $client->$method($input->getArgument($argument));
        }
        foreach (['allowed_grant_types' => 'setAllowedGrantTypes', 'redirect_uris' => 'setRedirectUris'] as $option => $method) {
            $client->$method($input->getOption($option));
        }
        $service->saveClient($client);

        $output->writeln(sprintf('Password client successfully created. Public ID is <info>"%s"</info>.', $client->getPublicId()));
    }

    public function isEnabled()
    {
        return $this->getContainer()->has('oauth2_server.password_client.client_manager');
    }
}

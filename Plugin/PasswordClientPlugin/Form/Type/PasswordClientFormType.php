<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordClientFormType extends AbstractType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plaintext_secret', 'repeated', [
                'type' => 'password',
                'first_options' => ['label' => 'password_client.form.password'],
                'second_options' => ['label' => 'password_client.form.password_confirmation'],
                'invalid_message' => 'spomky_labs.oauth2_server.password.mismatch',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'SpomkyLabsOAuth2Server',
            'data_class'         => $this->class,
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'oauth2_server_password_client';
    }
}

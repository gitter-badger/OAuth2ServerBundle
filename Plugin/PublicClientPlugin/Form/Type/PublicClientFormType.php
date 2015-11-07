<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublicClientFormType extends AbstractType
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
        return 'oauth2_server_public_client';
    }
}

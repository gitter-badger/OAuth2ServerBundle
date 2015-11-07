<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisteredClientFormType extends AbstractType
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
            ->add('redirect_uris', 'collection', [
                'type' => new TextType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => 'redirect_uri__',
                'options' => [],
            ])
            ->add('allowed_grant_types', 'collection', [
                'type' => new TextType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => 'allowed_grant_type__',
                'options' => [],
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
        return 'oauth2_server_registered_client';
    }
}

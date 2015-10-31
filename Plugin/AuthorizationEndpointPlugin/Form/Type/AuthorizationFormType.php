<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorizationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('accept', 'submit', [
                'label' => 'authorization.form.accept',
            ])
            ->add('reject', 'submit', [
                'label' => 'authorization.form.reject',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'SpomkyLabsOAuth2Server',
            'data_class'         => 'OAuth2\Endpoint\Authorization',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'oauth2_server_authorization';
    }
}

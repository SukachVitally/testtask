<?php

namespace AppBundle\Doc;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;


/**
 * Used for NelmioApiDoc.
 */
class OutputUser extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', null, ['description' => 'User id']);
        $builder->add('username', null, ['description' => 'Username']);
        $builder->add('apiKey', null, ['description' => 'ApiKey']);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }
}

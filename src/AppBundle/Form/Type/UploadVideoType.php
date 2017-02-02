<?php

namespace AppBundle\Form\Type;

use AppBundle\Model\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

/**
 * Class UploadVideo
 */
class UploadVideoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, ['description' => 'File', 'required' => true])
            ->add('start_time', IntegerType::class, ['description' => 'Start time', 'required' => true])
            ->add('end_time', IntegerType::class, ['description' => 'Start time', 'required' => true]);

    }

    /**
     * {@inheritdoc}
     *
     * @throws AccessException If called from a lazy option or normalizer
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'csrf_protection' => false,
            'method' => 'Post',
            'data_class' => Video::class,
            'allow_extra_fields' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }
}

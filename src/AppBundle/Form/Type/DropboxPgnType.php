<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\DropboxPgn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DropboxPgnType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path', HiddenType::class)
            ->add('importPgn', ImportPgnType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => DropboxPgn::class,
                'empty_data' => function (FormInterface $form) {
                    return new DropboxPgn(
                        $form
                            ->get('path')
                            ->getData(),
                        $form
                            ->get('importPgn')
                            ->getData()
                    );
                },
            ]
        );
    }
}

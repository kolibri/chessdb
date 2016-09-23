<?php


namespace AppBundle\Form\Type;

use AppBundle\Entity\ImportPgn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImportPgnType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'pgnString',
            TextareaType::class,
            [
                'attr' => ['rows' => 20, 'cols' => 80],
                'label' => 'form.importPgn.label.pgnString'
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ImportPgn::class,
                'empty_data' => function (FormInterface $form) {
                    return new ImportPgn(
                        $form
                            ->get('pgnString')
                            ->getData()
                    );
                },
            ]
        );
    }
}

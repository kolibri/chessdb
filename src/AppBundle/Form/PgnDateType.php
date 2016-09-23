<?php

namespace AppBundle\Form;

use AppBundle\Form\DataTransformer\PgnDateTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PgnDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new PgnDateTransformer());
    }

    public function getParent()
    {
        return TextType::class;
    }
}

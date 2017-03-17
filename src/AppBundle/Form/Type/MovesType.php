<?php declare(strict_types = 1);

namespace AppBundle\Form\Type;

use AppBundle\Form\DataTransformer\MovesTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovesType extends AbstractType
{
    private $transformer;

    public function __construct(MovesTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'attr' => ['rows' => 10, 'cols' => 80],
            ]
        );
    }

    public function getParent(): string
    {
        return TextareaType::class;
    }
}

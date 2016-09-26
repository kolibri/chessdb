<?php


namespace AppBundle\Form\Type;

use AppBundle\Entity\ImportPgn;
use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ImportPgnType extends AbstractType
{
    /** @var TokenStorage */
    private $securityTokenStorage;

    public function __construct(TokenStorage $securityTokenStorage)
    {
        $this->securityTokenStorage = $securityTokenStorage;
    }

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
                    $user = $this->securityTokenStorage->getToken()->getUser();

                    if (!$user instanceof User) {
                        throw new \LogicException('This form needs a authenticated user');
                    }

                    return new ImportPgn(
                        $form
                            ->get('pgnString')
                            ->getData(),
                        $user
                    );
                },
            ]
        );
    }
}

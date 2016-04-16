<?php

namespace RezervationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RezervationType extends AbstractType
{

    private $tokenStorage;

    public function __construct($seatNumber, $tokenStorage){
        $this->tokenStorage = $tokenStorage;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('details')
            ->add('seats', ChoiceType::class, array(
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3'
                    ),
                'choice_attr' => array('class' => 'row'),
                'expanded' => true,
                'multiple' => true,
                'required' => true,
                'attr' => array('class' => '4u$')
                ))
            //->add('reprezentation')
            //->add('user')
            ->add('submit', SubmitType::class, array('label' => 'Submit'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RezervationBundle\Entity\Rezervation'
        ));
    }

    public function getName()
    {
       return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'rezervation_type';
    }
}

<?php

namespace RezervationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Doctrine\ORM\EntityManager;

class RezervationFormType extends AbstractType
{
    private $tokentStorage;
    private $em;

    public function __construct($tokentStorage,EntityManager $em)
    {
        $this->tokentStorage = $tokentStorage;
        $this->em = $em;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('details')
            ->add('reprezentation', EntityType::class, array(
                'class' => 'SpectateBundle:Reprezentation',
                // 'query_builder' => function (EntityRepository $er) {
                //         return $er->createQueryBuilder('s')
                //             ->orderBy('s.location', 'ASC');
                //     }
                'placeholder' => '------------'
                )
            )
            //->add('seats')
            //->add('user')
            ->add('submit', SubmitType::class, array('label' => 'Submit'))
        ;

        $reprezentationModifier = function (FormInterface $form, $reprezentation) {
            $er = $this->em->getRepository('SpectateBundle:Reprezentation');
            $seatNumber = $er->findOneById($reprezentation);
            if($seatNumber)
            {   
                $seatNumberArray = array();
                for($i=0; $i<$seatNumber->getNumberOfSeats(); $i++) $seatNumberArray[$i] = $i;

                if($reprezentation) {
                    $form->add('seats', ChoiceType::class,array(
                        'choices' => $seatNumberArray,
                        'expanded' => true,
                        'multiple' => true
                        ));
                } else {
                    $form->remove('seats');
                }
            }
        };

        // RevenueGen Modifier
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($reprezentationModifier) {
                $data = $event->getData();

                if(!$data) {
                    return null;
                }

                $reprezentationModifier($event->getForm(), $data->getReprezentation());
            }
        );

        $builder->get('reprezentation')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($reprezentationModifier) {
                $reprezentation = $event->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $reprezentationModifier($event->getForm()->getParent(), $reprezentation);
            }
        );
    }
    
    // /**
    //  * @param OptionsResolver $resolver
    //  */
    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setDefaults(array(
    //         //'data_class' => 'RezervationBundle\Entity\Rezervation'
    //     ));
    // }

    public function getName()
    {
       return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'rezervation_form_type';
    }
}

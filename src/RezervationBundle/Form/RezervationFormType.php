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
use SpectateBundle\Entity\Reprezentation;
use RezervationBundle\EventListener\SpectateListener;

class RezervationFormType extends AbstractType
{
    private $tokentStorage;
    private $em;
    private $check;

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
            ->add('spectate', EntityType::class, array(
                'class' => 'SpectateBundle:Spectate',
                'placeholder' => '-----------'
                ))
            // ->add('reprezentation', EntityType::class, array(
            //     'class' => 'SpectateBundle:Reprezentation',
            //     'placeholder' => '------------'
            //     )
            // )
            //->add('seats')
            //->add('user')
            ->add('submit', SubmitType::class, array('label' => 'Submit'))
        ;

        // $subscriber = new SpectateListener($builder->getFormFactory(), $this->em);
        // $builder->addEventSubscriber($subscriber);
     
        $spectateModifier = function (FormInterface $form, $spectate) use ($builder) {
            if($spectate) {
                $er = $this->em->getRepository('SpectateBundle:Reprezentation');
                $reprezentationFound = $er->findBySpectate($spectate);
                    $form->add('reprezentation', EntityType::class, array(
                                    'class' => 'SpectateBundle:Reprezentation',
                                    'choices' => $reprezentationFound,
                                    'placeholder' => '------------'
                                )
                            );
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

                        $builder->get('reprezentation')->addEventListener(
                            FormEvents::PRE_SUBMIT,
                            function (FormEvent $event) use ($reprezentationModifier) {

                            $reprezentation = $event->getData();
                            $reprezentationModifier($event->getForm()->getParent(), $reprezentation);
                            }
                        );
                } else {
                    $form->remove('reprezentation');
                }
        };


        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($spectateModifier) {
                $data = $event->getData();
                if(!$data) {
                    return null;
                }
                $spectateModifier($event->getForm()->getParent(), $data->getSpectate());
            }
        );

        $builder->get('spectate')->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($spectateModifier) {
                $spectate = $event->getData();
                $spectateModifier($event->getForm()->getParent(), $spectate);
            }
        );

        // $builder->addEventListener(
        //     FormEvents::PRE_SET_DATA,
        //     function (FormEvent $event) use ($spectateModifier) {
        //         $data = $event->getData();
        //         var_dump($data);
        //         if(!$data) {
        //             return null;
        //         }

        //         $reprezentationModifier($event->getForm(), $data->getReprezentation());
        //     }
        // );
        //$builder->remove('reprezentation');
        // $builder->addEventListener(
        //     FormEvents::PRE_SUBMIT,
        //     function (FormEvent $event) use ($reprezentationModifier) {

        //     $reprezentation = $event->getData();

        //     $reprezentationModifier($event->getForm(), $reprezentation);
        //     }
        // );


    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            //'data_class' => 'RezervationBundle\Entity\Rezervation'
            'int' => null,
        ));
    }

    public function getName()
    {
       return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'rezervation_form_type';
    }

    public function setCheck($check)
    {
        $this->check = $check;
    }
}

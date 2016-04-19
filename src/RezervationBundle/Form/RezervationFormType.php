<?php

namespace RezervationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityManager;

use SpectateBundle\Entity\Reprezentation;
use RezervationBundle\EventListener\SpectateListener;

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
            ->add('details', TextareaType::class, array('attr' => array('style' => 'resize:vertical;')))
            ->add('spectate', EntityType::class, array(
                    'class' => 'SpectateBundle:Spectate',
                    'placeholder' => '-----------',
                    'label' => 'Select Spectate'
                )
            )
            //->add('reprezentation', HiddenType::class, array())
            //->add('seats')
            //->add('user')
            ->add('submit', SubmitType::class, array('label' => 'Submit'))
        ;

        $spectateModifier = function (FormInterface $form, $spectate){
            
            if($spectate) {
                // $er = $this->em->getRepository('SpectateBundle:Reprezentation');
                // $reprezentationFound = $er->findBySpectate($spectate);
                    $form->add('reprezentation', EntityType::class, array(
                                'class' => 'SpectateBundle:Reprezentation',
                                'query_builder' => function($er) use ($spectate)
                                {
                                    return $er->createQueryBuilder('r')
                                              ->select('r')
                                              ->where('r.spectate = :spectate')
                                              ->setParameter('spectate', $spectate);
                                },
                                //'choices' => $reprezentationFound,
                                'placeholder' => '------------',
                                'label' => 'Select Representation'
                                )
                            );
                    return $spectate;
                } else {
                    $form->remove('reprezentation');return $spectate;
                }
        };


        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($spectateModifier) {
                $data = $event->getData();
                if(!$data) {
                    return null;
                }
                $spectateModifier($event->getForm()->getParent(), $data->getSpectate());
            }
        );

        $builder->get('spectate')->addEventListener(FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($spectateModifier) {
                $spectate = $event->getData();
                $spectateModifier($event->getForm()->getParent(), $spectate);
            }
        );


            $reprezentationModifier = function (FormInterface $form, $reprezentation) {
                $check = false;

                foreach ($reprezentation as $key => $value) {
                    if($key == 'reprezentation'){
                        $check = true;
                        break;
                    }
                }

                if($check){
                $er = $this->em->getRepository('SpectateBundle:Reprezentation');
                $seatNumber = $er->findOneById($reprezentation['reprezentation']);

     
                    if($seatNumber)
                    {   
                        $seatNumberArray = array();
                        for($i=0; $i<$seatNumber->getNumberOfSeats(); $i++) $seatNumberArray[$i] = $i+1;

                        if($reprezentation) {
                            $form->add('seats', ChoiceType::class,array(
                                       'choices' => $seatNumberArray,
                                       'expanded' => true,
                                       'multiple' => true,
                                       'label' => 'Select Seats',
                                    )
                            );

                        } else {
                                $form->remove('seats');
                        }
                    }
                }
            };

            $builder->addEventListener(FormEvents::POST_SET_DATA,
                function (FormEvent $event) use ($reprezentationModifier) {
                    $data = $event->getData();
                    if(!$data) { 
                        return null; 
                    }
                    $reprezentationModifier($event->getForm(), $data->getReprezentation());
                }
            );

            $builder->addEventListener(FormEvents::PRE_SUBMIT,
                function (FormEvent $event) use ($reprezentationModifier) {
                    $reprezentation = $event->getData();
                    $reprezentationModifier($event->getForm(), $reprezentation);
                }
            );


    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'RezervationBundle\Entity\Rezervation',
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
}

<?php

namespace RezervationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityManager;

use SpectateBundle\Entity\Reprezentation;
use RezervationBundle\EventListener\SpectateListener;

class RezervationFormType extends AbstractType
{
    private $tokentStorage;
    private $em;
    private $stack;

    public function __construct($tokentStorage,EntityManager $em, RequestStack $stack)
    {
        $this->tokentStorage = $tokentStorage;
        $this->em = $em;
        $this->stack = $stack;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $request = $this->stack->getCurrentRequest();

        $builder
            ->add('details', TextareaType::class, array('attr' => array('style' => 'resize:vertical;')))
            ->add('spectate', EntityType::class, array(
                    'class' => 'SpectateBundle:Spectate',
                    'placeholder' => '-----------',
                    'label' => 'Select Spectate'
                )
            )
            //->add('reprezentation')
            //->add('seats')
            //->add('user')
            ->add('submit', SubmitType::class, array('label' => 'Submit'))
        ;

        $spectateModifier = function (FormInterface $form, $spectate) {

            if($spectate) {
                $form->add('reprezentation', EntityType::class, array(
                            'class' => 'SpectateBundle:Reprezentation',
                            'query_builder' => 
                                function($er) use ($spectate)
                                {
                                    return $er->createQueryBuilder('r')
                                              ->select('r')
                                              ->where('r.spectate = :spectate')
                                              ->setParameter('spectate', $spectate);
                                },
                            'placeholder' => '------------',
                            'label' => 'Select Representation'
                            )
                        );
                return $spectate;
            } else {
                    $form->remove('reprezentation');
                    return $spectate;
                }
        };


        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($spectateModifier){
                $data = $event->getData();
                if(!$data) {
                    return null;
                }
                $spectateModifier($event->getForm()->getParent(), $data->getSpectate());
            }
        );

        $builder->get('spectate')->addEventListener(FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($spectateModifier, $request) {
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
                        for($i=0; $i<$seatNumber->getNumberOfSeats(); $i++) 
                            $seatNumberArray[$i] = $i+1;

                        if($reprezentation) {
                            $er = $this->em->getRepository('RezervationBundle:Rezervation');
                            $rezervations = $er->findByReprezentation($reprezentation['reprezentation']);

                            $form->add('seats', ChoiceType::class,array(
                                       'choices' => $seatNumberArray,
                                       'expanded' => true,
                                       'multiple' => true,
                                       'label' => 'Select Seats',
                                       'choice_attr' => $this->getOccupied($rezervations),
                                    )
                            );

                        } else {
                                $form->remove('seats');
                        }
                    }
                }
            };

            $builder->addEventListener(FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($reprezentationModifier) {
                    $data = $event->getData();
                    if(!$data) {
                        return null;
                    }
                    $reprezentationModifier($event->getForm(), $data->getReprezentation());
                }
            );

            $builder->addEventListener(FormEvents::PRE_SUBMIT,
                function (FormEvent $event) use ($reprezentationModifier, $request) {
                    $reprezentation = $event->getData();
                    $reprezentationModifier($event->getForm(), $reprezentation);
                }, 900
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

    public function getOccupied($objects)
    {
        $array = array();

        foreach ($objects as $object) 
        {
            $shit = $object->getSeats();

            for($i=0;$i<sizeof($shit);$i++) 
            {
                $array[] = (int)$shit[$i] - 1;
            }
        }

        $array = array_flip($array);

        foreach ($array as $key => $value) 
        {
            $array[$key] = array('checked' => true, 'disabled' => true);
        }

        return $array;   
    }
}

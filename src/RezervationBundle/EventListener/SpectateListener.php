<?php
namespace RezervationBundle\EventListener;

use Symfony\Component\Form\Event\DataEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;

class SpectateListener implements EventSubscriberInterface
{
    private $factory;
    private $em;

    public function __construct(FormFactoryInterface $factory, $em)
    {
        $this->factory = $factory;
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that we want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData',
                     FormEvents::POST_SUBMIT => 'postSubmit');
    }

    public function preSetData($event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        
        if (!$data) {
            return null;
        }
        $spectate = $data->getSpectate();
        if($spectate) {
            $er = $this->em->getRepository('SpectateBundle:Reprezentation');
            $reprezentationFound = $er->findBySpectate($spectate);

            $form->add('reprezentation', EntityType::class, array(
                       'class' => 'SpectateBundle:Reprezentation',
                       'choices' => $reprezentationFound,
                       'placeholder' => '------------'
                    )
            );
        } else {
            $form->remove('reprezentation');
        }
    }

    public function postSubmit($event)
    {
        $spectate = $event->getData();
        $form = $event->getForm();

        if($spectate) {
            $er = $this->em->getRepository('SpectateBundle:Reprezentation');
            $reprezentationFound = $er->findBySpectate($spectate);

            $form->add('reprezentation', EntityType::class, array(
                       'class' => 'SpectateBundle:Reprezentation',
                       'choices' => $reprezentationFound,
                       'placeholder' => '------------'
                    )
            );
        } else {
            $form->remove('reprezentation');
        }
    }

    public function spectateModifier($form, $spectate)
    {
        if($spectate) {
            $er = $this->em->getRepository('SpectateBundle:Reprezentation');
            $reprezentationFound = $er->findBySpectate($spectate);

            $form->add('reprezentation', EntityType::class, array(
                       'class' => 'SpectateBundle:Reprezentation',
                       'choices' => $reprezentationFound,
                       'placeholder' => '------------'
                    )
            );
        } else {
            $form->remove('reprezentation');
        }
    }

    public function getName()
    {
       return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'spectate_listener';
    }
}
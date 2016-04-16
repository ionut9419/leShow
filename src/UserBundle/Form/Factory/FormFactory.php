<?php

namespace UserBundle\Form\Factory;

use FOS\UserBundle\Form\Factory\FactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class FormFactory implements FactoryInterface
{
    protected $name;
    protected $formFactory;
    protected $type;
    protected $validationGroups;

    public function __construct(FormFactoryInterface $formFactory, $name, $type, array $validationGroups = null)
    {
        $this->formFactory = $formFactory;
        $this->name = $name;
        $this->type = $type;
        $this->validationGroups = $validationGroups;
    }

    public function createForm(array $options = array())
    {
        //$options = array_merge(array('validation_groups' => $this->validationGroups), $options);

        return $this->formFactory->createNamed($this->name, $this->type, null, $options);
    }

    public function setType($type)
    {
        $this->type = $type;
    }
}

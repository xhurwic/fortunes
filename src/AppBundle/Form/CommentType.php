<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class CommentType extends AbstractType
{
    public function getName()
    {
        return "Comment";
    }

    public function setDefaultsOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults(array(
            'data_class'=> 'AppBundle\Entity\Comment'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add('autor')
            ->add('content')
            ->add('submit', 'submit');
    }
}
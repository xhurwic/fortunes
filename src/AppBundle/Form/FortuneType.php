<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FortuneType extends AbstractType
{
    public function getName()
    {
        return "Fortune";
    }

    public function setDefaultsOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults(array(
            'data_class'=> 'AppBundle\Entity\Fortune'
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $option
     */
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add('title')
            ->add('author')
            ->add('content')
            ->add('submit', 'submit');
    }
}
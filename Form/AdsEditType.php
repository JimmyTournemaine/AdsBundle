<?php

namespace JT\AdsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdsEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
            	'label' => 'Titre',
            	'disabled' => true,
            ))
            ->add('content', 'textarea', array(
            	'label' => 'Description'
            ))
            ->add('price', null, array(
            	"widget_addon_prepend"=> array(
            		"icon" => "euro"
            	)
        	))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JT\AdsBundle\Entity\Ads'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jt_adsbundle_ads';
    }
}

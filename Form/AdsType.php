<?php

namespace JT\AdsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
            	'label' => 'Titre'
            ))
            ->add('content', 'textarea', array(
            	'label' => 'Description'
            ))
            ->add('price', null, array(
            	"widget_addon_prepend"=> array(
            		"icon" => "euro"
            	)
        	))
            ->add('images', 'collection', array(
            	'type' => new ImageType(),
            	'allow_add' => true,
            	'allow_delete' => true,
            	'prototype' => true,
            	'help_block' => "La première image sera  l'image principale de l'annonce",
            	'widget_add_btn' => array('label' => ""),
            	'widget_remove_btn' => array('label' => "Supprimer"),
            	'options' => array(
            		'label_render' => false,
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

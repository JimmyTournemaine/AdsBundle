<?php

namespace JT\AdsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;

class AdsSearchType extends AbstractType
{
	
	const MIN_PRICE = 0;
	const MAX_PRICE = 1000;
	const STEP_PRICE = 50;
	
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$prices = array();
    	
    	foreach(range(self::MIN_PRICE, self::MAX_PRICE , self::STEP_PRICE) as $n)
    		$prices[$n] = $n;
    	
        $builder
            ->add('keywords', 'text', array(
            		'label' => 'Recherche',
            		'required' => false,
            		'constraints' => new Length(array('min' => 3)),
            		'attr' => array(
            			'placeholder' => 'Mots-Clés',
            		),
            ))
            ->add('minPrice', 'choice', array(
            		'label' => false,
            		'choices' => $prices,
            		'empty_value' => 'Prix Minimum',
            ))
            ->add('maxPrice', 'choice', array(
            		'label' => false,
            		'choices' => $prices,
            		'empty_value' => 'Prix Maximum',
            ))
            ->add('sortBy', 'choice', array(
            		'choices' => array('Nouveautés', 'Prix croissant', 'Prix décroissant'),
            ))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jt_adsbundle_ads_research';
    }
}

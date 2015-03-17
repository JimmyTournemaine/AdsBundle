<?php

namespace JT\AdsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

use JT\AdsBundle\Entity\Ads;
use JT\AdsBundle\Entity\Image;
use JT\AdsBundle\Entity\Contact;
use JT\AdsBundle\Entity\Research;
use JT\AdsBundle\Form\AdsType;
use JT\AdsBundle\Form\AdsEditType;
use JT\AdsBundle\Form\AdsSearchType;
use JT\AdsBundle\Form\ContactType;

/**
 * Ads controller.
 *
 */
class AdsController extends Controller
{

    /**
     * Lists all Ads entities.
     * @method GET
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('JTAdsBundle:Ads')->findAll();

        $form = $this->createResearchForm();
        
        return $this->render('JTAdsBundle:Ads:index.html.twig', array(
            'entities' 	=> $entities,
        	'research_form'	=> $form->createView(),
        ));
    }
    
    /**
     * Creates a new Ads entity.
     * @method POST
     */
    public function createAction(Request $request)
    {
    	/* Any security verification cause of self::newAction() do it
    	 * and here is the POST version
    	 */
    	
    	/* Form creating and handling */
        $entity = new Ads();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        /* Validation */
        if ($form->isValid()) {
        	
        	$entity->setSeller($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            /* Add ACEs */
            $aclProvider = $this->get('security.acl.provider');
            $securityContext = $this->get('security.context');
            $acl = $aclProvider->createAcl(ObjectIdentity::fromDomainObject($entity));
            $securityIdentity = UserSecurityIdentity::fromAccount($securityContext->getToken()->getUser());

            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER); // Give OWNER rights
            $aclProvider->updateAcl($acl);

            /* Redirection */
            $this->get('session')->getFlashBag()->add('success', 'Votre annonce à bien été ajoutée !');
            return $this->redirect($this->generateUrl('ads_show', array('slug' => $entity->getSlug())));
        }
		
        /* Rendering page with form */
        return $this->render('JTAdsBundle:Ads:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Ads entity.
     *
     * @param Ads $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Ads $entity)
    {
        $form = $this->createForm(new AdsType(), $entity, array(
            'action' => $this->generateUrl('ads_create'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Créer'));
        
        return $form;
    }

    /**
     * Displays a form to create a new Ads entity.
     */
    public function newAction()
    {
    	/* Access denied for an anonymous user */
    	$securityContext = $this->get('security.context');
    	if(false === $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')){
    		$this->get('session')->getFlashbag()->add('warning', 'Vous devez être authentifiés pour déposer une annonce.');
    		throw new AccessDeniedException();
    	}
    	
    	/* Form creating */
        $entity = new Ads();
        $form   = $this->createCreateForm($entity);

        /* Render page */
        return $this->render('JTAdsBundle:Ads:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Ads entity.
     */
    public function showAction(Ads $entity)
    {
        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('JTAdsBundle:Ads:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Ads entity.
     * @method GET
     */
    public function editAction(Ads $entity)
    {
    	/* Only Moderators & owner can edit an Ads */
    	$securityContext = $this->get('security.context');
    	if (false === $securityContext->isGranted('EDIT', $entity) && false === $securityContext->isGranted('ROLE_ADS_MODERATOR'))
    	{
    		throw new AccessDeniedException();
    	}
    	
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($entity);

        return $this->render('JTAdsBundle:Ads:edit.html.twig', array(
            'entity'      	=> $entity,
            'edit_form'   	=> $editForm->createView(),
            'delete_form' 	=> $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Ads entity.
    *
    * @param Ads $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Ads $entity)
    {
        $form = $this->createForm(new AdsEditType(), $entity, array(
            'action' => $this->generateUrl('ads_update', array('slug' => $entity->getSlug())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Mettre à jour','attr'=>['class'=>'center-block btn-primary']));

        return $form;
    }
    
    /**
     * Edits an existing Ads entity.
     */
    public function updateAction(Request $request, Ads $entity)
    {
        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
        	$em->flush();

        	$this->get('session')->getFlashBag()->add('success', 'Votre annonce à bien été modifiée !');
            return $this->redirect($this->generateUrl('ads_edit', array('slug' => $entity->getSlug())));
        }

        return $this->render('JTAdsBundle:Ads:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        	'add_images_form' => $imagesForm->createView(),	
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
   
    /**
     * Deletes a Ads entity.
     *
     */
    public function deleteAction(Request $request, Ads $entity)
    {
    	$securityContext = $this->get('security.context');
    	if (false === $securityContext->isGranted('DELETE', $entity) && false === $securityContext->isGranted('ROLE_ADS_MODERATOR'))
    	{
    		throw new AccessDeniedException();
    	}
    	
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ads'));
    }

    /**
     * Creates a form to delete a Ads entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ads $entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ads_delete', array('slug' => $entity->getSlug())))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array(
            		'label' => 'Supprimer',
            		'attr' => ['class' => 'btn-danger']
            ))
            ->getForm()
        ;
    }
    
    /**
     * Render a contact guess page
     * @param Ads $entity
     */
    public function contactAction(Ads $entity){
    	
    	$em = $this->getDoctrine()->getManager();
    	$contact = new Contact();
    	
    	$contactForm = $this->createContactForm($contact, $entity);
    	
    	return $this->render('JTAdsBundle:Ads:contact.html.twig', array(
    			'entity'    => $entity,
    			'form'   	=> $contactForm->createView(),
    	));
    }
    
    /**
    * Creates a form to contact a seller
    *
    * @param Ads $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createContactForm(Contact $entity, Ads $ads)
    {
        $form = $this->createForm(new ContactType(), $entity, array(
            'action' => $this->generateUrl('ads_send', array('slug' => $ads->getSlug())),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Envoyer !','attr'=>['class'=>'center-block btn-primary']));

        return $form;
    }
    
    /**
     * Send a mail to contact the seller of the Ads
     */
    public function sendAction(Request $request, Ads $entity)
    {
    	$em = $this->getDoctrine()->getManager();
    	$contact = new Contact();
    	
    	$contactForm = $this->createContactForm($contact, $entity);
    	$contactForm->handleRequest($request);
    
    	if ($contactForm->isValid()) {
    		
    		if($this->get('jt.ads.mailer')->sendSellerContactMessage($contact, $entity)){
    			$request->getSession()->getFlashBag()->add('info', 'Votre message à été envoyé !');
    		} else {
    			$request->getSession()->getFlashBag()->add('danger', 'Une erreur est survenue !');
    		}
    		    		
    		return $this->redirect($this->generateUrl('ads_show', array('slug' => $entity->getSlug())));
    	}
    
    	return $this->render('JTAdsBundle:Ads:contact.html.twig', array(
    			'entity'    => $entity,
    			'form'   	=> $contactForm->createView(),
    	));
    }
    
    /**
     * Create a form to search an ads
     */
    private function createResearchForm(){
    	
    	$form = $this->createForm(new AdsSearchType(), null, array(
    			'action' => $this->generateUrl('ads_research'),
    			'method' => 'POST',
    	));
    	
    	return $form;
    }
    
    /**
     * Search some ads
     * @param Request $request
     */
    public function researchAction(Request $request)
    {
    	$search_form= $this->createResearchForm();
    	
    	if($search_form->handleRequest($request)->isValid())
    	{
    		$ads = $this
    			->getDoctrine()
    			->getManager()
    			->getRepository('JTAdsBundle:Ads')
    			->findByParameters($search_form->getData());
    	} else {
    		$ads = null;
    	}
    	
    	return $this->render('JTAdsBundle:Ads:research.html.twig', array(
    			'ads' 			=> $ads,
    			'research_form'	=> $search_form->createView(),
    	));
    }
}

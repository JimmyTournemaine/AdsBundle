<?php

namespace JT\AdsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

use JT\AdsBundle\Entity\Ads;
use JT\AdsBundle\Entity\Issue;
use JT\AdsBundle\Form\IssueType;
use JT\AdsBundle\Form\IssueEditType;

/**
 * Issue controller.
 *
 */
class IssueController extends Controller
{
	/**
	 * Finds and displays a Answer entity.
	 * @ParamConverter("ads", options={"mapping": {"ads_slug": "slug"}})
	 * @ParamConverter("entity", options={"mapping": {"id": "id"}})
	 */
	public function showAction(Ads $ads, Issue $entity)
	{
		return $this->render('JTAdsBundle:Issue:show.html.twig', array(
				'issue' 		=> $entity,
				'delete_form'	=> $this->createDeleteForm($entity, $ads)->createView(),
		));
	}

    /**
     * Creates a new Issue entity.
     * 
     * @ParamConverter("ads", options={"mapping": {"ads_slug": "slug"}})
     */
    public function createAction(Request $request, Ads $ads)
    {
    	/* Anonymous cannot post an issue */
    	$securityContext = $this->get('security.context');
    	if (false === $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED'))
    	{
    		throw new AccessDeniedException();
    	}
    	
        $entity = new Issue();
        $form = $this->createCreateForm($entity, $ads);
        $form->handleRequest($request);

        if ($form->isValid()) {
        	
        	$entity->setQuestioner($this->getUser());
        	$ads->addIssue($entity);
        	
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            /* Mail notification */
            $this->get('jt.ads.mailer')->sendIssueNotificationMessage($entity, $ads);
            
            /* Add ACEs */
            $aclProvider = $this->get('security.acl.provider');
            $securityContext = $this->get('security.context');
            $acl = $aclProvider->createAcl(ObjectIdentity::fromDomainObject($entity));
            $securityIdentity = UserSecurityIdentity::fromAccount($securityContext->getToken()->getUser());
            
            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER); // Give OWNER rights
            $aclProvider->updateAcl($acl);

            $this->get('session')->getFlashbag()->add('success', 'Votre question à bien été posée à l\'annonceur.');
            return $this->redirect($this->generateUrl('ads_show', array('slug' => $ads->getSlug())));
        }

        return $this->render('JTAdsBundle:Issue:new.html.twig', array(
            'entity' => $entity,
        	'ads'	 => $ads,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Issue entity.
     *
     * @param Issue $entity The entity
     * @return \Symfony\Component\Form\Form The form
     * 
     */
    private function createCreateForm(Issue $entity, Ads $ads)
    {
        $form = $this->createForm(new IssueType(), $entity, array(
            'action' => $this->generateUrl('issue_create', ['ads_slug' => $ads->getSlug()]),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Issue entity.
     * @ParamConverter("ads", options={"mapping": {"ads_slug": "slug"}})
     */
    public function newAction(Ads $ads)
    {
    	/* Only authenticated users can ask a question */
    	$securityContext = $this->get('security.context');
    	if (false === $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED'))
    	{
    		throw new AccessDeniedException();
    	}
    	
        $entity = new Issue();
        $form   = $this->createCreateForm($entity, $ads);

        return $this->render('JTAdsBundle:Issue:new.html.twig', array(
            'entity' => $entity,
        	'ads' => $ads,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Issue entity.
     * @ParamConverter("ads", options={"mapping": {"ads_slug": "slug"}})
     * @ParamConverter("entity", options={"mapping": {"id": "id"}})
     */
    public function editAction(Ads $ads, Issue $entity)
    {
    	/* Only issue owner can edit it */
    	$securityContext = $this->get('security.context');
    	if (false === $securityContext->isGranted('EDIT', $entity))
    	{
    		throw new AccessDeniedException();
    	}

        $editForm = $this->createEditForm($entity, $ads);
        $deleteForm = $this->createDeleteForm($entity, $ads);

        return $this->render('JTAdsBundle:Issue:edit.html.twig', array(
            'entity'      => $entity,
        	'ads'		  => $ads,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Issue entity.
    *
    * @param Issue $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Issue $entity, Ads $ads)
    {
        $form = $this->createForm(new IssueEditType(), $entity, array(
            'action' => $this->generateUrl('issue_update', array(
            		'ads_slug' 	=> $ads->getSlug(),
            		'id' 		=> $entity->getId(),
            )),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Issue entity.
	 *
     * @ParamConverter("ads", options={"mapping": {"ads_slug": "slug"}})
     * @ParamConverter("entity", options={"mapping": {"id": "id"}})
     */
    public function updateAction(Request $request, Ads $ads, Issue $entity)
    {
        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($entity, $ads);
        $editForm = $this->createEditForm($entity, $ads);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashbag()->add('info', 'Votre question à bien été modifiée.');
            return $this->redirect($this->generateUrl('ads_show', array('slug' => $ads->getSlug())));
        }

        return $this->render('JTAdsBundle:Issue:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Issue entity.
     * @ParamConverter("ads", options={"mapping": {"ads_slug": "slug"}})
     * @ParamConverter("entity", options={"mapping": {"id": "id"}})
     */
    public function deleteAction(Request $request, Ads $ads, Issue $entity)
    {
    	/* Security */
    	$securityContext = $this->get('security.context');
    	if(false === $securityContext->isGranted('ROLE_ADS_MODERATOR')){
    		throw new AccessDeniedException;
    	}
    	
    	/* Removing */
        $form = $this->createDeleteForm($entity, $ads);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ads_show', array('slug' => $ads->getSlug())));
    }

    /**
     * Creates a form to delete a Issue entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Issue $entity, Ads $ads)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('issue_delete', array('ads_slug' => $ads->getSlug(), 'id' => $entity->getId())))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer'))
            ->getForm()
        ;
    }
}

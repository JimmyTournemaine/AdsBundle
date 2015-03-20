<?php

namespace JT\AdsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use JT\AdsBundle\Entity\Ads;
use JT\AdsBundle\Entity\Answer;
use JT\AdsBundle\Entity\Issue;
use JT\AdsBundle\Form\Type\AnswerType;

/**
 * Answer controller.
 *
 */
class AnswerController extends Controller
{

    /**
     * Lists all Answer entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JTAdsBundle:Answer')->findAll();

        return $this->render('JTAdsBundle:Answer:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    
    /**
     * Creates a new Answer entity.
     * @ParamConverter("issue", options={"mapping": {"issue_id": "id"}})
     * @ParamConverter("ads", options={"mapping": {"ads_slug": "slug"}})
     */
    public function createAction(Request $request, Issue $issue, Ads $ads)
    {
    	// Security is in the GET method
    	
        $entity = new Answer();
        $form = $this->createCreateForm($entity, $issue, $ads);
        $form->handleRequest($request);

        if ($form->isValid()) {
        	
        	$issue->setAnswer($entity);
        	
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            /* Mail Notification */
            $this->get('jt.ads.mailer')->sendAnswerNotificationMessage($entity, $issue, $ads);
            
            return $this->redirect($this->generateUrl('ads_show', array('slug' => $ads->getSlug())));
        }

        return $this->render('JTAdsBundle:Answer:new.html.twig', array(
            'entity' => $entity,
        	'issue'  => $issue,
        	'ads'	 => $ads,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Answer entity.
     *
     * @param Answer $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Answer $entity, Issue $issue, Ads $ads)
    {
        $form = $this->createForm(new AnswerType(), $entity, array(
            'action' => $this->generateUrl('answer_create', array(
            		'ads_slug' 	=> $ads->getslug(), 
            		'issue_id'	=> $issue->getId()
            )),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Answer entity.
     * @ParamConverter("issue", options={"mapping": {"issue_id": "id"}})
     * @ParamConverter("ads", options={"mapping": {"ads_slug": "slug"}})
     */
    public function newAction(Issue $issue, Ads $ads)
    {
    	$securityContext = $this->get('security.context');
    	if (false === $securityContext->isGranted('OWNER', $ads))
    	{
    		throw new AccessDeniedException();
    	}
    	
        $entity = new Answer();
        $form   = $this->createCreateForm($entity, $issue, $ads);

        return $this->render('JTAdsBundle:Answer:new.html.twig', array(
            'entity' => $entity,
        	'issue'	 => $issue,
        	'ads'	 => $ads,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Answer entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JTAdsBundle:Answer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Answer entity.');
        }

        return $this->render('JTAdsBundle:Answer:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing Answer entity.
     */
    public function editAction(Answer $entity)
    {
        $editForm = $this->createEditForm($entity);

        return $this->render('JTAdsBundle:Answer:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Answer entity.
    *
    * @param Answer $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Answer $entity)
    {
        $form = $this->createForm(new AnswerType(), $entity, array(
            'action' => $this->generateUrl('answer_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Answer entity.
     */
    public function updateAction(Request $request, Answer $entity)
    {
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
        	$em->flush();

        	$this->get('session')->getFlashbag()->add('info', 'Votre réponse à bien été modifiée');
            return $this->redirect($this->generateUrl('ads_show', array('slug' => $entity->getIssue()->getAds()->getSlug())));
        }

        return $this->render('JTAdsBundle:Answer:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Answer entity.
     *
     */
    public function deleteAction(Request $request, Answer $entity)
    {
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('answer'));
    }

    /**
     * Creates a form to delete a Answer entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Answer $entity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('answer_delete', array('id' => $entity->getId())))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer !'))
            ->getForm()
        ;
    }
}

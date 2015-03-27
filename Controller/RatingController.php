<?php

namespace JT\AdsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use JT\AdsBundle\Entity\Vote;
use JT\UserBundle\Entity\User;
use JT\AdsBundle\Form\Type\VoteType;

class RatingController extends Controller
{
	/**
	 * Rate a seller
	 * @param Request $request
	 * @param User $user the user who's rating
	 * @throws AccessDeniedException Only not-anonymous users can rate a seller
	 * @throws \Exception In case of a user is not a seller
	 */
    public function voteAction(Request $request, User $user)
    {
    	$em = $this->getDoctrine()->getManager();
    	$ratingRepo = $em->getRepository('JTAdsBundle:Rating');
    	$rating = $ratingRepo->findOneByUser($user);
    	
    	/* Security */
    	if(false === $this->get('security.context')->isGranted('ROLE_USER')){
    		$this->get('session')->getFlashBag()->add('warning', 'Vous devez être authentifiés pour noter un utilisateur');
    		throw new AccessDeniedException();
    	}
    	if($rating == null){
    		throw new \Exception('Cet utilisateur n\'est pas un vendeur.');
    	}
    	
    	
    	$entity = new Vote();
    	$vote_form = $this->createVoteForm($entity, $user);
    	$vote_form->handleRequest($request);
    	
    	if ($vote_form->isValid()) {
    		
    		$entity->addUser($this->getUser());
    		$entity->setRating($rating);
    		$em->persist($entity);
    		$em->flush();
    		
    		
    		$rating->setRatio($em->getRepository("JTAdsBundle:Vote")->getNotesAverage($user));
    		$em->flush();
    		
    		$this->get('session')->getFlashBag()->add('info', 'Votre avis à été pris en considération. Merci !');
    		return $this->redirect($this->generateUrl('fos_user_profile_show'));
    	}
    	
    	
        return $this->render('JTAdsBundle:Rating:vote.html.twig', array(
        		'user'		=> $user,
                'vote_form' => $vote_form->createView(),
            ));    
    }
    
    /**
     * Create the form to vote
     * @param Vote $entity
     * @param User $user the seller
     * @return Form the generated form
     */
    private function createVoteForm(Vote $entity, User $user){
    	$form = $this->createForm(new VoteType(), $entity, array(
    			'action' => $this->generateUrl('ads_vote', ['username' => $user->getUsername()]),
    			'method' => 'POST',
    	));
    	$form->add('submit', 'submit', array('label' => 'Voter'));
    	
    	return $form;
    }
    
    /**
     * You can now rate this user (called for first deposit ad) 
     * @param User $user
     */
    public function nowVotable(User $user){
    	$rating = new Rating();
    	$rating->setUser($user);
    	$em = $this->getDoctrine()->getManager();
    	$em->persist($rating);
    	$em->flush();
    }
    
    
    /**
     * Show the user-rating and all votes
     * @param User $user
     */
    public function showAction(User $user){
    	
    	$em = $this->getDoctrine()->getManager();
    	$rating = $em->getRepository('JTAdsBundle:Rating')->findOneByUser($user);
    	$votes = $em->getRepository('JTAdsBundle:Vote')->findByRating($rating);
    	
    	return $this->render('JTAdsBundle:Rating:show.html.twig', array(
        		'user'		=> $user,
    			'rating'	=> $rating,
                'votes'		=> $votes,
            ));  
    }

}

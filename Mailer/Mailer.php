<?php 

namespace JT\AdsBundle\Mailer;

use JT\MainBundle\Mailer\Mailer as BaseMailer;

class Mailer extends BaseMailer {
	
	/**
	 * Send a mail for an ads from the interested person to the seller
	 */
	public function sendSellerContactMessage(\JT\AdsBundle\Entity\Contact $contact, \JT\AdsBundle\Entity\Ads $ads){
	
		$subject = "Votre annonce : " . $ads->getTitle();
		$template = 'JTAdsBundle:Mail:contactSeller.html.twig';
		$to = $ads->getSeller()->getEmail();
		$body = $this->templating->render($template, array(
				'contact' => $contact,
				'ads'	  => $ads
		));
	
		return ($this->sendMessage($to, $subject, $body)) ? true : false;
	}
	
	/**
	 * Send a message to the seller to inform him of a new issue about his ads.
	 * @param \JT\AdsBundle\Entity\Issue $issue
	 * @param \JT\AdsBundle\Entity\Ads $ads
	 * @return boolean if sent or not
	 */
	public function sendIssueNotificationMessage(\JT\AdsBundle\Entity\Issue $issue, \JT\AdsBundle\Entity\Ads $ads){
		
		$subject = "Nouvelle question publique !";
		$template = 'JTAdsBundle:Mail:issueNotification.html.twig';
		$to = $ads->getSeller()->getEmail();
		$body = $this->templating->render($template, array(
				'ads' 	=> $ads,
				'issue' => $issue,
		));
		
		return ($this->sendMessage($to, $subject, $body)) ? true : false;
	}
	
	/**
	 * Send a message to the seller to inform him of a new issue about his ads.
	 * @param \JT\AdsBundle\Entity\Issue $issue
	 * @param \JT\AdsBundle\Entity\Ads $ads
	 * @return boolean if sent or not
	 */
	public function sendAnswerNotificationMessage(\JT\AdsBundle\Entity\Answer $answer, \JT\AdsBundle\Entity\Issue $issue, \JT\AdsBundle\Entity\Ads $ads){
	
		$subject = "Nouvelle réponse à votre question !";
		$template = 'JTAdsBundle:Mail:answerNotification.html.twig';
		$to = $ads->getSeller()->getEmail();
		$body = $this->templating->render($template, array(
				'answer'=> $answer,
				'ads' 	=> $ads,
				'issue' => $issue,
		));
	
		return ($this->sendMessage($to, $subject, $body)) ? true : false;
	}
}
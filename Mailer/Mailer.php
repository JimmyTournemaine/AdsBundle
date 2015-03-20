<?php 

namespace JT\AdsBundle\Mailer;

use Symfony\Component\Templating\EngineInterface;

class Mailer {
	
	protected $mailer;
	protected $templating;
	
	private $from = "noreply@tournemaine.fr";
	private $reply = "services@tournemaine.fr";
	private $name = "Jimmy Tournemaine Website";
	
	public function __construct($mailer, EngineInterface $templating){
		$this->mailer = $mailer;
		$this->templating = $templating;
	}
	
	protected function sendMessage($to, $subject, $body) {
	
		$mail = \Swift_Message::newInstance();
		$mail
		->setFrom($this->from, $this->name)
		->setTo($to)
		->setSubject($subject)
		->setBody($body)
		->setReplyTo($this->reply, $this->name)
		->setContentType('text/html');
	
		return ($this->mailer->send($mail)) ? true : false;
	}
	
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

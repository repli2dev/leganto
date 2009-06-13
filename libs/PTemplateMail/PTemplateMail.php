<?php
/**
 * Nette Framework
 *
 * Copyright (c) 2008, 2009 Patrik Votoček (http://www.vrtak-cz.net)
 *
 * This source file is subject to the New-BSD licence.
 *
 * For more information please see http://nettephp.com
 *
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @license    New-BSD
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette\Mail
 * @version    $Id: PTemplateMail.php 3 2009-06-02 20:56:43Z patrik@votocek.cz $
 */

/*namespace Nette\Mail;*/

/**
 * Mail provides functionality to compose and send both text and MIME-compliant multipart e-mail messages with nette template.
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2008, 2009 Patrik Votoček
 * @package    Nette\Mail
 * @property   ITemplate $template
 * @property   string $from
 * @property   string $subject
 * @property   string $returnPath
 * @property   int $priority
 * @property   string $htmlBody
 */
class PTemplateMail extends /*Nette\Mail\*/Mail
{
	/** @var ITemplate */
	protected $template = NULL;
	
	/**
	 * Set template
	 * 
	 * @param ITemplate $template
	 * @param string $file
	 * @return PTemplateMail
	 */
	public function setTemplate(ITemplate $template, $file = NULL)
	{
		$this->template = $template;
		if (!empty($file))
			$this->template->setFile($file);
		
		//Fluent
		return $this;
	}
	
	/**
	 * Get template
	 * 
	 * @return ITemplate
	 */
	public function getTemplate()
	{
		return $this->template;
	}
	
	/*********************** Refactoring *************************/
	
	/**
	 * Constructor
	 * 
	 * @param ITemplate $template
	 * @param string $file
	 */
	public function __construct(ITemplate $template = NULL, $file = NULL)
	{
		parent::__construct();
		
		if (!empty($template))
		{
			$this->setTemplate($template, $file);
		}
		
		// Load default config
		$config = Environment::getConfig("mail");
		if (!empty($config) && isset($config->from) && !empty($config->from))
		{
			if (isset($config->fromname) && !empty($config->fromname))
				$this->setFrom($config->form, $config->fromname);
			else
				$this->setFrom($config->form);
		}
	}
	
	/**
	 * Builds e-mail.
	 * @return PTemplateMail
	 */
	protected function build()
	{
		$html = (string) $this->template;
		
		$this->setHtmlBody($html);
		
		return parent::build();
	}
	
	/*********************** Adding Fluent *************************/
	
	/**
	 * Adds new multipart.
	 * @param  MailMimePart
	 * @return PTemplateMail
	 */
	public function addPart(MailMimePart $part)
	{
		parent::addPart($part);
		
		//Fluent
		return $this;
	}
		
	/**
	 * Adds email recipient.
	 * @param  string  e-mail or format "John Doe" <doe@example.com>
	 * @param  string
	 * @return PTemplateMail
	 */
	public function addTo($email, $name = NULL) // addRecipient()
	{
		parent::addTo($email, $name);
		
		//Fluent
		return $this;
	}

	/**
	 * Adds carbon copy email recipient.
	 * @param  string  e-mail or format "John Doe" <doe@example.com>
	 * @param  string
	 * @return PTemplateMail
	 */
	public function addCc($email, $name = NULL)
	{
		parent::addCc($email, $name);
		
		//Fluent
		return $this;
	}

	/**
	 * Adds blind carbon copy email recipient.
	 * @param  string  e-mail or format "John Doe" <doe@example.com>
	 * @param  string
	 * @return PTemplateMail
	 */
	public function addBcc($email, $name = NULL)
	{
		parent::addBcc($email, $name);
		
		//Fluent
		return $this;
	}
}
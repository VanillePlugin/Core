<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.1
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

class Mailer
{
	/**
	 * @access private
	 * @var mixed $email
	 * @var string $subject
	 * @var string $content
	 * @var array $headers
	 * @var array $attachments
	 */
	private $email = [];
	private $subject;
	private $content;
	private $headers = [];
	private $attachments = [];

	/**
	 * @param mixed $email
	 */
	public function __construct($email = null)
	{
		if ( !$email ) {
			$email = get_bloginfo('admin_email');
		}
		$this->email = $email;
	}

	/**
	 * Add email adress.
	 * 
	 * @access public
	 * @param string $email
	 * @return object
	 */
	public function addEmail($email)
	{
		$this->email[] = $email;
		return $this;
	}

	/**
	 * Set email content.
	 * 
	 * @access public
	 * @param array $content
	 * @return object
	 */
	public function setContent($content)
	{
		$this->content = "{$content} \n";
		return $this;
	}

	/**
	 * Add email content.
	 * 
	 * @access public
	 * @param string $content
	 * @return object
	 */
	public function addContent($content)
	{
		$this->content .= "{$content} \n";
		return $this;
	}

	/**
	 * Set email subject.
	 * 
	 * @access public
	 * @param string $subject
	 * @return object
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;
		return $this;
	}

	/**
	 * Set email headers.
	 * 
	 * @access public
	 * @param array $headers
	 * @return object
	 */
	public function setheaders($headers = [])
	{
		$this->headers = $headers;
		return $this;
	}

	/**
	 * Add email headers.
	 * 
	 * @access public
	 * @param string $header
	 * @return object
	 */
	public function addHeaders($header)
	{
		$this->headers[] = $header;
		return $this;
	}

	/**
	 * Set email attachments.
	 * 
	 * @access public
	 * @param array $attachments
	 * @return object
	 */
	public function setAttachments($attachments = [])
	{
		$this->attachments = $attachments;
		return $this;
	}

	/**
	 * Add email attachments.
	 * 
	 * @access public
	 * @param string $attachments
	 * @return object
	 */
	public function addAttachments($attachment)
	{
		$this->attachments[] = $attachment;
		return $this;
	}

	/**
	 * Format email as HTML.
	 * 
	 * @access public
	 * @param void
	 * @return object
	 */
	public function asHTML()
	{
		$this->addHeaders('Content-Type: text/html; charset=UTF-8');
		return $this;
	}

	/**
	 * Send email.
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 */
	public function send()
	{
		if ( TypeCheck::isFunction('wp_mail') ) {
			return wp_mail(
				$this->email,
				$this->subject,
				$this->content,
				$this->headers,
				$this->attachments
			);
		}
		return false;
	}
}

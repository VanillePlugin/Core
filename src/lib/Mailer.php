<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.3.3
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

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
	 * @param mixed $email null
	 * @return void
	 */
	public function __construct($email = null)
	{
		$this->email = isset($email) ? $email : get_bloginfo('admin_email');
	}

	/**
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
	 * @access public
	 * @param array $headers
	 * @return object
	 */
	public function addHeaders($headers = [])
	{
		$this->headers[] = $headers;
		return $this;
	}

	/**
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
	 * @access public
	 * @param array $attachments
	 * @return object
	 */
	public function addAttachments($attachments = [])
	{
		$this->attachments[] = $attachments;
		return $this;
	}

	/**
	 * @access public
	 * @param void
	 * @return boolean
	 */
	public function send()
	{
		if ( function_exists('wp_mail') ) {
			return wp_mail($this->email,$this->subject,$this->content,$this->headers,$this->attachments);
		}
		return false;
	}
}

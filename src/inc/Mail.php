<?php

/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

class Mail
{
	/**
	 * @access private
	 * @var mixed $to
	 * @var string $subject
	 * @var string $body
	 * @var array $headers
	 * @var array $attachments
	 */
	private $to = [];
	private $subject;
	private $body;
	private $headers = [];
	private $attachments = [];

	/**
	 * Init mail.
	 *
	 * @param mixed $email
	 */
	public function __construct($email = null)
	{
		if ($email) {
			$this->to = $email;
		}
		$this->setBody();
	}

	/**
	 * Set receiving email.
	 *
	 * @access public
	 * @param mixed $email
	 * @return object
	 */
	public function to($email): self
	{
		$this->to = $email;
		return $this;
	}

	/**
	 * Set body.
	 *
	 * @access public
	 * @param string $body
	 * @return object
	 */
	public function setBody(string $body = ''): self
	{
		$this->body = $body;
		return $this;
	}

	/**
	 * Add content to body.
	 *
	 * @access public
	 * @param string $content
	 * @param bool $break
	 * @return object
	 */
	public function addContent(string $content, bool $break = true): self
	{
		if ($break) {
			$this->addBreak();
		}
		$this->body .= $content;
		return $this;
	}

	/**
	 * Add break to body.
	 *
	 * @access public
	 * @return object
	 */
	public function addBreak(): self
	{
		$this->body .= "\n";
		return $this;
	}

	/**
	 * Set subject.
	 *
	 * @access public
	 * @param string $subject
	 * @return object
	 */
	public function setSubject(string $subject): self
	{
		$this->subject = $subject;
		return $this;
	}

	/**
	 * Set footer.
	 *
	 * @access public
	 * @return object
	 */
	public function setFooter(): self
	{
		$footer = '©' . Globals::website() . ' ' . date('Y');
		$this->addContent($footer);
		return $this;
	}

	/**
	 * Set headers.
	 *
	 * @access public
	 * @param array $headers
	 * @return object
	 */
	public function setheaders(array $headers = []): self
	{
		$this->headers = $headers;
		return $this;
	}

	/**
	 * Add header.
	 *
	 * @access public
	 * @param string $header
	 * @return object
	 */
	public function addHeader(string $header): self
	{
		$this->headers[] = $header;
		return $this;
	}

	/**
	 * Set attachments.
	 *
	 * @access public
	 * @param array $attachments
	 * @return object
	 */
	public function setAttachments(array $attachments = []): self
	{
		$this->attachments = $attachments;
		return $this;
	}

	/**
	 * Add attachment.
	 *
	 * @access public
	 * @param string $attachments
	 * @return object
	 */
	public function addAttachment(string $attachment): self
	{
		$this->attachments[] = $attachment;
		return $this;
	}

	/**
	 * Send email as HTML.
	 *
	 * @access public
	 * @return object
	 */
	public function asHTML(): self
	{
		$this->addHeader('Content-Type: text/html; charset=UTF-8');
		return $this;
	}

	/**
	 * Send email.
	 *
	 * @access public
	 * @return bool
	 */
	public function send(): bool
	{
		if (TypeCheck::isFunction('wp_mail')) {
			return wp_mail(
				$this->to,
				$this->subject,
				$this->body,
				$this->headers,
				$this->attachments
			);
		}
		return false;
	}

	/**
	 * Get admin email address.
	 *
	 * @access public
	 * @return string
	 */
	public static function address(): string
	{
		return Globals::email();
	}
}

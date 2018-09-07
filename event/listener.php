<?php
/**
 *
 * phpBB Symbols extension
 *
 * @copyright (c) 2018 v12mike
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace v12mike\symbols\event;

use phpbb\template\template;
use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use v12mike\symbols\ext;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	/** @var template */
	protected $template;

	/** @var string phpBB root path */
	protected $ext_root_path;

	/** @var user */
	protected $user;

	/**
	 * Constructor
	 *
	 * @param template  	  $template
	 * @param string		  $ext_root_path
	 * @access public
	 */
	public function __construct(template $template, user $user, $ext_root_path)
	{
		$this->template = $template;
		$this->user = $user;
		$this->ext_root_path = $ext_root_path;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 *
	 * @return array
	 * @static
	 * @access public
	 */
	public static function getSubscribedEvents()
	{
		return array(
			'core.posting_modify_template_vars' 	=> 'posting_modify_template_vars',
			'core.ucp_pm_compose_modify_data'   	=> 'posting_modify_template_vars',
		);
	}

	/**
	* Fill symbols templates
	*/
	public function posting_modify_template_vars($event)
	{
		/* load the extension language file */
		$this->user->add_lang_ext('v12mike/symbols', 'symbols');

		/* template flag to show that we are showing symbols tabs */
		$this->template->assign_var('SYMBOLS_TABS', 1);
	}
}

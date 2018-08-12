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
		$symbols_groups_file_path = $phpbb_root_path . $this->ext_root_path . 'data/';

		/* read the csv file holding the symbol groups definitions */
		$groups = $groups_header = array();
		$file_handle = fopen($symbols_groups_file_path . 'symbol_groups.csv', 'r');
		if ($file_handle)
		{
			while (($row = fgetcsv($file_handle, 1024)) !== false) 
			{
				if (empty($groups_header))
				{
					$groups_header = $row;
				}
				else
				{
					$groups[] = array_combine($groups_header, $row);
				}
			}
			fclose($file_handle);

			/* load the extension language file */
			$this->user->add_lang_ext('v12mike/symbols', 'symbols');

			/* iterate through the groups of symbols */
			foreach ($groups as $group)
			{
				$this->template->assign_block_vars('symbols_box', array(
					'SYMBOLS_TAB_ID'	 => $group['Identifier'],
					'SYMBOLS_TAB_NAME'	=> $this->user->lang[$group['Name']],
					'SYMBOLS_TAB_LABEL'	=> $group['Label'],
					)
				);
			}
            /* template flag to show that we at least found groups file */
			$this->template->assign_var('SYMBOLS_TABS', 1);
		}
	}
}

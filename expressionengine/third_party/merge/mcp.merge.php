<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Merge Control Panel Class
*
* @package		ExpressionEngine
* @category		Module
* @author		Steve Callan
* @copyright	Copyright (c) 2011, Steve Callan
*/

class Merge_mcp {


	/**
	 * Constructor
	 *
	 * @access	public
	 */

	function __construct($switch = TRUE)
	{
		// Make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();

		$this->base_url = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=merge';
	}

	// --------------------------------------------------------------------

	/**
	 * Control Panel Index
	 *
	 * @access	public
	 */

	function index($message = '')
	{
		$this->EE->load->library('table');
		$this->EE->load->library('javascript');
		$this->EE->load->helper('form');

		$vars = array(
			'message' => $message,
			'cp_page_title'	=> $this->EE->lang->line('merge_module_name'),
			'action_url'	=> 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=merge'.AMP.'method=various_settings'
		);

		foreach(array('cache_path', 'cache_web_path','html_root') as $val)
		{
			$vars["merge_" . $val] = $this->EE->config->item("merge_" . $val);
		}

		return $this->EE->load->view('index', $vars, TRUE);

	}

	// --------------------------------------------------------------------

	/** -------------------------------------------
	/**  Save Encryption Settings
	/** -------------------------------------------*/
	function various_settings()
	{
		$prefs = array('cache_path', 'cache_web_path','html_root');

		$insert = array();

		if ( ! isset($_POST['merge_cache_path']))
		{
			return $this->index();
		}

		foreach($prefs as $val)
		{
			if (isset($_POST["merge_" . $val]))
			{
				if ($val == 'cache_path' && ! is_really_writable($_POST["merge_" . $val]))
				{
					show_error($this->EE->lang->line('temporary_directory_unwritable'));
				}

				$insert["merge_" . $val] = $this->EE->security->xss_clean($_POST["merge_" . $val]);
			}
		}

		if (count($insert) == 0)
		{
			return $this->index();
		}


		$this->EE->config->_update_config($insert);

		$this->EE->session->set_flashdata('message_success', $this->EE->lang->line('settings_updated'));

		$this->EE->functions->redirect(BASE.AMP.'C=addons_modules'.AMP
		.'M=show_module_cp'.AMP.'module=merge'.AMP.'method=index');

	}

	
}


/* End of file mcp.merge.php */
/* Location: ./system/expressionengine/third_party/merge/mcp.merge.php */
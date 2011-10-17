<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Merge Update Class
*
* @package		ExpressionEngine
* @category		Module
* @author		Steve Callan
* @copyright	Copyright (c) 2011, Steve Callan
*/

class Merge_upd {

	var $version = '1.0';
	
	function __construct()
	{
		$this->EE =& get_instance();
	}
	
	// --------------------------------------------------------------------
	
	/**
	* Installer
	*
	* @access	public
	* @return	bool
	*/
	
	function install()
	{
	
		/* Create Module Entry */
	
		$data = array(
			'module_name' => 'Merge' ,
			'module_version' => $this->version,
			'has_cp_backend' => 'y',
			'has_publish_fields' => 'y'
		);
		
		$this->EE->db->insert('exp_modules', $data);
		
		$this->EE->config->_assign_to_config(array('merge_cache_path' 	=> '',
											'merge_cache_web_path' 	=> '',
											'merge_html_root'		=> ''));
		
	
	}
	
	// --------------------------------------------------------------------
	
	/**
	* Update
	*
	* @access	public
	* @return	bool
	*/
	
	function update($current = '')
	{
		if (version_compare($current, '1.0', '='))
		{
			return FALSE;
		}
	
		if (version_compare($current, $this->version, '<'))
		{
			// Do your update code here
		}
	
		return TRUE;
	}
	
	
	// --------------------------------------------------------------------
	
	/**
	* Uninstaller
	*
	* @access	public
	* @return	bool
	*/
	function uninstall()
	{
		$this->EE->load->dbforge();
	
		$this->EE->db->select('module_id');
		$query = $this->EE->db->get_where('modules', array('module_name' => 'Merge'));
	
		$this->EE->db->where('module_id', $query->row('module_id'));
		$this->EE->db->delete('module_member_groups');
	
		$this->EE->db->where('module_name', 'Merge');
		$this->EE->db->delete('modules');
	
		$this->EE->db->where('class', 'Merge');
		$this->EE->db->delete('actions');
	
		//$this->EE->dbforge->drop_table('download_files');
		//$this->EE->dbforge->drop_table('download_posts');	
	
		return TRUE;
	}
    
}
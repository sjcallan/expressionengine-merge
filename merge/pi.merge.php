<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
	'pi_name' => 'Merge',
	'pi_version' =>'1.0',
	'pi_author' =>'Steve Callan',
	'pi_author_url' => 'http://www.stevecallan.com/',
	'pi_description' => 'Merges multiple local CSS or Javascript files into single files to limit the number of HTTP requests. test',
	'pi_usage' => merge::usage()
);

/**
* Merge Class
*
* @package		ExpressionEngine
* @category		Plugin
* @author		Steve Callan
* @copyright	Copyright (c) 2011, Steve Callan
*/

class Merge {

	function __construct()
	{
		$this->EE =& get_instance();
		$this->EE->load->helper("file");
		
		// --------------------- MODIFY THESE VARIABLES --------------------- 
			/* 
			 	Full path to your HTML root directory
			 	ex: d://my_full_server_path/html/
			*/
				$this->html_root = "";
			
			/*
				Full Server Path to the directory where these merged files will be stored, this folder should be accessible from the web
				ex: d://my_full_server_path/html/cache_dir/	
			*/
				$this->cache_dir = ""; 
			
			 /*
			 	Web accessible path to your cache directory
			 	ex: /cache_dir/	
			 */
				$this->cache_web_path = "";
		
		// --------------------- END OF MODIFY THESE VARIABLES --------------------- 
		

	}
	
	function build()
	{	
	
		/* Grab all the data from the view */
			$tag_content = $this->EE->TMPL->tagdata;
			$this->minify_code = $this->EE->TMPL->fetch_param('minify');
			
			if($this->minify_code == "")
			{	
				$this->minify_code = "yes";
			}
			
		/* Colidate all the links into one CSS file and one Javascript file */
			$css_result = $this->_consolidate_files($tag_content,"css");
			$js_result = $this->_consolidate_files($tag_content,"js");
		
		/* Join and return */
			if($js_result <> "")
			{
				return $css_result . "\n" . $js_result;
			}
			else
			{
				return $css_result;
			}
		
	}
	
	function _consolidate_files($tag_content,$ext)
	{
			$content = "";
			$name = "";
	
		/* Find all matching links */
		
			if($ext == "css")
			{
				$regex = '/(?:<link\b.+href=[\'"])(?!http)([^"]*)(?:[\'"].*>)/siU';
			}
			else
			{
				$regex = '/(?:<script\b.+src=[\'"])(?!http)([^"]*)(?:[\'"].*>)/siU';
			}
			
			if(preg_match_all($regex, $tag_content, $matches)) { 
				
				foreach($matches[2] AS $match)
				{
					$content .= $this->_file_get_contents($this->html_root . $match,$ext);
					$filename = $this->_get_filename($match,$ext);
					
					$name .= $filename . "_";
				}
				
			}
			
		/* If there are no files, return empty otherwise compare and build file */
		
			if($name == "")
			{
				return "";
			}
			else
			{
				$new_filename = $name . "." . $ext;
			
				if(file_exists($this->cache_dir . $new_filename))
				{
					$old_contents = $this->_file_get_contents($this->cache_dir . $new_filename,$ext);
				
					if(!$this->_check_file_contents($old_contents,$content))
					{
						$this->_build_file($content,$this->cache_dir . $new_filename);
					}
				}
				else
				{
					$this->_build_file($content,$this->cache_dir . $new_filename);	
				}
				
				if($ext == "css")
				{
					return "<link href=\"" . $this->cache_web_path . $new_filename . "\" rel=\"stylesheet\" type=\"text/css\" />";
				}
				else
				{
					return "<script src=\"" . $this->cache_web_path . $new_filename . "\" type=\"text/javascript\"></script>";
				}
				
			}
	
	}
	
	function _file_get_contents($file_path,$ext)
	{
		
		$contents = read_file($file_path);
		
		if($this->minify_code == "yes")
		{
			$contents = preg_replace('/\s\s+/', ' ', $contents);
		}
		
		return $contents;
	
	}
	
	function _build_file($content,$new_filename)
	{
		write_file($new_filename, $content);
	} 
	
	function _get_filename($filename,$ext)
	{
		$filename_array = explode("/",$filename);
		$filename_last_seg = $filename_array[count($filename_array)-1];
		$filename =  str_replace("." . $ext,"",$filename_last_seg);
		return $filename;
	}
	
	function _check_file_contents($old_contents, $new_contents)
	{
		if($old_contents == $new_contents)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	function usage()
	{
	
		ob_start(); 
		?>
		The ExpressionEngine merge plugin merges multiple local CSS or Javascript files into single files to limit the number of HTTP requests your visitors need to make without you needing to sacrifice your clean directory structure.
		
		{exp:merge:build}
			<link href="my_great_file.css" rel="stylesheet" type="text/css" />
			<link href="my_second_great_file.css" rel="stylesheet" type="text/css" />
		{/exp:merge:build}
		
		Outputs: <link href="my_great_file_my_second_great_file.css" rel="stylesheet" type="text/css" />
		
		Optional parameters:
		minify ("yes" "no", default = "yes")
		
		
		<?php
		$buffer = ob_get_contents();
		
		ob_end_clean(); 
		
		return $buffer;
	
	}
	
}


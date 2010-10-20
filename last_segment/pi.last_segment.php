<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
  'pi_name' => 'Last Segment',
  'pi_version' => '1.0',
  'pi_author' => 'Bjorn Borresen',
  'pi_author_url' => 'http://bybjorn.com/',
  'pi_description' => 'Get the last segment from the url',
  'pi_usage' => Last_segment::usage()
  );

/**
 * PagesList
 *
 * @package			ExpressionEngine
 * @category		Plugin
 * @author			Bjorn Borresen
 * @copyright		Copyright (c) 2009, Bjorn Borresen
 * @link			http://bybjorn.com/ee/pageslist
 */

class Last_segment
{
	
	var $return_data = "";
	 
	function Last_segment()
	{
		$this->EE =& get_instance();		
		$ignore_pagination = !($this->EE->TMPL->fetch_param('ignore_pagination') == 'no');
		
		$segment_count = $this->EE->uri->total_segments();
		$last_segment = $this->EE->uri->segment($segment_count);
				
		if($ignore_pagination && substr($last_segment,0,1) == 'P') // might be a pagination page indicator
		{
			$end = substr($last_segment, 1, strlen($last_segment));
			if ((preg_match( '/^\d*$/', $end) == 1))
			{
				$last_segment = $this->EE->uri->segment($segment_count-1); 
			} 		
		}
		return $this->return_data = $last_segment;		
	}
  


	// --------------------------------------------------------------------
	/**
	 * Usage
	 *
	 * This function describes how the plugin is used.
	 *
	 * @access	public
	 * @return	string
	 */	
	  function usage()
	  {
	  ob_start(); 
	  ?>
		Get the last segment of the current URL. Inspired by Tommy Kiss' Last Segment extention for 1.6.8
		
		Usage example:
		
		{exp:last_segment}	// will return the last segment (but not pagination segment if it exist)
		
		or
		
		{exp:last_segment ignore_pagination='no'}	// will return the last segment
				
	  <?php
	  $buffer = ob_get_contents();
		
	  ob_end_clean(); 
	
	  return $buffer;
	  }
	  // END

}
/* End of file pi.pageslist.php */ 

/* Location: ./system/expressionengine/third_party/pageslist/pi.last_segment.php */ 
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Registers the current last segment as a global variable
 *
 * @package		last_segment
 * @subpackage	ThirdParty
 * @category	Extension
 * @author		Bjorn Borresen
 * @link		http://ee.bybjorn.com/last_segment
 */
class Last_segment_ext {

    var $settings        = array();
    
    var $name            = 'Last Segment';
    var $version         = '1.0.0';
    var $description     = 'Adds global variables; {last_segment}, {last_segment_absolute}, {last_segment_id}';
    var $settings_exist  = 'n';
    var $docs_url        = 'http://ee.bybjorn.com/last_segment';

    /**
     * Constructor 
     * 
     * @paramarray of settings
     */
    function Last_segment_ext($settings='')
    {
		$this->settings = $settings;						
		$this->EE =& get_instance();    	// Make a local reference to the ExpressionEngine super object							
    }
    
    
    
	/**
	 * Settings
	 */
	function settings()
	{

	}

    
	
	/**
	 * Update the extension
	 * 
	 * @param $current current version number
	 * @return boolean indicating whether or not the extension was updated 
	 */
	function update_extension($current='')
	{    
	    if ($current == '' OR $current == $this->version)
	    {
	        return FALSE;
	    }
	    
	    return FALSE;
	    // update code if version differs here
	}
		
	/**
	 * Disable the extention
	 * 
	 * @return unknown_type
	 */    
	function disable_extension()
	{		
		//
		// Remove added hooks
		//
		$this->EE->db->delete('extensions', array('class'=>get_class($this)));
			
	}
		

    /**
     * Activate the extension
     * 
     * This function is run on install and will register all hooks
     * 
     */
	function activate_extension()
	{
		
		 // -------------------------------------------------
		 // Register the hooks needed for this extension 
		 // -------------------------------------------------
		 
		$register_hooks = array(			
			'sessions_start' => 'on_sessions_start',				
		);
		
		$class_name = get_class($this);
		foreach($register_hooks as $hook => $method)
		{
			$data = array(                                        
				'class'        => $class_name,
				'method'       => $method,
				'hook'         => $hook,
				'settings'     => "",
				'priority'     => 10,
				'version'      => $this->version,
				'enabled'      => "y"
			);
			$this->EE->db->insert('extensions', $data); 	
		}
		
	}

	//
	// HOOKS
	//
	
	function on_sessions_start($ref)
	{				
		$segment_count = $this->EE->uri->total_segments();		
		$last_segment_absolute = $this->EE->uri->segment($segment_count); 
		$last_segment = $last_segment_absolute;
		$last_segment_id = $segment_count;
				
		if(substr($last_segment,0,1) == 'P') // might be a pagination page indicator
		{
			$end = substr($last_segment, 1, strlen($last_segment));
			if ((preg_match( '/^\d*$/', $end) == 1))
			{
				$last_segment_id = $segment_count-1;
				$last_segment = $this->EE->uri->segment($last_segment_id);				 
			} 		
		}

		$this->EE->config->_global_vars['last_segment'] = $last_segment;    // in case pre. 2.1.1
		$this->EE->config->_global_vars['last_segment_absolute'] = $last_segment;
		$this->EE->config->_global_vars['last_segment_id'] = $last_segment_id;
		$this->EE->config->_global_vars['last_segment_absolute_id'] = $segment_count;
        $this->EE->config->_global_vars['last_segment_no_pagination'] = $last_segment;
	}

}

/* End of file ext.openid.php */ 
/* Location: ./system/expressionengine/third_party/last_segment/ext.last_segment.php */ 
<?php

/****************************************************************//**
 *Take the response from a get_entry_list and decompose into original module
 *
 * 	********************************************************************/

/***********************//**
 * Generic class
 * ***********************/

require_once( '../ksf_modules_common/class.origin.php' );
require_once( "class.name_value_list.php" );
use Exception;
use name_value_list;

/***********************************************//**
 * Superparent to SUITECRM classes.  
 *
 * This class handles SOAP activities.
 * *************************************************/
class ResponseDecompose extends origin {
    protected $nvl; // Array of data to be added to SOAP msg
    protected $moduleName; // String representing the module to query about
    protected $id;
    protected $obj;

    /**********************************************************//**
     *
     * @param stdClass $response Response object from a SOAP query
     * *********************************************************/	
    function __construct($response) {
        if (!isset($response->id)) {
            throw new Exception("Are you sure of the data type passed in?");
        }
        $this->set("id", $response->id);

        if (!isset($response->module_name)) {
            throw new Exception("Without a module name we can't go forward");
        }
        $this->set("moduleName", $response->module_name);

        if (!isset($response->name_value_list)) {
            throw new Exception("Without a NVL we can't go forward");
        }
        $nvl = new name_value_list();
        $hash = $nvl->hashNvl($response->name_value_list);
        $this->hashToObj($hash);
    }

    public function set($key, $value) {
        $this->$key = $value;
    }

    function hashToObj($hash = null) {
        $className = "suitecrm_" . strtolower($this->moduleName);
        require_once('class.' . $className . '.php');
        $obj = new $className();
        foreach ($hash as $key => $value) {
            $obj->set($key, $value, true);
        }
        $this->obj = $obj;
        // We now have a "Accounts/Contacts/..." module ready for use (i.e. ->prepare())
    }
}


<?php

/****************************************************************//**
 * Base class for all SuiteCRM classes to be used in API
 *
 * REFACTORING
 * 20201121
 * 	I've tested SOAP code that makes it fairly easy to do the API
 * 	calls without a lot of specific code.  As we are writing modules
 * 	to interface between apps, we need a way to "map" fields betweem
 * 	systems.
 *
 * 	See client.protect.php for examples of creating an account + contact.
 *
 * 	********************************************************************/

/***********************//**
 * Generic class
 * ***********************/

use Exception;
use SoapClient;

if (!defined('KSF_FIELD_NOT_SET')) {
    define('KSF_FIELD_NOT_SET', 1); // Placeholder value
}
if (!defined('KSF_VALUE_NOT_SET')) {
    define('KSF_VALUE_NOT_SET', 2); // Placeholder value
}
if (!defined('KSF_RESULT_NOT_SET')) {
    define('KSF_RESULT_NOT_SET', 3); // Placeholder value
}

require_once( '../ksf_modules_common/class.origin.php' );
require_once( "class.name_value_list.php" );

/************************************************//**
 * This is an attempt at an abstract SOAP class based upon a working SuiteCRM connection.
 *
 * This class may not actually abstract much away.
 * *************************************************/
class ksfSOAP extends origin
{
    protected $soapClient;
    protected $username;
    protected $password;
    protected $url;
    protected $soapLoginTime;
    protected $soapAuthArray;
    protected $retryCount;
    protected $result;
    protected $appName;
    protected $sessionId;
    protected $debugLevel;
    protected $soapParams; // Changed to protected
    protected $moduleName;
    protected $version;

    function __construct() {
        parent::__construct();
        $this->debugLevel = 0;
        $this->retryCount = 0;
        $this->result = null;
        $this->sessionId = null;
        $this->moduleName = null;
    }

    function setSoapClient() {
        if (!isset($this->url)) {
            throw new Exception("Base SOAP URL must be set", KSF_FIELD_NOT_SET);
        }
        $this->soapClient = new SoapClient($this->url . '?wsdl');
    }

    function buildAuthArray() {
        if (!isset($this->username)) {
            throw new Exception("SOAP username must be set", KSF_FIELD_NOT_SET);
        }
        if (!isset($this->password)) {
            throw new Exception("SOAP Password must be set", KSF_FIELD_NOT_SET);
        }
        $this->soapAuthArray = [
            'user_name' => $this->username,
            'password' => $this->password
        ];
        if (isset($this->version)) {
            $this->soapAuthArray['version'] = $this->version;
        }
        return $this->soapAuthArray;
    }

    function soapReconnect() {
        if (!isset($this->soapLoginTime)) {
            throw new Exception("SOAP Login time must be set.", KSF_FIELD_NOT_SET);
        }
        if (time() - $this->soapLoginTime > 600) {
            try {
                $this->soapLogin();
            } catch (Exception $e) {
                throw $e;
            }
        }
    }

    function soapLogin() {
        if (!isset($this->appName)) {
            throw new Exception("SOAP App Name must be set or blank", KSF_FIELD_NOT_SET);
        }
        $this->setSoapClient();
        if (!isset($this->soapAuthArray) || !isset($this->soapAuthArray['user_name'])) {
            $this->buildAuthArray();
        }
        try {
            $soapLogin = $this->soapClient->login($this->soapAuthArray, $this->appName, []);
            $this->sessionId = $soapLogin->id;
            $this->soapLoginTime = time();
            return $this->sessionId;
        } catch (SoapFault $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    function soapLogout() {
        $this->soapClient->logout($this->sessionId);
        unset($this->sessionId);
        unset($this->soapLoginTime);
    }

    function soapCall($operation) {
        try {
            if (!isset($this->soapParams) || !is_array($this->soapParams)) {
                throw new Exception("SoapParams must be set", KSF_VALUE_NOT_SET);
            }
            $this->result = $this->soapClient->__soapCall($operation, $this->soapParams);
        } catch (SoapFault $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
        if (isset($this->result->error)) {
            $errno = $this->result->error['number'];
            if ($errno === 10 && $this->retryCount < 5) {
                $this->soapReconnect();
                $this->retryCount++;
                $this->result = $this->soapCall($operation);
                $this->retryCount = 0;
            }
        }
        if ($this->result === null) {
            throw new Exception("Why is result null?", KSF_RESULT_NOT_SET);
        }
        return $this->result;
    }

    function getFunctions() {
        return $this->soapClient->__getFunctions();
    }
}



<?php 

class lhgcmConfig
{
    private static $instance = null;
    public $conf;
	private $settingsFile;

    private function __construct()
    {
		$this->settingsFile = 'extension/gcm/settings/settings.ini.php';
		$this->reload();
    }

	public function reload() {
		if (is_readable($this->settingsFile)) {
	    	$this->conf = include($this->settingsFile);
		} else {
			$this->conf = null;
		}
	}
	
	/**
	 * This overwrites the current config
	 */
	public function setConfig($config) {
		if (is_array($config)) {
			$this->conf = $config;
			return true;
		}
		
		return false;
	}
	
	public function isValid() {
		return is_array($this->conf);
	}
	
    public function getSetting($section, $key, $throwException = true)
    {
    	if (!$this->isValid()) {
    		return false;
    	}

        if (isset($this->conf['settings'][$section][$key])) {
            return $this->conf['settings'][$section][$key];
        } else {
        	if ($throwException === true) {
            	throw new Exception\HttpException(500, 'Setting with section {'.$section.'} value {'.$key.'}');
        	} else {
        		return false;
        	}
        }
    }
	
	
    public function hasSetting($section, $key)
    {
    	if (!$this->isValid()) {
    		return false;
    	}
    	
        return isset($this->conf['settings'][$section][$key]);
    }
	
	

    public function setSetting($section, $key, $value)
    {
    	if (!$this->isValid()) {
    		return false;
    	}

        $this->conf['settings'][$section][$key] = $value;
    }

    public static function getInstance()
    {
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function save()
    {
    	if (!$this->isValid()) {
    		return false;
    	}

        return file_put_contents($this->settingsFile, "<?php\n return ".var_export($this->conf,true).";\n?>");
    }
	
	public function deleteFile() {
    	if (!$this->isValid()) {
    		return true;	// File doesn't exist so return true
    	}
		
		$success = unlink($this->settingsFile);
		if ($success) {
			$this->conf = null;
		}
		
		return $success;
	}
}


?>
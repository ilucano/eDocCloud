<?php

class Activity_Logs {
    
    
    public $pdocon;
    
    public function __construct()
    {
        $this->pdocon = NConnectionFactory::getConnection();
 
    }
    
    
    public function log()
    {
      echo "log";
        
        
    }
    
}


$ActivityLogs = new Activity_Logs();
print_r($ActivityLogs);
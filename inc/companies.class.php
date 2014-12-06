<?php

if(session_id() == '' || !isset($_SESSION)) {
    // session isn't started
    session_start();
}

require_once $arrIni['base'].'lib/db/dbConn.php';

class Companies {
    
    public $pdocon;
    
    public function __construct()
    {
        $this->pdocon = NConnectionFactory::getConnection();
        
    }
    
    
    
    public function getCompany($row_id)
    {
        
        $query = "SELECT * FROM companies WHERE row_id = :row_id LIMIT 1";
        $array_bind = array(':row_id' => $row_id);
        
        $stmt = $this->pdocon->prepare($query);
        $stmt->execute($array_bind);
        
        $row = $stmt->fetch();
        return $row;
    
    }
    

}

<?php

if(session_id() == '' || !isset($_SESSION)) {
    // session isn't started
    session_start();
}

require_once $arrIni['base'].'lib/db/dbConn.php';

class Users {
    
    public $pdocon;
    
    public function __construct()
    {
        $this->pdocon = NConnectionFactory::getConnection();
        
    }
    
    /**
     * Check if users is company admin
     * @param string $username Default null
     * 
     */
    public function isCompanyAdmin($username = null)
    {
        
        
        if($username == '') {
            $username = $_SESSION['Vusername'];
        }
        
        $query = "SELECT company_admin FROM users WHERE username = :username LIMIT 1";
        $array_bind = array(':username' => $username);
        
        $stmt = $this->pdocon->prepare($query);
        $stmt->execute($array_bind);
        $row = $stmt->fetch();
         
        return  ($row['company_admin'] == 'X') ? true : false;
    }
    
    /**
     * Return users company ID
     *
     */
    
    public function userCompany($username = null)
    {
        
        
    }
}
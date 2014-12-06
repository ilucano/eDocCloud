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
        
        if($username == '') {
            $username = $_SESSION['Vusername'];
        }
        
        $query = "SELECT fk_empresa FROM users WHERE username = :username LIMIT 1";
        
        $array_bind = array(':username' => $username);
        
        $stmt = $this->pdocon->prepare($query);
        $stmt->execute($array_bind);
        $row = $stmt->fetch();
        
        return $row['fk_empresa'];
        
    }
    
    
    public function getUser($user_id, $filter = null, $filter_bind = null)
    {
        $query = "SELECT * FROM users WHERE row_id = :row_id";
        
        if($filter) {
            $query .= $filter;
        }
        
        $array_bind = array(':row_id' => $user_id);
        
        if($filter_bind) {
            $array_bind = array_merge($array_bind, $filter_bind);
            
        }
        
        $stmt = $this->pdocon->prepare($query);
        $stmt->execute($array_bind);
        $row = $stmt->fetch();
        
        return $row;
    }
    
    public function listUsers($filter = null, $array_bind = null, $order = null, $limit = null)
    {
        
        $query = "SELECT * FROM users WHERE 1 ";
        
        if($filter) {
            $query .= $filter;
        }
        
        if($order) {
            $query .= $order;
        }
        
        if($limit) {
            $query .= $limit;
        }
        
        $stmt = $this->pdocon->prepare($query);
        $stmt->execute($array_bind);
       
        while ($r = $stmt->fetch()) {
            $row[] = $r;
        }
    
        return $row;
        
    }
    
}

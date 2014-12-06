<?php


require_once $arrIni['base'].'lib/db/dbConn.php';

class Groups {
    
    public $pdocon;
    
    public function __construct()
    {
        $this->pdocon = NConnectionFactory::getConnection();
        
    }
     
    public function listGroups($filter = null, $array_bind = null, $order = null, $limit = null)
    {
        
        $query = "SELECT * FROM groups WHERE 1 ";
        
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

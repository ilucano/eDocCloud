<?php

if(session_id() == '' || !isset($_SESSION)) {
    // session isn't started
    session_start();
}

require_once $arrIni['base'].'lib/db/dbConn.php';

class Files {
    
    public $pdocon;
    
    public function __construct()
    {
        $this->pdocon = NConnectionFactory::getConnection();
        
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
    
    public function getRecordByFilter($filter, $array_bind)
    {
        
        $query = "SELECT * FROM file_marks WHERE $filter";
        
        
        $stmt = $this->pdocon->prepare($query);
        $stmt->execute($array_bind);
        $row = $stmt->fetch();
        
        return $row;
    }
    
    public function insertRecord($data = array())
    {
        if(count($data) <= 1)
            return false;
        
        $array_values = array();
        $array_columns = array();
        
        foreach ($data as $field => $value)
        {
            $array_columns[] = "`$field`";
            
            $bind_field = ":". $field;
            
            $array_values[] = $bind_field;
            
            $array_bind[$bind_field] = $value;
            
        }
        $string_columns = "(" . join(", ", $array_columns) . ")";
        $string_values = "(" . join(", ", $array_values) . ")";
        
        $query = "INSERT INTO file_marks $string_columns
                  VALUES $string_values ";
 
        $stmt = $this->pdocon->prepare($query);
        $stmt->execute($array_bind);
    }
    
    public function updateRecord($data = array(), $id, $custom_where = null)
    {
        if($id == '' || count($data) < 1)
            return false;
        
        
    
        $array_set = array();
        foreach ($data as $field => $value)
        {
            $bind_field = ":". $field;
            
            $array_set[] = " `$field` = $bind_field ";
            
            $array_bind[$bind_field] = $value;
            
        }
        
        $array_bind[':id'] = $id;
        
        $string_set = join(", ",  $array_set);
        
        $query = "UPDATE file_marks SET $string_set WHERE id = :id $custom_where";
 
        $stmt = $this->pdocon->prepare($query);
        $stmt->execute($array_bind);
        
    }
    
    
    public function listCountByYears($company = null)
    {
        $array_bind = array();
        
        if($company) {
            $filter = " AND fk_empresa = :fk_empresa";
            $array_bind[':fk_empresa'] = $company;
        }
        
        $query = "SELECT file_year, COUNT(*) as num FROM files
                  WHERE 1 $filter GROUP BY file_year ORDER BY file_year";
                  
        
        $stmt = $this->pdocon->prepare($query);
        $stmt->execute($array_bind);
       
        while ($r = $stmt->fetch()) {
            $row[] = $r;
        }
    
        return $row;
        
    }
    
    
    public function listCountByAlphabet($company = null, $year)
    {
        $array_bind = array();
        
        if($company) {
            $filter .= " AND fk_empresa = :fk_empresa";
            $array_bind[':fk_empresa'] = $company;
        }
        
        if($year == '') {
            $filter .= " AND (file_year = :file_year OR file_year is NULL)";
        }
        else {
            $filter .= " AND file_year = :file_year";
        }
        
        $array_bind[':file_year'] = $year;
        
        
        $query = "SELECT SUBSTR(`filename`,1,1) AS alpha, COUNT(`filename`) as num
                    FROM files  WHERE 1 $filter
                 GROUP BY SUBSTR(`filename`,1,1) ORDER BY SUBSTR(`filename`,1,1) ";
        
 
        $stmt = $this->pdocon->prepare($query);
        $stmt->execute($array_bind);
       
        while ($r = $stmt->fetch()) {
            $row[] = $r;
        }
    
        return $row;
        
    }
    
    public function listFilemarks($filter = null, $array_bind = null, $order = null, $limit = null)
    {
        
        $query = "SELECT * FROM file_marks WHERE 1 ";
        
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
    
    
    public function getLabelById($id)
    {
        
        $query = "SELECT * FROM file_marks WHERE id = :id";
        
        $array_bind = array(':id' => $id);
        
        $stmt = $this->pdocon->prepare($query);
        $stmt->execute($array_bind);
        $row = $stmt->fetch();
        
        return $row['label'];

    }
}

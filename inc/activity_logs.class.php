<?php

class Activity_Logs {
    
    
    public $pdocon;
    
    public $array_log_pages;
    
    public function __construct()
    {
        $this->pdocon = NConnectionFactory::getConnection();
        
        $this->array_log_pages = array( '/chgpwd.php' => 'Change Password',
                                        '/login.php'  => 'Login',
                                        '/orders.php'  => 'List Order',
                                        '/lib/data/dBoxes.php' => 'View Order Boxes',
                                        '/lib/search/ftsearch.php' => 'Search Content',
                                        '/lib/search/ftsearch.php' =>  'Search Filename',
                                        '/admin/wf_pick.php'     => 'View Pickups',
                                        '/lib/data/wf.pickup.php' => 'Pickup Action',
                                        '/admin/prep.php'  => 'View Preparations',
                                        '/lib/data/wf.prepare.php' => 'Preparation Action',
                                        '/admin/scan.php' => 'View Scans',
                                        '/lib/data/wf.scan.php' => 'Scan Action',
                                        '/admin/qa.php' => 'View QA',
                                        '/lib/data/wf.qa.php' => 'QA Action',
                                        '/admin/ocr.php' => 'View OCR',
                                        '/lib/data/wf.ocr.php' => 'OCT Action',
                                        '/admin/company.php' => 'Admin View Companies',
                                        '/lib/data/company/dCompany.actions.php' => 'Admin Company Action',
                                        '/lib/data/company/dCompany.actions.e.php' => 'Admin Company Action Complete',
                                        '/admin/users.php' => 'Admin View Users',
                                        '/lib/data/users/dUsers.actions.php' => 'Admin User Action',
                                        '/lib/data/users/dUsers.actions.e.php' => 'Admin User Action Complete',
                                        '/admin/groups.php' => 'Admin View Groups',
                                        '/lib/data/users/dGroups.actions.php' => 'Admin Group Action',
                                        '/lib/data/users/dGroups.actions.e.php' => 'Admin Group Action Complete',
                                        '/admin/orders.php' => 'Admin View Orders',
                                        '/lib/data/users/dOrders.actions.php' => 'Admin Order Action',
                                        '/lib/data/users/dOrders.actions.e.php' => 'Admin Order Action Complete',
                                        '/admin/pickup.php' => 'Admin View Pickups',
                                        '/lib/data/users/dPickup.actions.php' => 'Admin Pickup Action',
                                        '/lib/data/users/dPickup.actions.e.php' => 'Admin Pickup Action Complete',
                                        '/admin/box.php' => 'Admin View Boxes',
                                        '/lib/data/users/dBox.actions.php' => 'Admin Box Action',
                                        '/lib/data/users/dBox.actions.e.php' => 'Admin Box Action Complete',
                                       );
    }
    
    
    public function log()
    {
        $data = $_REQUEST;
        $this->insertLog($data);
        
    }
    
    public function insertLog($data)
    {
        $columnStr = '';
        $valueStr = '';
        
        foreach($data as $column => $value)
        {
            $array_column[] = "`".$column."`";
            $array_value_bind = ":".$column;
            $array_bind[$array_value_bind] = $value;
        }
        
        $columnStr = "(" . implode(",", $array_column) . ")";
        $valueStr = "(" . implode(",", $array_value_bind) . ")";
    
        $insert_query = "INSERT INTO activity_logs
                         $columnStr 
                         $valueStr ";
                         
        echo $insert_query;
        
        
    }
    
}

<?php
require_once $arrIni['base'].'lib/db/dbConn.php' 
class Activity_Logs {
    
    
    public $pdocon;
    
    public $array_log_pages;
    
    public function __construct()
    {
        $this->pdocon = NConnectionFactory::getConnection();
        
        $this->array_log_pages = array( '/chgpwd.php' => 'Change Password Complete',
                                        '/login.php'  => 'Login',
                                        '/orders.php'  => 'List Order',
                                        '/lib/data/dBoxes.php' => 'View Order Boxes',
                                        '/lib/search/ftsearch.php' => 'Search Content',
                                        '/lib/search/ftsearchn.php' =>  'Search Filename',
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
                                        '/lib/data/groups/dGroups.actions.php' => 'Admin Group Action',
                                        '/lib/data/groups/dGroups.actions.e.php' => 'Admin Group Action Complete',
                                        '/admin/orders.php' => 'Admin View Orders',
                                        '/lib/data/orders/dOrders.actions.php' => 'Admin Order Action',
                                        '/lib/data/orders/dOrders.actions.e.php' => 'Admin Order Action Complete',
                                        '/admin/pickup.php' => 'Admin View Pickups',
                                        '/lib/data/pickup/dPickup.actions.php' => 'Admin Pickup Action',
                                        '/lib/data/pickup/dPickup.actions.e.php' => 'Admin Pickup Action Complete',
                                        '/admin/box.php' => 'Admin View Boxes',
                                        '/lib/data/box/dBox.actions.php' => 'Admin Box Action',
                                        '/lib/data/box/dBox.actions.e.php' => 'Admin Box Action Complete',
                                        '/lib/data/dFiles.php' => 'View Chart In Box',
                                       );
    }
    
    
    public function log()
    {
        $module = $this->MapScriptToAction($_SERVER['SCRIPT_NAME']);
     
        if($module == '')
            return;
        
        if($module == 'Change Password' && $_POST['password'] == '')
            return;
        
        $username = $_SESSION['Vusername']  ? $_SESSION['Vusername'] : $_POST['username'];
        
        
        $data['module'] =  $module;
        $data['username'] = $username;
        
        $log_details  = $this->FilterLogDetails($_REQUEST, $module);
        
        $data['parameters'] =  json_encode($log_details);
        $data['ip_address'] = $_SERVER['REMOTE_ADDR'];
        $data['create_date'] = date("Y-m-d H:i:s");
        
        $this->insertLog($data);
        
    }
    
    public function FilterLogDetails($_REQUEST, $module)
    {
        $array_fields = array('action', 'id', 'wfid', 'status', 'fk_company', 'texto', 'ordid', 'boxid', 'orderid');
        
        $array_result = array();
        
        foreach($array_fields as $key)
        {
            if($_REQUEST[$key]) {
                $array_result[$key] = $_REQUEST[$key];
            }
            
        }
        return $array_result;
        
    }
    
    
    public function MapScriptToAction($key)
    {
        
        return $this->array_log_pages[$key];
        
    }
    
    public function insertLog($data)
    {
        $columnStr = '';
        $valueStr = '';
 
        foreach($data as $column => $value)
        {
            $array_column[] = "`".$column."`";
            $array_value_bind[] = ":".$column;
            $array_bind[":".$column] = $value;
        }
        
        $columnStr = "(" . implode(",", $array_column) . ")";
        $valueStr = " VALUES (" . implode(",", $array_value_bind) . ")";
    
        $insert_query = "INSERT INTO activity_logs
                         $columnStr 
                         $valueStr ";
        
        $stmt = $this->pdocon->prepare($insert_query);
        $stmt->execute($array_bind);
        
        
    }
    
    
    public function customLog($details)
    {
        
        
        $data['module'] =   $details['module'];
        $data['username'] =  $details['username'];

        $data['parameters'] =   $details['parameters'];
        $data['ip_address'] = $_SERVER['REMOTE_ADDR'];
        $data['create_date'] = date("Y-m-d H:i:s");
        
        $this->insertLog($data);
        
        
    }
}

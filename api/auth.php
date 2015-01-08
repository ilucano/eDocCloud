<?php

   require_once('lib/nusoap.php'); // basic include.. must go at the top
   
   $SERVICE_NAMESPACE = "urn:Testing_Service"; // create a namespace to run under.
   
   $server = new soap_server(); // the soap object from the include above.

   // this has many input parameters but we only need two: the service name and the namespace
   $server->configureWSDL('Testing_Service', $SERVICE_NAMESPACE);
   
   // Register a method name with the service and make it publicly accessable.
   $server->register('Say_Hello',// method name
        array('name' => 'xsd:string'),// input parameter called name.. and it's a string.
        array('return' => 'xsd:string'),// output - one string is returned called "return"
        $SERVICE_NAMESPACE,// namespace
        $SERVICE_NAMESPACE . '#hello',// soapaction
        'rpc',// style.. remote procedure call
        'encoded',// use of the call
        'Sends a greeting with your name!'// documentation for people who hook into your service.
    );
    
    // here is the method you registered.. it takes in a string and returns a string!
    function Say_Hello($sName) 
    {        
      return 'Hello ' . $sName . '!  Hello world!';
    }
    
    //This processes the request and returns a result.
    $HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
    $server->service($HTTP_RAW_POST_DATA); 
?>

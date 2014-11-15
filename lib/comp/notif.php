<?php

require_once('lib/db/db.php');

function GetNotifications($intCtd){

	$con = ConnectionFactory::getConnection();

	$qry = "SELECT T1.row_id as row_id, T1.notdate as notdate, T1.object, T1.status as status, T2.object_description as objectdesc, T3.first_name, T3.last_name, T4.plural as action, T5.f_code, T5.f_name FROM notifications T1 INNER JOIN objecttypes T2 ON T2.row_id = T1.fk_type_object INNER JOIN users T3 ON T3.row_id = T1.fk_user INNER JOIN actions T4 ON T4.row_id = T1.fk_action INNER JOIN objects T5 ON T5.row_id = T1.object WHERE T1.fk_company = ".$_SESSION['CoCo']." ORDER BY T1.notdate ASC LIMIT 0,".$intCtd;
	
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qry);
	
	$antes = '<tr><td>';
	$despues = '</td></tr>';
	
	if (mysql_num_rows($res)) {
		while ($row = mysql_fetch_array($res)) {
			
			echo $antes;
			echo $row['notdate'].' - The '.$row['objectdesc'].' ('.$row['f_code'].','.$row['f_name'].') has been '.$row['action'];
			if ($row['status']== 'N') {
					echo '  <span class="success label">New</span>';
					NotifCS($row['row_id']);
			}
			echo $despues;
		}
	} else {
		echo $antes;
		echo "You don't have notifications at this time";
		echo $despues;
	}
	
	};
	
	function NotifCS($rw) {
		
		$con = ConnectionFactory::getConnection();
		
		mysql_query("UPDATE notifications SET status = 'R' WHERE row_id = ".$rw)
    	or die(mysql_error());   
		
		};
		
		ConnectionFactory::close();

?>
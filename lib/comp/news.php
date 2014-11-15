<?php

require_once('lib/db/db.php');

function GetNews($intCtd){

	$con = ConnectionFactory::getConnection();
	
	$qry = "SELECT row_id, title, author, copet, texto FROM news ORDER BY datepub DESC 
LIMIT 0 , ".$intCtd;
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qry);
	//$num_row = mysql_num_rows($res);
	//$row=mysql_fetch_assoc($res);
	
	while ($row = mysql_fetch_array($res)) {
    	$antes = '<tr><td>';
		$despues = '</td></tr>';
		
		echo $antes;
		echo '<h4><a href="senews.php?id='.$row['row_id'].'">'.$row['title'].'</a></h4><br>'.$row['copet'].'<br><br><a href="senews.php?id='.$row['row_id'].'">More...</a>';
		echo $despues;
	}
	ConnectionFactory::close();
	
	};

?>
<?php

	session_start();
	
	require_once '/var/www/html/config.php';
	
	require_once $arrIni['base'].'inc/check.php';
	require_once $arrIni['base'].'inc/general.php';
	require_once $arrIni['base'].'lib/db/db.php' ;
	
	
	$antes = "<div class=\"large-1 columns\">&nbsp;</div><div class=\"large-10 columns\">";
	$despues = "</div><div class=\"large-1 columns\"></div></div>";
	
	echo $antes;
	
	$con = ConnectionFactory::getConnection();
		

	//$qryFT = "SELECT T1.*, (T2.status) as status, (SELECT company_name FROM companies WHERE row_id = T4.fk_company) as empresa, (T4.row_id) boxid FROM workflow T1 INNER JOIN wf_status T2 ON T1.fk_status = T2.row_id INNER JOIN pickup T3 ON T1.wf_id = T3.fk_barcode LEFT JOIN objects T4 ON T3.fk_box = T4.row_id WHERE T1.fk_status NOT IN (1,16,17) ORDER BY T2.status DESC, T3.fk_barcode LIMIT 0,".($limit).";";
	
	
	$qryRep = "SELECT T1.*, (T4.fk_company) as fk_company, (T2.status) as status, (COUNT(status)) as qty, (SUM(T4.qty)) as suma, (AVG(T5.ppc)) as precio, (SELECT company_name FROM companies WHERE row_id = T4.fk_company) as empresa FROM workflow T1 INNER JOIN wf_status T2 ON T1.fk_status = T2.row_id INNER JOIN pickup T3 ON T1.wf_id = T3.fk_barcode INNER JOIN objects T4 ON T3.fk_box = T4.row_id INNER JOIN objects T5 ON T4.fk_parent = T5.row_id  WHERE T4.fk_obj_type = 2 GROUP BY T2.status, T4.fk_company ORDER BY T1.fk_status ASC";
	
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qryRep);
	
	
	/*
	'<dl class="accordion" data-accordion>
  <dd class="accordion-navigation">
  <a href="#panel1">Accordion 1</a>
    
	<div id="panel1" class="content active">
      <dl class="tabs" data-tab>
        <dd class="active"><a href="#panel2-1">Tab 1</a></dd>
        <dd><a href="#panel2-2">Tab 2</a></dd>
        <dd><a href="#panel2-3">Tab 3</a></dd>
        <dd><a href="#panel2-4">Tab 4</a></dd>
      </dl>
      <div class="tabs-content">
        <div class="content active" id="panel2-1">
          <p>First panel content goes here...</p>
        </div>
        <div class="content" id="panel2-2">
          <p>Second panel content goes here...</p>
        </div>
        <div class="content" id="panel2-3">
          <p>Third panel content goes here...</p>
        </div>
        <div class="content" id="panel2-4">
          <p>Fourth panel content goes here...</p>
        </div>
      </div>
    </div>
  </dd>'
  */
	$intCnt = 1;
	if (mysql_num_rows($res)) {
		echo '<table><tbody><thead><tr><th width=\"20%\">Status</th><th width=\"20%\">Quantity</th><th width=\"20%\">Amount</th><th width=\"40%\">Company</th></tr></thead>';
		//echo '<dl class="accordion" data-accordion>';
		//echo '<table><tbody><thead><tr><th width=\"30%\">Status</th><th width=\"30%\">Quantity</th><th width=\"40%\">Company</th></tr></thead>';
		while ($row = mysql_fetch_array($res)) {
			//echo '<a href="#panel'.$intCnt.'"><table><dd class="accordion-navigation">';
			echo '<tr><td width="20%"  class="accordion">';
			echo ''.$row['status'].'</td>';
			echo '<td width="20%">'.$row['qty'].'</td>';
			
			if ($row['suma']>0) {
				setlocale(LC_MONETARY, 'en_US');
				echo '<td width="20%">'.money_format('%i', ($row['suma'] * $row['precio']) ).'</td>';
			} else {
				echo '<td width="20%" class="subheader">';
				$qryAvg = "SELECT *, (COUNT(*)) as cant, (SUM(qty)) as suma FROM objects WHERE fk_obj_type = 2 AND fk_status = 5 and fk_company = " . $row['fk_company'];
				//echo $qryAvg;
				mysql_query("SET NAMES UTF8");
				$resAvg = mysql_query($qryAvg);
				if (mysql_num_rows($resAvg)) {
					while ($rowAvg = mysql_fetch_array($resAvg)) {
						setlocale(LC_MONETARY, 'en_US');
						//echo money_format('%i', ($rowAvg['cant'] / $rowAvg['suma']) );
						echo money_format('%i',((($rowAvg['suma'] / $rowAvg['cant']) * $row['precio']) * $row['qty']));
					}
				}
				echo '</td>';
			}
			echo '<td width="40%">'.$row['empresa'].'</td>';
			
			echo '</tr>';
			
			/*
			// Aca va el detalle
			echo '<div id="panel'.$intCnt.'" class="content">';
			echo '<table><tbody><thead><tr><th width=\"30%\">Status</th><th width=\"30%\">Quantity</th><th width=\"40%\">Company</th></tr></thead>';
			echo '<tr><td width=30%>';
			echo $row['status'].'</td>';
			echo '<td width="30%">'.$row['qty'].'</td>';
			echo '<td width="20%">'.$row['empresa'].'</td>';
			echo '</tr></td></table>'; 
			echo '</div>';
			*/
			//echo '</dd>';
			$intCnt = $intCnt + 1;
		}
		echo "</tbody></table>";
		//echo '</dl>';
		
	} else {
			echo $antes;
			echo "No results";
			echo $despues;
	}
			
	
	echo $despues;
	
	ConnectionFactory::close();


?>
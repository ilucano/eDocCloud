<?php
require_once '/var/www/html/config.php';

require_once $arrIni['base'].'inc/check.php';
require_once $arrIni['base'].'lib/db/db.php' ;
require_once $arrIni['base'].'inc/general.php';
require_once $arrIni['base'].'inc/checkACL.php';

require_once $arrIni['base'].'inc/users.class.php';
require_once $arrIni['base'].'inc/filemarks.class.php';
require_once $arrIni['base'].'inc/files.class.php';
require_once $arrIni['base'].'inc/companies.class.php';
$objFiles = new Files;
$objUsers = new Users;
$objCompanies = new Companies;

session_start();

$action = $_GET['action'];
$year = $_GET['year'];
$alphabet = $_GET['alphabet'];

$companyCode = $objUsers->userCompany();
$company_res = $objCompanies->getCompany($companyCode);
$company_name = $company_res['company_name'];
		
		
		

switch ($action)
{
	case "listyear":
		
		$row = $objFiles->listCountByYears($companyCode);
		
		$list_result = '';
		foreach($row as $list)
		{
			if($list['file_year'] == null || $list['file_year'] == '') {
				$file_year_label = 'Unknown';
			}
			else {
				$file_year_label = $list['file_year'];
			}
			$file_count = $list['num'];
			
			$list_result .= '<li><a data-list-year="'.$file_year.'" href="#">'. $file_year_label .' ('.$file_count.')</a></li>';
			
		}
		
		$html = '<div style="width: 50%">
					<ul class="breadcrumbs">
						<li class="current">%company_name%</li>
					</ul>
				</div>';
				
		$html .= '<h4>List by Year</h3>';
		
		$html .= '<ul class="inline-list">
					%list_result%
				  </ul>';
				  
		
		$html = str_replace(array('%company_name%', '%list_result%'),
							array($company_name, $list_result),
							$html);
									
		
		echo $html;
	
	break;
	

	case "listalphabet":
		
		$row = $objFiles->listCountByAlphabet($companyCode, $year);
	    
		
		$list_result = '';
		foreach($row as $list)
		{
			$alphabet = $list['alpha'];
			$file_count = $list['num'];
			
			$list_result .= '<li><a data-list-file="'.$alphabet.'" data-list-alpha-year="'.$year.'" href="#">'. $alphabet .' ('.$file_count.')</a></li>';
			
		}
		 
		$html = '<h4>List by Alphabet</h3>';
		
		$html .= '<ul class="inline-list">
					%list_result%
				  </ul>';
				  
		
		$html = str_replace(array('%list_result%'),
							array($list_result),
							$html);
									
		
		echo $html;
		
		
	break;

	

	case "listfile":
		
		$result = $objFiles->listFileByAlphabet($companyCode, $year, $alphabet);
	    
		
		$list_result = '';
				
		
		$group_permission = GetUserPermission();
		
		$show_file_marker  = ($group_permission['use_file_marker']['view'] == 1 || $group_permission['use_file_marker']['change'] == 1) ? true : false;
		
		if($show_file_marker == true) {
			$marker_header = '<th>Marks</th>';
		}
		
 
		foreach($result as $row)
		{
		    
			$str_filename = '<a href="lib/data/file.download.php?fileid='.$row['row_id'].'" target="_blank">'.$row['filename'].'</a>';

			
			$tr = '<tr>
			    <td><input type="checkbox" id="checkbox_%id%" class="case" name="case" value="%id%" /></td>
                <td>%filename%</td>';
				
			if($show_file_marker == true) {
				$tr .= '<td>%marker%</td>';
				$str_marker = dropDownButton($row['row_id'], $row['file_mark_id']);
			}
			$tr .= '<td>%file_year%</td><td>%creation_date%</td>
					<td>%modification_date%</td>
					<td>%pages%</td>
					<td>%size%</td>
				</tr>';
			
			
			$bytes = $row['filesize'];

			if ($bytes < 1048576) {
				$bytes = number_format($row['filesize'] / 1024,2).' Kb';
			} else {
				$bytes = number_format($row['filesize'] / 1024 / 1024,2).' Mb';
			}
			
			$str_file_year = dropDownYearButton($row['row_id'], $row['file_year']);
			
			$list_result .= str_replace(array('%id%',
									'%filename%',
									'%marker%',
									'%file_year%',
									'%creation_date%',
									'%modification_date%',
									'%pages%',
									'%size%'),
							  array($row['row_id'],
									$str_filename,
									$str_marker,
									$str_file_year,
									date("m/d/Y G:i:s",strtotime($row['creadate'])),
									date("m/d/Y G:i:s",strtotime($row['moddate'])),
									$row['pages'],
									$bytes
									),
							  $tr);
			
		}
		
		$html = '<h4>List of files</h3>';
		$html .= '<table id="list-file-table" class="display" cellspacing="0" width="70%">';
		
		$html .= '<thead>
					<tr>
						<th style="text-align: left;"> <input type="checkbox" id="selectall" /> </th>
						<th>Filename</th>' . $marker_header . '
						<th>Year</th>
						<th>Creation Date</th>
						<th>Modifcation Date</th>
						<th>Pages</th>
						<th>Size</th>
					</tr>
				</thead>';

        $html .= '<tbody>';
		
		$html .= '%list_result%';
				  
		$html .= '</tbody>';
		
		$html .= '</table>';
		
							  
		$html .=  '		<script>
						$(function(){
					  
						 // add multiple select / deselect functionality
						 $("#selectall").click(function () {
							   $(\'.case\').prop(\'checked\', this.checked);
						 });
					  
						 // if all checkbox are selected, check the selectall checkbox
						 // and viceversa
						 $(".case").click(function(){
					  
							 if($(".case").length == $(".case:checked").length) {
								   $("#selectall").prop("checked", true);
							 } else {
								 $("#selectall").prop("checked", false);
							 }
							
							   });
						});
					 </script>
					 <style>
					  .f-year-dropdown { max-height: 150px; height: 150px; overflow: auto;}
					 </style>
				';
				
		$html = str_replace(array('%list_result%'),
							array($list_result),
							$html);
									
		
		echo $html;
		
		
	break;

	
	
}



function dropDownYearButton($row_id, $file_year)
{
	
 
	if($file_year == '')
	{
		$file_year = "(Unknown)";
	}
	
	$drop_down_list = '';
   
   
   
    for ($i = 1990; $i <= date("Y"); $i++) {

		$drop_down_list .= '<li><a class="set-year" data-set-year-id="'.$row_id.'" data-set-year-value="'.$i.'">'.$i.'</a></li>';
		
	}
	$drop_down_list .= '<li><a class="set-year" data-set-year-id="'.$row_id.'" data-set-year-value="">Unknown</a></li>';
	
	$drop_down_list = '<ul style="max-height: 50px;" id="drop'.$row_id.'" data-dropdown-content class="f-year-dropdown f-dropdown" aria-hidden="true" tabindex="-1">'.$drop_down_list.'</ul>';
	
	
	return '<button id="set-year-button'.$row_id.'" href="#" data-dropdown="drop'.$row_id.'" aria-controls="drop'.$row_id.'" aria-expanded="false" class="'.$disabled_class.'tiny button dropdown">'.$file_year.'</button><br>' .$drop_down_list;

}



function dropDownButton($row_id, $mark_id)
{
	
	$group_permission = GetUserPermission();
	
	$objFilemarks = new Filemarks;
	$objUsers = new Users;
	$label = $objFilemarks->getLabelById($mark_id);
	
	if($label == '')
	{
		$label = "(No Mark)";
	}
	
	$drop_down_list = '';
	$disabled_class = "disabled secondary ";
	
    if( $group_permission['use_file_marker']['change'] == 1 ) {
		
			$disabled_class = '';
			
			$filter = " AND global = :global";
			
			$array_bind[':global'] = '1'; //fk_empresa = global share
			
			$res = $objFilemarks->listFilemarks($filter, $array_bind);
			
			$company_filter = " AND fk_empresa = :fk_empresa AND global = :global";
			
			$company_array_bind[':fk_empresa'] =  $objUsers->userCompany();
			
			$company_array_bind[':global'] = '0';
			
			$company_res = $objFilemarks->listFilemarks($company_filter, $company_array_bind);
			
		    
			
			if (count($res) >= 1 || count($company_res) >= 1) {
				
				foreach ($res as $row) {
		
					$drop_down_list .= '<li><a class="set-filemarker" data-set-filemark-id="'.$row_id.'" data-set-filemark-value="'.$row['id'].'">'.$row['label'].'</a></li>';
					
				}
				
				foreach ($company_res as $row) {
					$drop_down_list .= '<li><a class="set-filemarker" data-set-filemark-id="'.$row_id.'" data-set-filemark-value="'.$row['id'].'">'.$row['label'].'</a></li>';
				}

				$drop_down_list .= '<li><a class="set-filemarker" data-set-filemark-id="'.$row_id.'" data-set-filemark-value=""> <i>Remove Mark</i></a></li>';
	
			}
			$drop_down_list = '<ul id="drop'.$row_id.'" data-dropdown-content class="f-dropdown" aria-hidden="true" tabindex="-1">'.$drop_down_list.'</ul>';
	
	}

	return '<button id="set-filemark-button'.$row_id.'" href="#" data-dropdown="drop'.$row_id.'" aria-controls="drop'.$row_id.'" aria-expanded="false" class="'.$disabled_class.'tiny button dropdown">'.$label.'</button><br>' .$drop_down_list;

}

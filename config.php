<?php

// Leo las variables generales
$arrIni = parse_ini_file('/opt/eDocCloud/general.config');

// Cambios en las variables
$arrIni['foundationurl'] = $arrIni['http'].$_SERVER['SERVER_NAME'].$arrIni['foundationurl'];
$arrIni['logourl'] = $arrIni['http'].$_SERVER['SERVER_NAME'].$arrIni['logourl'];
$arrIni['dbinc'] = $arrIni['http'].$_SERVER['SERVER_NAME'].$arrIni['dbinc'];
$arrIni['base'] = $arrIni['base'];
$inMaint = '';




$permissionList = array('application' 	=> array('label' => 'Application',
											   'code' => array('main' => 'Main'),
											   'script' => array('main' => '/main.php')
											  ),
						'workflow' 		=> array('label' => 'Workflow',
												'code' => array('pickup' => 'Pickup',
																'preparation' => 'Preparation',
																'scan' => 'Scan',
																'qa' => 'QA',
																'ocr' => 'OCR'),
												'script' => array('pickup' => '/admin/wf_pick.php',
																'preparation' => '/admin/prep.php',
																'scan' => '/admin/scan.php',
																'qa' => '/admin/qa.php',
																'ocr' => '/admin/ocr.php')
										   ),
						'reports' 		=> array('label' => 'Reports',
												 'code' => array('all_boxes'  => 'All Boxes',
																 'group_by_status' => 'Group By Status'),
												 'script' => array('all_boxes'  => '/admin/inproc.php',
																 'group_by_status' => '/admin/report01.php')
												),
						'admin_menu'	=> array('label' => 'Admin Menu',
												 'code' => array(
																 'company' => 'Company',
																 'users' => 'Users',
																 'groups' => 'Groups',
																 'orders' => 'Orders',
																 'pickup' => 'Pickup',
																 'box'	=> 'Box',
																 'chart' => 'Chart',
																 'file' => 'File',
																 'barcode' => 'Barcode',
																 'audit'	=> 'Activity Logs'),
												 'script' => array(
																 'company' => '/admin/company.php',
																 'users' => '/admin/users.php',
																 'groups' => '/admin/groups.php',
																 'orders' => '/admin/orders.php',
																 'pickup' => '/admin/pickup.php',
																 'box'	=> '/admin/box.php',
																 'chart' => '/admin/chart.php',
																 'file' => '/admin/file.php',
																 'barcode' => '/admin/barcode.php',
																 'audit'	=> '/admin/audit.php'),
												 
												 ),
						'user_menu'		=> array('label' => 'User Menu',
												 'code' => array('home' => 'Home',
																 'orders' => 'Orders',
																 'search' => 'Search',
																 'change_password' => 'Change Password'),
												  'script' => array('home' => '/main.php',
																'orders' => '/orders.php',
																'search' => '/search.php',
																'change_password' => '/chgpwd.php')
																										
												 ),
                        'use_file_marker' => array('label' => 'File Marker',
                                                   'code' => array('view' => 'Can view file\'s marker',
                                                                   'change' => 'Can change file\'s marker'),
                                                   'scripts' => array('view' => null,
                                                                      'change' => null)
                                                   )
						);


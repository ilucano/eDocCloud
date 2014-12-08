<?php

$group_permission = GetUserPermission();
 
//if ($_SESSION['VisAdmin']!='X') {
//	die;
//}

require_once $arrIni['base'].'inc/users.class.php';

$objUsers = new Users;

?>

<div class="row">
<div class="fixed"> 
<nav class="top-bar" data-topbar role="navigation" data-options="sticky_on: large">
  <ul class="title-area">
    <li class="name">
      <h1><a href="#">imagingXperts.com</a></h1>
    </li>
     <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
  </ul>
    
  <section class="top-bar-section">
    <!-- Right Nav Section -->
    <ul class="right">

<?php 
      
      echo '<li class=\"has-dropdown\"><a href="../main.php">Application</a><ul class=\"dropdown\">';
	  // Cargo el Menu Admin
	  
	  echo '</ul></li>';
	  
?>
	
	<?php if( is_array($group_permission['workflow']) && count($group_permission['workflow']) > 0): ?>
       <!-- work flow -->
	  <li class="has-dropdown"> 
        <a href="#">Workflow</a>
        <ul class="dropdown">
			<?php if($group_permission['workflow']['pickup'] == 1 ): ?>
			   <li<?php if ($page=="pick") { echo ' class="active"'; } ?>><a href="wf_pick.php">Pickup</a></li>
			<?php endif ?>
		  
			<?php if($group_permission['workflow']['preparation'] == 1 ): ?>
				<li<?php if ($page=="prep") { echo ' class="active"'; } ?>><a href="prep.php">Preparation</a></li>
			 <?php endif ?>
		   
			<?php if($group_permission['workflow']['scan'] == 1 ): ?>
				<li<?php if ($page=="scan") { echo ' class="active"'; } ?>><a href="scan.php">Scan</a></li>
			<?php endif ?>
			 
			<?php if($group_permission['workflow']['qa'] == 1 ): ?>
				<li<?php if ($page=="qa") { echo ' class="active"'; } ?>><a href="qa.php">QA</a></li>
			<?php endif ?>
			<?php if($group_permission['workflow']['ocr'] == 1 ): ?>
				 <li<?php if ($page=="ocr") { echo ' class="active"'; } ?>><a href="ocr.php">OCR</a></li>
			<?php endif ?>
        </ul>
      </li>
	  
	<?php endif ?>
 
	<?php if( is_array($group_permission['reports']) && count($group_permission['reports']) > 0): ?>
       <!-- reports-->
	   
      <li class="has-dropdown">
        <a href="#">Reports</a>
        <ul class="dropdown">
			
			<?php if($group_permission['reports']['all_boxes'] == 1 ): ?>
				<li<?php if ($page=="inproc") { echo ' class="active"'; } ?>><a href="inproc.php">All Boxes</a></li>
	        <?php endif ?>
			
			<?php if($group_permission['reports']['group_by_status'] == 1 ): ?>
				<li<?php if ($page=="report01") { echo ' class="active"'; } ?>><a href="report01.php">Group By Status</a></li>
			<?php endif ?>
        </ul>
      </li>
	  
	<?php endif ?>
	
	
		<?php if( is_array($group_permission['admin_menu']) && count($group_permission['admin_menu']) > 0): ?>
	
		<li class="has-dropdown">
		  <a href="#">Admin Menu</a>
		  <ul class="dropdown">
			<?php if($group_permission['admin_menu']['home'] == 1 ): ?>
				<li<?php if ($page=="main") { echo ' class="active"'; } ?>><a href="main.php">Home</a></li>
	        <?php endif ?>
			
			<?php if($group_permission['admin_menu']['company'] == 1 ): ?>
				<li<?php if ($page=="company") { echo ' class="active"'; } ?>><a href="company.php">Company</a></li>
			<?php endif ?>
			
			<?php if($group_permission['admin_menu']['users'] == 1 ): ?>
				<li<?php if ($page=="users") { echo ' class="active"'; } ?>><a href="users.php">Users</a></li>
			<?php endif ?>
			
			<?php if($group_permission['admin_menu']['groups'] == 1 ): ?>
				<li<?php if ($page=="groups") { echo ' class="active"'; } ?>><a href="groups.php">Groups</a></li>
			<?php endif ?>
			
			<?php if($group_permission['admin_menu']['orders'] == 1 ): ?>
				<li<?php if ($page=="orders") { echo ' class="active"'; } ?>><a href="orders.php">Orders</a></li>
			<?php endif ?>
			
			<?php if($group_permission['admin_menu']['pickup'] == 1 ): ?>
				<li<?php if ($page=="pickup") { echo ' class="active"'; } ?>><a href="pickup.php">Pickup</a></li>
			<?php endif ?>
			
			<?php if($group_permission['admin_menu']['box'] == 1 ): ?>
				<li<?php if ($page=="box") { echo ' class="active"'; } ?>><a href="box.php">Box</a></li>
			<?php endif ?>
			
			<?php if($group_permission['admin_menu']['chart'] == 1 ): ?>
				<li<?php if ($page=="chart") { echo ' class="active"'; } ?>><a href="chart.php">Chart</a></li>
			<?php endif ?>
			
			<?php if($group_permission['admin_menu']['file'] == 1 ): ?>
				<li<?php if ($page=="file") { echo ' class="active"'; } ?>><a href="file.php">File</a></li>
			<?php endif ?>
	 
			<li<?php if ($page=="filemarks") { echo ' class="active"'; } ?>><a href="filemarks.php">File Markers</a></li>
	 
			
			<?php if($group_permission['admin_menu']['barcode'] == 1 ): ?>
				<li<?php if ($page=="barcode") { echo ' class="active"'; } ?>><a href="barcode.php">Barcode</a></li>
			<?php endif ?>
			
			
			<?php if($group_permission['admin_menu']['audit'] == 1 ): ?>
				<li<?php if ($page=="audit") { echo ' class="active"'; } ?>><a href="audit.php">Activity Logs</a></li>
			<?php endif ?>
			
			<!-- <li<?php if ($page=="search") { echo ' class="active"'; } ?>><a href="search.php">Search</a></li>
			<li<?php if ($page=="chgpwd") { echo ' class="active"'; } ?>><a href="chgpwd.php">Change Password</a></li> -->
		  </ul>
		</li>
		<?php endif ?>
		
				
		<?php if($objUsers->isCompanyAdmin() == true) :?>
		    <li class="has-dropdown"><a href="#">Company Admin</a>
				<ul class="dropdown">
					<li<?php if ($page=="myusers") { echo ' class="active"'; } ?>><a href="myusers.php">My Users</a></li>
					<li<?php if ($page=="mygroups") { echo ' class="active"'; } ?>><a href="mygroups.php">My Groups</a></li>
					<li<?php if ($page=="myfilemarks") { echo ' class="active"'; } ?>><a href="myfilemarks.php">My File Markers</a></li>
				</ul>
			</li>
		<?php endif ?>
    </ul>
    
    <!-- Left Nav Section -->
    <ul class="left">
      <li><a href="../logout.php">Logout</a></li>
    </ul>
  </section>
</nav>
</div>
</div>
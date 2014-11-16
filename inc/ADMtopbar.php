<?php

$group_permission = GetUserPermission();
 
//if ($_SESSION['VisAdmin']!='X') {
//	die;
//}

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
      if ($_SESSION['VisAdmin']=='X') {
      echo '<li class=\"has-dropdown\"><a href="../main.php">Application</a><ul class=\"dropdown\">';
	  // Cargo el Menu Admin
	  
	  echo '</ul></li>';
	  }
?>
	<?php if( is_array($group_permission['workflow']) && count($group_permission['workflow']) > 0): ?>
       <!-- work flow -->
	  <li class="has-dropdown"> 
        <a href="#">Workflow</a>
        <ul class="dropdown">
          <li<?php if ($page=="pick") { echo ' class="active"'; } ?>><a href="wf_pick.php">Pickup</a></li>
          <li<?php if ($page=="prep") { echo ' class="active"'; } ?>><a href="prep.php">Preparation</a></li>
          <li<?php if ($page=="scan") { echo ' class="active"'; } ?>><a href="scan.php">Scan</a></li>
          <li<?php if ($page=="qa") { echo ' class="active"'; } ?>><a href="qa.php">QA</a></li>
          <li<?php if ($page=="ocr") { echo ' class="active"'; } ?>><a href="ocr.php">OCR</a></li>
        </ul>
      </li>
	  
	<?php endif ?>
 
	<?php if( is_array($group_permission['reports']) && count($group_permission['reports']) > 0): ?>
       <!-- work flow -->
	   
      <li class="has-dropdown">
        <a href="#">Reports</a>
        <ul class="dropdown">
          <li<?php if ($page=="inproc") { echo ' class="active"'; } ?>><a href="inproc.php">All Boxes</a></li>  
          <li<?php if ($page=="report01") { echo ' class="active"'; } ?>><a href="report01.php">Group By Status</a></li>  
        </ul>
      </li>
	  
	<?php endif ?>
	
      <li class="has-dropdown">
        <a href="#">Admin Menu</a>
        <ul class="dropdown">
          <li<?php if ($page=="main") { echo ' class="active"'; } ?>><a href="main.php">Home</a></li>
          <li<?php if ($page=="company") { echo ' class="active"'; } ?>><a href="company.php">Company</a></li>
          <li<?php if ($page=="users") { echo ' class="active"'; } ?>><a href="users.php">Users</a></li>
		  <li<?php if ($page=="groups") { echo ' class="active"'; } ?>><a href="groups.php">Groups</a></li>
          <li<?php if ($page=="orders") { echo ' class="active"'; } ?>><a href="orders.php">Orders</a></li>
          <li<?php if ($page=="pickup") { echo ' class="active"'; } ?>><a href="pickup.php">Pickup</a></li>
          <li<?php if ($page=="box") { echo ' class="active"'; } ?>><a href="box.php">Box</a></li>
          <li<?php if ($page=="chart") { echo ' class="active"'; } ?>><a href="chart.php">Chart</a></li>
          <li<?php if ($page=="file") { echo ' class="active"'; } ?>><a href="file.php">File</a></li>
          <li<?php if ($page=="barcode") { echo ' class="active"'; } ?>><a href="barcode.php">Barcode</a></li>
          
          <!-- <li<?php if ($page=="search") { echo ' class="active"'; } ?>><a href="search.php">Search</a></li>
          <li<?php if ($page=="chgpwd") { echo ' class="active"'; } ?>><a href="chgpwd.php">Change Password</a></li> -->
        </ul>
      </li>
    </ul>

    <!-- Left Nav Section -->
    <ul class="left">
      <li><a href="../logout.php">Logout</a></li>
    </ul>
  </section>
</nav>
</div>
</div>
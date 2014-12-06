<?php

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
      if ($_SESSION['VisAdmin']=='X' || $objUsers->isCompanyAdmin() == true ) {
		echo '<li><a href="admin/main.php">Administration</a></li>';
	  }
?>      
      <li class="has-dropdown">
        <a href="#">User Menu</a>
        <ul class="dropdown">
          <li<?php if ($page=="main") { echo ' class="active"'; } ?>><a href="main.php">Home</a></li>
          <li<?php if ($page=="orders") { echo ' class="active"'; } ?>><a href="orders.php">Orders</a></li>
          <li<?php if ($page=="search") { echo ' class="active"'; } ?>><a href="search.php">Search</a></li> 
          <li<?php if ($page=="chgpwd") { echo ' class="active"'; } ?>><a href="chgpwd.php">Change Password</a></li> 
          <?php
		  
          	if ($_SESSION['Vcadm']=='X') {
			 ?>
            <!-- <li<?php if ($page=="usradm") { echo ' class="active"'; } ?>><a href="usradm.php">User Admin</a></li> -->
             <?php 
			}
		  
		  ?>
        </ul>
      </li>
	  

    </ul>

    <!-- Left Nav Section -->
    <ul class="left">
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </section>
</nav>
</div>
</div>
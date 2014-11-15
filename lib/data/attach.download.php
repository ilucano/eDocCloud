<?php

session_start();

require_once '/var/www/html/config.php';


require_once $arrIni['base'].'inc/general.php';
require_once $arrIni['base'].'inc/checkACL.php';

$fileid = basename( $_GET['fileid'] );

$con = ConnectionFactory::getConnection();

$qry = "SELECT * FROM attachs WHERE row_id = ".$fileid;
	//echo $qry;
	$res = mysql_query($qry);
	//echo 'PUT';
	while ($row = mysql_fetch_array($res)) {
		//echo 'PUT'.$row['orden'];
		//echo $_SESSION['Vid'];
		if ($_SESSION['VisAdmin']=='X') {
			//echo 'entro';
			$filename = $row['attach_path'];
			//echo $filename;
		} else {
			//echo 'error';
			if ($_SESSION['CoCo']==1) {
				$filename = $row['attach_path'];
			} else {
				$filename = "error";
			}
		}
		
	}
	
	//echo "Ahi Va:".$filename;
//
if( file_exists( $filename ) ) {
    /** 
     * Send some headers indicating the filetype, and it's size. This works for PHP >= 5.3.
     * If you're using PHP < 5.3, you might want to consider installing the Fileinfo PECL
     * extension.
     */
	//echo "IN";
    $finfo = finfo_open( FILEINFO_MIME );
    header( "Content-Disposition: attachment; filename=\"" . basename( $filename ) . "\"");
    header( 'Content-Type: ' . finfo_file( $finfo, $filename ) );
    header( 'Content-Length: ' . filesize( $filename ) );
	header( 'Accept-Ranges: bytes' );
    header( 'Expires: 0' );
    finfo_close( $finfo );

    /**
     * Now clear the buffer, read the file and output it to the browser.
     */
    ob_clean( );
    flush( );
    readfile( $filename );
    exit;
}

ConnectionFactory::close();

//header( 'HTTP/1.1 404 Not Found' );

echo "<h1>File not found</h1>";
exit;

?>
<script>
window.close();
</script>
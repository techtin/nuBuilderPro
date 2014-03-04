<?php 
	require_once('nucommon.php'); 

	if (isset($_GET['p'])){

		$values  = array($_GET['p']);
		$sql     = "SELECT slp_php FROM zzzsys_php WHERE slp_code = ? AND slp_nonsecure = '1' ";
		$rs      = nuRunQuery($sql, $values);
		$num     = db_num_rows($rs);

		if ($num == 1) {
		
			$r   = db_fetch_object($rs);
			$e   = 	nuReplaceHashes($r->slp_php, $_GET);

			eval($e); 
			
		} else {
		
			echo "Request is not allowed";
			
		}

	} else {

		echo "Request format is invalid";
		
	}	
?>

<?php 
	require_once('nucommon.php'); 

	if (isset($_GET['p'])){

		$values  = array($_GET['p']);
		$sql     = "SELECT zzzsys_php_id, slp_php FROM zzzsys_php WHERE slp_code = ? AND slp_nonsecure = '1' ";
		$rs      = nuRunQuery($sql, $values);
		$num     = db_num_rows($rs);

		if ($num == 1) {
		
			$r   = db_fetch_object($rs);

			if ( $_SESSION['SafeMode'] === true ) {
                		$file = $r->zzzsys_php_id.'_'.slp_php;
                		$r->slp_php = nuGetSafePHP($file);
        		}

			$e   = 	nuReplaceHashes($r->slp_php, $_GET);

			eval($e); 
			
		} else {
		
			echo "Request is not allowed";
			
		}

	} else {

		echo "Request format is invalid";
		
	}	
?>

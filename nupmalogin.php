<?php
require_once('config.php');
$session_id = $_REQUEST['sessid'];

$db = new PDO("mysql:host=$nuConfigDBHost;dbname=$nuConfigDBName;charset=utf8", $nuConfigDBUser, $nuConfigDBPassword, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$values = array($session_id, 'globeadmin');
$sql = "SELECT * FROM zzzsys_session WHERE zzzsys_session_id = ? AND sss_zzzsys_user_id = ?";
$obj = $db->prepare($sql);
$obj->execute($values);
$recordObj = $obj->fetch(PDO::FETCH_OBJ);
$result = $obj->rowCount();
$page = 'nulogout.php';

if ( $result == 1 ) {

	$then = $recordObj->sss_timeout;
	$now  = time();
	$diff = bcsub($now, $then, 0);

	if ($diff < 1800) {
		session_name('PHPSESSIDNUBUILDERPRO');
		session_start();
		$page = 'phpmyadmin/index.php?server=1';
		setcookie("DBName", $nuConfigDBName, time()+1800);
		setcookie("DBHost", $nuConfigDBHost, time()+1800);
		$_SESSION['PMA_single_signon_user']             = $nuConfigDBUser;
		$_SESSION['PMA_single_signon_password']         = $nuConfigDBPassword;
		$_SESSION['PMA_single_signon_host']             = $nuConfigDBHost;
		$_SESSION['PMA_single_signon_port']             = "3306";
		$page = 'phpmyadmin/index.php?server=1';
	}
}
header("Location: $page")
?>

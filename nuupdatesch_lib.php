<?
class nuupdatesch {

        var $sqlErrors       	= array();
	var $tables_updated	= array();
	var $DBHost		= '';			
        var $DBName		= '';
        var $DBUserID		= '';
        var $DBPassWord		= '';
	var $zzsys_only		= true;

	function update() {

		$db	= $this->DBName;
		$sql 	= "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$db' ";
		$rs 	= $this->runQuery($sql);

		while($obj = mysql_fetch_object($rs)) {

			$thisTablePrefix = substr($obj->TABLE_NAME, 0, 7);

			if ( $this->zzsys_only ) {
				if ( $thisTablePrefix == 'zzzsys_' ) {
					$this->execute($obj->TABLE_NAME);
				}
			} else {
				$this->execute($obj->TABLE_NAME);
			}
		}
	}

	function execute($table) {

		$sql = "ALTER TABLE $table ENGINE = MYISAM";
                $this->runQuery($sql);

		$sql = "ALTER TABLE $table DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ";
                $this->runQuery($sql);

                $sql = "ALTER TABLE $table CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci ";
                $this->runQuery($sql);

                array_push($this->tables_updated, $table);
	}

	function runQuery($pSQL) {

                $con 		= mysql_connect($this->DBHost, $this->DBUserID, $this->DBPassWord) or die ("Could not connect to database\n");
                mysql_select_db($this->DBName,$con) or die ("Could not select database\n");
                $rs 		= mysql_query($pSQL);

                if ( "" !=  mysql_error($con) ) {
                        $errors[0] = mysql_errno($con);
                        $errors[1] = mysql_error($con);
                        $errors[2] = $pSQL;
                        array_push($this->sqlErrors, $errors);
                }

                return $rs;
        }
}
?>

<?php
        require_once("config.php");
	require_once("nuupdatesch_lib.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv='Content-type' content='text/html;charset=UTF-8'>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<title>nuBuilder</title>
<style>
.nuShadeHolder {
	border-bottom-left-radius:5px;
	border-bottom-right-radius:5px;
	border-top-right-radius:5px;
	border-top-left-radius:5px;
	box-shadow: 5px 5px 5px #888888;
	position:absolute;
	top:70px;
	left:0px;
	margin:auto;
	width:auto; 
	height:auto
}
</style>
<body bgcolor="#CCCCCC">
	<div class="nuShadeHolder" style="width: 500px; height: 300px; top: 15px; left: 15px; border: 1px solid grey; position: absolute; background-color: rgb(255, 255, 255);">
		<span style="top: 15px; left: 20px;  text-align: center; font-size: 25px; font-family: sans-serif; position: absolute; background-color: rgb(255, 255, 255);"> 
			<strong>
<span style="color: #78C13A; text-shadow: 1px 1px #D0D7D1;">nu</span><span style="color: #03AAE9; text-shadow: 1px 1px #D0D7D1;">Builder</span><span style="color: #FB0003; text-shadow: 1px 1px #D0D7D1;"><i>Pro</i></span>
			</strong>
		</span>
		<span style="top: 55px; left: 20px;  text-align: left; font-size: 15px; font-family: sans-serif; position: absolute; background-color: rgb(255, 255, 255);">
			<h3>Convert <small>ENGINE/CHARACTER SET/COLLATION</small></h3>
			<form method="POST" action="">
				<table>
        				<tr>
						<td>Globeadmin Password</td>
						<td><input type="password" name="pwd"></td>
					</tr>
					<tr>
                                                <td>ENGINE</td>
                                                <td>MYISAM</td>
                                        </tr>
					<tr>
                                                <td>CHARACTER SET</td>
                                                <td>utf8</td>
                                        </tr>
					<tr>
                                                <td>COLLATION</td>
                                                <td>utf8_general_ci</td>
                                        </tr>
					<tr>
                                		<td>Table to Change</td>
                                		<td>
                                        		<select name="tables_to_change">
							<?php
								if ( $_POST['tables_to_change'] == 'all' ) {
									$all = "selected";
									$zz  = "";	
								} else {
									$all = "";
                                                                        $zz  = "selected";
								}
							?>
                                        		<option value="all" <?php echo $all; ?>>All Tables</option>
                                        		<option value="zzzsys" <?php echo $zz; ?>>Just zzzsys_ Tables</option>
                                        		</select>
                                		</td>
                        		</tr>

				</table>
				<input type="submit" value="Run" style="width: 60px; height: 35px; top: 150px; left: 350px; position: absolute; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px; font-size: 22px; line-height: 22px; background-color: rgb(212, 206, 230);">
			</form>
		</span>
	</div>

	<?php

	if ( isset($_POST['pwd']) &&  $_POST['pwd'] != $nuConfigDBGlobeadminPassword ) {

                echo "<div class=\"nuShadeHolder\" style=\"width: 500px; height: 40px; top: 330px; left: 15px; border: 1px solid grey; position: absolute; background-color: rgb(255, 255, 255);\">";
                        echo "<span style=\"top: 10px; left: 20px;  text-align: left; font-size: 15px; font-family: sans-serif; position: absolute; background-color: rgb(255, 255, 255);\">";
                                echo "Globadmin password incorrect";
                        echo "</span>";
                echo "</div>";

        }
	
	if ( $_POST['pwd'] == $nuConfigDBGlobeadminPassword ) {

		$nuupdatesch 			= new nuupdatesch();

		$nuupdatesch->DBHost 		= $nuConfigDBHost;
		$nuupdatesch->DBName 		= $nuConfigDBName;
		$nuupdatesch->DBUserID 		= $nuConfigDBUser;
		$nuupdatesch->DBPassWord 	= $nuConfigDBPassword;

		if ( $_POST['tables_to_change'] == 'all' ) {
			$nuupdatesch->zzsys_only = false;
                } else {
			$nuupdatesch->zzsys_only = true;
		}

		$nuupdatesch->update();

                $height = '1100px';
                $width  = '900px';

                echo "<div style=\"width: $width; height: $height; top: 330px; left: 15px; position: absolute; background-color: #CCCCCC;\">";
                        echo "<span style=\"top: 10px; left: 20px;  text-align: left; font-size: 15px; font-family: sans-serif; position: absolute; background-color: #CCCCCC;\">";

				echo "Tables updated: <br>";
				for ( $x = 0; $x < count($nuupdatesch->tables_updated); $x++ ) {
					echo $nuupdatesch->tables_updated[$x]."<br>";				
				}
				
				if ( count($nuupdatesch->sqlErrors) > 0 ) {
					echo "Errors: <br>";
					for ( $x = 0; $x < count($nuupdatesch->sqlErrors); $x++ ) {
                                        	echo $nuupdatesch->sqlErrors[$x][1]."<br>";
        	                        }
				}

                         echo "</span>";
                echo "</div>";
	}
	?>

</body>
</html>

<?php
	ob_start();

	require_once("config.php");

	define('NU_CACHE_TIME_STAMP', 'Y_m_d_H');

	define('GITCACHE',	'GITCACHE');
	define('GITNEW',	'GITNEW');
	define('GITERROR',	'GITERROR');

	define('GIT','http://gitcache.nubuilder.net/contents/');
        define('RAW','http://gitcache.nubuilder.net/master/');

	define('NUAGENT', 'nuSoftware/nuBuilderPro/AutoUpdater');	

	$download_dest = dirname(__FILE__).'/tmp/';
	$copy_dest     = dirname(__FILE__).'/';

	define('DOWNLOAD_DEST', $download_dest);
	define('COPY_DEST',     $copy_dest);

	$exclude_files 	= array('ReadMe.md','ajax-loader.gif','apple-touch-icon.png','config.php','nuBuilder-Logo-medium.png','numove_black.png','numove_red.png','nurefresh_black.png');
	$folders	= array('','nusafephp');
	$files_list	= array();
	$errors		= array();
	$success	= array();
	$cache		= array();
	$dbupdate	= array();
	$finalResult 	= array( 'message'=>'', 'errors'=>array(), 'success'=>array(), 'cache'=>array(), 'dbupdate'=>array() );
	$login 		= checkGlobeadmin($nuConfigDBHost, $nuConfigDBName, $nuConfigDBUser, $nuConfigDBPassword);

	if ( 0 == errorCount() ) {	
		try {
    			$writeable = checkIsWriteable($folders, $download_dest, $copy_dest);
		} catch (Exception $e) {
			setError("Exception trying to test file permissions");
		}
	}
	
	if ( 0 == errorCount() ) {
		$tmp_folder	= setupTmpFolder($folders);
	}

	if ( 0 == errorCount() ) {
		$cache		= setupGitCacheFolder($folders);
	}

	define('GIT_CACHE_DEST', $cache[0]);

	if ( 0 == errorCount() ) {
		for ( $x=0; $x < count($folders); $x++ ) {
			buildFilesList($cache, $files_list, GIT, $exclude_files, $folders[$x]);
		}
	}

	if ( 0 == errorCount() && $cache[1] == GITNEW ) {
		downloadFiles($files_list);
	}	

	if ( 0 == errorCount() ) {
		backupFiles($tmp_folder, $files_list);
	}

	if ( 0 == errorCount() ) {
                copyFiles($files_list);
        }

	if ( 0 == errorCount() ) {
		$dbupdate = updateDB($nuConfigDBHost, $nuConfigDBName, $nuConfigDBUser, $nuConfigDBPassword);
        }

	if ( errorCount() > 0 ) {
		$finalResult['message'] = 'ERRORS';
	} else {
		$finalResult['message'] = 'SUCCESS';
	}

	$successCount 			= count($success);
	$finalResult['errors'] 		= $errors;
	$finalResult['success']		= array("$successCount File(s) updated");
	$finalResult['cache'] 		= $cache;
	$finalResult['dbupdate']	= $dbupdate;

	$json = json_encode($finalResult);

	ob_flush();
	flush();

	header('Content-Type: application/json');
	echo $json;

function checkGlobeadmin($nuConfigDBHost, $nuConfigDBName, $nuConfigDBUser, $nuConfigDBPassword) {

	$login = false;
	$session_id = $_REQUEST['sessid'];

	$db = new PDO("mysql:host=$nuConfigDBHost;dbname=$nuConfigDBName;charset=utf8", $nuConfigDBUser, $nuConfigDBPassword, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$values = array($session_id, 'globeadmin');
	$sql = "SELECT * FROM zzzsys_session WHERE zzzsys_session_id = ? AND sss_zzzsys_user_id = ?";
	$obj = $db->prepare($sql);
	$obj->execute($values);
	$recordObj = $obj->fetch(PDO::FETCH_OBJ);
	$result = $obj->rowCount();

	if ( $result == 1 ) {
        	$then = $recordObj->sss_timeout;
        	$now  = time();
        	$diff = bcsub($now, $then, 0);
        	if ($diff < 1800) {
			$login = true;
        	}
	}

	if (!$login) {
		setError("Not Logged in as globeadmin");
	}

	unset($obj);
	unset($db);
	return $login;
}

function updateDB($nuConfigDBHost, $nuConfigDBName, $nuConfigDBUser, $nuConfigDBPassword) {

	$result = array();
        require_once("nuinstall_lib.php");

        $template = new nuinstall();
        $template->setDB($nuConfigDBHost, $nuConfigDBName, $nuConfigDBUser, $nuConfigDBPassword);
        $template->removeColumns = true;
        $template->removeIndexes = true;

        $template->run();
	$result = $template->returnArrayResults();
	return $result;	
}

function checkIsWriteable($folders, $download_dest, $copy_dest) {

	$errors = 0;
        if ( !is_writable($download_dest) ) {
		setError("Download destination is not writable: $download_dest");
		$errors++;
	}

	if ( !is_writable($copy_dest) ) {
                setError("Copy destination is not writable: $download_dest");
		$errors++;
	}

	for ( $x=0; $x < count($folders); $x++ ) {

		$folder = $folders[$x];
		if ( $folder != '' ) {
			$folder .= '/';
		}
		$search_folder = $copy_dest.$folder;

		if ($handle = opendir($search_folder)) {
                	while (false !== ($entry = readdir($handle))) {
                        	if ($entry != "." && $entry != "..") {
					$search_file = "$search_folder$entry";
                                	if ( is_file($search_file) ) {
						if ( $entry[0] != '.' ) {
							if ( !is_writable($search_file) ) {
                						setError("File is not writable: $search_file");
								$errors++;
							}
						}
                                	}	
                        	}
                	}
                closedir($handle);
        	}
	}

	if ( $errors > 0 ) {
		return false;
	} else {
		return true;
	}
}

function checkSubFolders($folders, $folder) {

        for ($x=0; $x < count($folders); $x++) {
                $this_folder = $folder.'/'.$folders[$x];
                if ( !is_dir($this_folder) ) {
                        setError("Error checking sub folder: $this_folder");
                }
        }
}

function setupSubFolders($folders, $folder) {

	for ($x=0; $x < count($folders); $x++) {
		$this_folder = $folder.'/'.$folders[$x];
		@mkdir($this_folder, 0755);
		if ( !is_dir($this_folder) ) {
			setError("Error creating sub folder: $this_folder");
		}
	}	
}

function setupGitCacheFolder($folders) {

	date_default_timezone_set(@date_default_timezone_get());
        $objDateTime    = new DateTime('NOW');
        $dateStr        = $objDateTime->format(NU_CACHE_TIME_STAMP);
        $cache_folder   = DOWNLOAD_DEST.'GIT_'.$dateStr.'/';
	$result		= array();
	$result[0] 	= $cache_folder;
	$result[2]	= 'CACHE_TIME_'.$dateStr;

	// if folder exists return folder name	
	if ( is_dir($cache_folder) ) {

		// assume we have a cache to work with
		$result[1] = GITCACHE;
		checkSubFolders($folders, $cache_folder);
	
	} else {

		// if folder does not exists
        	@mkdir($cache_folder, 0755);
	
		// make sure the mkdir was successful	
        	if ( is_dir($cache_folder) ) {
			$result[1] = GITNEW;
			setupSubFolders($folders, $cache_folder);

        	} else {
			$result[1] = GITERROR;
			setError("Error creating cache folder: $cache_folder");
        	}
	}

	return $result;
}

function setupTmpFolder($folders) {

	date_default_timezone_set(@date_default_timezone_get());
	$objDateTime 	= new DateTime('NOW');
	$dateStr 	= $objDateTime->format(DateTime::ISO8601);
	$tmp_folder	= DOWNLOAD_DEST.$dateStr;
	$tmp_folder 	= str_replace(":", "_", $tmp_folder);

	@mkdir($tmp_folder, 0755);	

	if ( is_dir($tmp_folder) ) {
		setupSubFolders($folders, $tmp_folder);
		return $tmp_folder;
	} else {
		setError("Error creating tmp folder: $tmp_folder");
	}
	
	return '';
}

function downloadFiles($files) {

	for ( $x=0; $x < count($files); $x++) {

		$file = $files[$x];

		@unlink($file->download_dest);
		@file_put_contents($file->download_dest, file_get_contents($file->raw_url), LOCK_EX);
		@$file->downloaded_size = filesize($file->download_dest);
				
		if ( $file->downloaded_size != $file->git_size ) {
			setError("Downloading $file->raw_url files sizes do not match $file->download_dest");
		}
	}
}

function backupFiles($tmp_folder, $files) {

	for ( $x=0; $x < count($files); $x++) {
                $file 	= $files[$x];
		$source = $file->copy_dest;
		$folder = $file->folder;
	
		if ( $folder == '' ) {
			$seperator = '';
		} else {
			$seperator = '/';
		}
		$dest   = $tmp_folder.'/'.$folder.$seperator.$file->name;	
		@unlink($dest);
                $copy 	= copy($source, $dest);
		if (!$copy) { 
			setError("Back up error: $dest");
		}
        }
}

function copyFiles($files) {

        for ( $x=0; $x < count($files); $x++) {

                $file  		= $files[$x];
		$this_error 	= 0;

                @unlink($file->copy_dest);

                $copy   = copy($file->download_dest, $file->copy_dest);

		if (!$copy) {
                        setError("Copy error: $file->copy_dest");
			$this_error++;	
                }

		$size = filesize($file->copy_dest);
                if ( $size != $file->git_size ) {
                        setError("Copy file size check error $file->copy_dest");
			$this_error++;
                }
	
		if ( $this_error == 0 ) {	
			setSuccess("Success: $file->name");
		}
        }
} 

function setError($msg) {
	global $errors;
	array_push($errors, $msg);
}

function setSuccess($msg) {
        global $success;
        array_push($success, $msg);
}

function errorCount() {
	global $errors;
	return count($errors);
}

function buildFilesList($cache, &$files_list, $git_url, $exclude_files, $folder = '') {

	if ( $cache[1] == GITNEW ) {
		buildFilesListFromGit($files_list, $git_url, $exclude_files, $folder);
	}

	if ( $cache[1] == GITCACHE ) {
		buildFilesListFromCache($files_list, $cache[0], $folder);
	}
}

function buildFilesListFromCache(&$files_list, $cache_folder, $folder) {

	if ( $folder == '' ) {
        	$seperator = '';
	} else {
        	$seperator = '/';
	}
	$search_folder = $cache_folder.$folder.$seperator;

	if ($handle = opendir($search_folder)) {
		while (false !== ($entry = readdir($handle))) {
        		if ($entry != "." && $entry != "..") {
				if ( is_file($search_folder.$entry) ) {	
					$gitObj = array();
					$gitObj['name'] = $entry;
					$gitObj['size'] = filesize($search_folder.$entry);
					$file = new nuFile($gitObj, $folder);
                                	array_push($files_list, $file);	
				}
        		}
    		}
   		closedir($handle);
	}
	return $files_list;
}

function buildFilesListFromGit(&$files_list, $git_url, $exclude_files, $folder = '') {

	if ( $folder != '') {
		$git_url = $git_url.$folder.'/';

	}

	$git = doCurl($git_url);

        if ($git[1] != '200') {
        	setError("Calling $git_url, status $git[1], error: $git[2] ");
	}

	$jsonGit = json_decode($git[0], true);

	for ( $x=0; $x < count($jsonGit); $x++) {
        	if ( $jsonGit[$x]['type'] != 'dir' ) {
                	if ( !in_array($jsonGit[$x]['name'],$exclude_files) ) {
                        	$file = new nuFile($jsonGit[$x], $folder);
                                array_push($files_list, $file);
                        }
                }
        }
	return $files_list;
}

function doCurl($url){

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, NUAGENT);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	$result[0] 	= curl_exec($ch);
	$result[1] 	= curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$result[2]	= curl_error($ch);
	curl_close($ch);

	return $result;
}

class nuFile {

	public $folder;
	public $name;
	public $git_size;
	public $raw_url;
	public $download_dest;
        public $copy_dest;
	public $downloaded_size;
        public $copied_size;
	
	function __construct($gitObj, $folder = '') {

		if ( $folder == '' ) {
			$seperator = '';
                } else {
			$seperator = '/';
                }

		$this->folder   	= $folder;
		$this->name 		= $gitObj['name'];
		$this->git_size 	= $gitObj['size'];
		$this->raw_url  	= RAW.$folder.$seperator.$gitObj['name'];
		$this->download_dest	= GIT_CACHE_DEST.$folder.$seperator.$gitObj['name'];
		$this->copy_dest	= COPY_DEST.$folder.$seperator.$gitObj['name'];
	}
}

function logger($msg) {
	$log = dirname(__FILE__).'/nuphpgit-errors.log';
	error_log($msg, 3, $log);
}

?>

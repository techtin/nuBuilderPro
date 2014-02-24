<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv='Content-type' content='text/html;charset=UTF-8'>

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes" />

<title>nuBuilder</title>

<link rel="apple-touch-icon" href="apple-touch-icon.png"/>

<link rel="stylesheet" href="jquery/jquery-ui.css" />
<script src="jquery/jquery-1.8.3.js" type='text/javascript'></script>
<script src="jquery/jquery-ui.js" type='text/javascript'></script>
<script src="//api.filepicker.io/v1/filepicker.js" type="text/javascript"></script>

<?php
require_once('config.php');
require_once('nucommon.php');

jsinclude('nuformat.js');
jsinclude('nucommon.js');
jsinclude('nueditform.js');
jsinclude('nubrowseform.js');

print $GLOBALS['nuSetup']->set_css;  //-- html header
$i = "";
$h = "";
$t = "";

if( array_key_exists('i', $_GET) ) {
    $i  = $_GET['i'];
}
if( array_key_exists('home', $_SESSION) ) {
    $h  = $_SESSION['home'];
}
if( array_key_exists('title', $_SESSION) ) {
    $t  = $_SESSION['title'];
}
$l  = nuGetLanguage();

$k1 = $GLOBALS['nuSetup']->set_inkfilepicker_key;

$de = $GLOBALS['nuSetup']->set_denied;


print "

<script>

window.nuDenied = '$de';

filepicker.setKey('$k1');

$l
    
function nuGetID(){ 
	return '$i';
}

function nuGetHome(){ 
	return '$h';
}

function nuGetTitle(){ 
	return '$t';
}

function nuHomeWarning(){

	if(nuFORM.edited == '1'){
		return 'Leave This Form Without Saving? Doing this will return you to the login screen.';
	}
    return 'Doing this will return you to the login screen.';
}

function nuWindowWarning(){

	if(nuFORM.edited == '1'){
		return 'Leave This Form Without Saving?';
	}
    return null;
}

window.onbeforeunload = nuHomeWarning;

</script>

";

?>


<script>

window.nuShiftKey    = false;
window.nuControlKey  = false;
window.nuTimeout     = false;
window.nuMoveable    = false;

$(document).ready(function() {

	$('title').html(nuGetTitle());

	var i            = nuGetID();

	window.nuSession = new nuBuilderSession();

	if(i === ''){                                                            //-- Main Desktop
		toggleModalMode();	
	}else{                                                                  //-- iFrame or new window
		var pSession  = nuGetParentSession();
		nuSession.setSessionID(pSession.nuSessionID);
		var w         = document.defaultView.parent.nuSession.getWindowInfo(i,pSession);
		
//-- added by sc 2014-01-24

		var alreadyDefined   = Array();

		for (var key in w){
			alreadyDefined.push(key);
		}
		
		for (var key in document.defaultView.parent.nuFORM){
			if(alreadyDefined.indexOf(key) == -1){
				w[key] = document.defaultView.parent.nuFORM[key];           //-- add values from parent values (so they can be used as hash variables)
			}
		}
		
//-- end added by sc			
			
		nuBuildForm(w);                                                     //-- Edit or Browse
                
	}

});


  
</script>
</head>
<body onkeydown="nuKeyPressed(event, true);" onkeyup="nuKeyPressed(event, false);">
</body>
</html> 

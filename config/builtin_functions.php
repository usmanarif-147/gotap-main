<?php

function redirect($path){
	echo "<script>location.href='".$path."'</script>"; die;
}

function redirectBack(){
	echo "<script> document.referrer ? window.history.back() : location.reload()</script>"; die;
}

function redirectBackWithError($error){
		$_SESSION['ot_error']=$error;
		redirectBack();
}

function safePrint($str){
	echo htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}



function csrf(){
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32)).rand(1,9);
	echo '<input type="hidden" value="'.$_SESSION['csrf_token'].'" name="csrf_token"></input>';
}

function pd($arr){
	echo "<pre>";
	print_r($arr);
	var_dump($arr);
	die;
}


function filterArray($var){
		return ($var !== NULL && $var !== FALSE && $var !== "" && $var !== "null");
}

function loadView($view){
	include __DIR__."/../Views/$view.php";
}

	
function imageExist($external_link)
{
    if (@getimagesize($external_link)) 
    {
        return 200;
    } 
    else 
    {
        return 201;
    }
}

function uuid()
{
	// Generate 16 bytes (128 bits) of random data or use the data passed into the function.
	$data = $data ?? random_bytes(16);
	assert(strlen($data) == 16);

	// Set version to 0100
	$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
	// Set bits 6-7 to 10
	$data[8] = chr(ord($data[8]) & 0x3f | 0x80);

	// Output the 36 character UUID.
	return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

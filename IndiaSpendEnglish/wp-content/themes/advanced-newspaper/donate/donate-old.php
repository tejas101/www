  <?php 

set_include_path('../lib'.PATH_SEPARATOR.get_include_path());
require_once('../lib/CitrusPay.php');
require_once 'Zend/Crypt/Hmac.php';

function generateHmacKey($data, $apiKey=null){
	$hmackey = Zend_Crypt_Hmac::compute($apiKey, "sha1", $data);
	return $hmackey;
}

$action = "donate.php";
$flag = "";

CitrusPay::setApiKey("d4491ed13a24943a08e7698883ab93f4bc295d6a",'production');

if(isset($_POST['submit']))
{
	$vanityUrl = "payonline";
	$currency = "INR";
	$merchantTxnId = $_POST['merchantTxnId'];
	$addressState = $_POST['addressState'];
	$addressCity = $_POST['addressCity'];
	$addressStreet1 = $_POST['addressStreet1'];
	$addressCountry = $_POST['addressCountry'];
	$addressZip = $_POST['addressZip'];
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$phoneNumber = $_POST['phoneNumber'];
	$email = $_POST['email'];
	$paymentMode = $_POST['paymentMode'];
	$issuerCode = $_POST['issuerCode'];
	$cardHolderName = $_POST['cardHolderName'];
	$cardNumber = $_POST['cardNumber'];
	$expiryMonth = $_POST['expiryMonth'];
	$cardType = $_POST['cardType'];
	$cvvNumber = $_POST['cvvNumber'];
	$expiryYear = $_POST['expiryYear'];
	$returnUrl = $_POST['returnUrl'];
	$orderAmount = $_POST['orderAmount'];
	$honoreename = $_POST['honoreename'];
	$flag = "post";
	$data = "$vanityUrl$orderAmount$merchantTxnId$currency";
	$secSignature = generateHmacKey($data,CitrusPay::getApiKey());
	$action = CitrusPay::getCPBase()."$vanityUrl";  
	$time = time()*1000;
	$time = number_format($time,0,'.','');
	/* $iscod = $_POST['COD']; */
	
	/*$customParamsName = $_POST['customParamsName'];*/
	/*$customParamsValue = $_POST['customParamsValue'];*/
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>IndiaSpend Donation</title>
<link href="css/default.css" rel="stylesheet" type="text/css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="http://cdn.webrupee.com/font">
<script src="http://cdn.webrupee.com/js" type="text/javascript"></script>
<style>
@font-face {
    font-family: 'rupee';
    src: url('rupee_foradian-1-webfont.eot');
    src: local('Ã¢ËœÂº'), url(data:font/truetype;charset=utf-8;base64,AAEAAAANAIAAAwBQRkZUTVen5G0AAADcAAAAHEdERUYAQAAEAAAA+AAAACBPUy8yRQixzQAAARgAAABgY21hcGmyCE0AAAF4AAABamdhc3D//wADAAAC5AAAAAhnbHlmmuFTtAAAAuwAABAoaGVhZPOmAG0AABMUAAAANmhoZWELSAQOAAATTAAAACRobXR4KSwAAAAAE3AAAABMbG9jYUCgSLQAABO8AAAAKG1heHAAFQP+AAAT5AAAACBuYW1lWObwcQAAFAQAAAIDcG9zdCuGzNQAABYIAAAAuAAAAAEAAAAAxtQumQAAAADIadrpAAAAAMhp2uoAAQAAAA4AAAAYAAAAAAACAAEAAQASAAEABAAAAAIAAAADAigBkAAFAAgFmgUzAAABGwWaBTMAAAPRAGYCEgAAAgAFAAAAAAAAAIAAAKdQAABKAAAAAAAAAABITCAgAEAAICBfBZr+ZgDNBrQBoiAAARFBAAAAAAAFnAAAACAAAQAAAAMAAAADAAAAHAABAAAAAABkAAMAAQAAABwABABIAAAADgAIAAIABgAgAFIAoCAKIC8gX///AAAAIABSAKAgACAvIF/////j/7L/ZeAG3+LfswABAAAAAAAAAAAAAAAAAAAAAAEGAAABAAAAAAAAAAECAAAAAgAAAAAAAAAAAAAAAAAAAAEAAAMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB//8AAgABAAAAAAO0BZwD/QAAATMVMzUhFTMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVIxUjNSMVIzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNSE1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1ITUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNSECTBAYATwEBAQEBAQEBAQEBAQEBAQEBAQEBAQQ2AQEBAQEBAQEBAQEBAQEBAT0BAQEBAQEBAQEBAQEBAQEBAQEBAQECJwEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAgEBAQECAQECAQIBAgECAgECAwICAgMCAwMEAwQFBAcHBAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBIAcMAwEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEsCAcEBAMDAwICAgICAgECAQIBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAT9/AQEBAQEBAQEBAQEBAQEBAQEBAQECAGYBAQEBAQEBAQEBAQEBAgECAQIBAwICAwIEBAYFCjwBAQEBAQEBAQEBAQEBAQEBAQEBAQECAH0BZwEBAQIBAgIBAgECAQIBAgIBAgECAQIBAQEDAgECAQIBAgECAwICAwQEAQEBAgECAQICAQIBAgECAgECAQICAQEBAgQEBAMDAgIDAQICAQICAQEBAgEBAgEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBBAECAQEBAgEBAgEBAQECAQECAQEBAgEBAQIBAQIBAQEBAQIBAgEBAgEBAQIBAQECAQEBAgEBAQIBAQEBAQIBAQIBAQECAQEBAgEBAgEBAQEBAgEBAgEBAgEBAQEBAgEBAgEBAQECAQECAQEBAgEBAQECAQECAQECAQEBAQIBAQEBAgEBAQEBAQECAQEBAQIBAQECAQEBAgEBAQIBAQECAQECAQEBAgECAQEBAgEBAQIBAQIBAQEBAgEBAgEBAgEBAQECAQECAQEBAQIBAQECAQECAQEBAQIBAQIBAQEBAQIBAQECAQEBAgEBAgEBAQEBAQIBAQECAQEBAQIBAQECAQEBAQEBAgIeAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQECAQECAQICAgIDAwIHBQMCAQICAQIBAgECAQICAQIBAgEBAQEDAgIBAgEBAgEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAgECAQIBAgECAQIBAgECAQICAQEBAQAAAAAAQAAAAADtAWcA/0AAAEzFTM1IRUzFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUjFSMVIxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFTMVMxUzFSMVIzUjFSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUhNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNSE1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUjNSM1IzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUzNTM1MzUhAkwQGAE8BAQEBAQEBAQEBAQEBAQEBAQEBAQEENgEBAQEBAQEBAQEBAQEBAQE9AQEBAQEBAQEBAQEBAQEBAQEBAQEBAicBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQIBAQEBAgEBAgECAQIBAgIBAgMCAgIDAgMDBAMEBQQHBwQBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBASAHDAMBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBLAgHBAQDAwMCAgICAgIBAgECAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQE/fwEBAQEBAQEBAQEBAQEBAQEBAQEBAgBmAQEBAQEBAQEBAQEBAQIBAgECAQMCAgMCBAQGBQo8AQEBAQEBAQEBAQEBAQEBAQEBAQEBAgB9AWcBAQECAQICAQIBAgECAQICAQIBAgECAQEBAwIBAgECAQIBAgMCAgMEBAEBAQIBAgECAgECAQIBAgIBAgECAgEBAQIEBAQDAwICAwECAgECAgEBAQIBAQIBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQQBAgEBAQIBAQIBAQEBAgEBAgEBAQIBAQECAQECAQEBAQECAQIBAQIBAQECAQEBAgEBAQIBAQECAQEBAQECAQECAQEBAgEBAQIBAQIBAQEBAQIBAQIBAQIBAQEBAQIBAQIBAQEBAgEBAgEBAQIBAQEBAgEBAgEBAgEBAQECAQEBAQIBAQEBAQEBAgEBAQECAQEBAgEBAQIBAQECAQEBAgEBAgEBAQIBAgEBAQIBAQECAQECAQEBAQIBAQIBAQIBAQEBAgEBAgEBAQECAQEBAgEBAgEBAQECAQECAQEBAQECAQEBAgEBAQIBAQIBAQEBAQECAQEBAgEBAQECAQEBAgEBAQEBAQICHgEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAgEBAgECAgICAwMCBwUDAgECAgECAQIBAgECAgECAQIBAQEBAwICAQIBAQIBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQIBAgECAQIBAgECAQIBAgECAgEBAQEAAAAAAEAAAABAACTKPMBXw889QALCAAAAAAAyGna6gAAAADIadrqAAAAAAO0BZwAAAAIAAIAAAAAAAAAAQAABrT+XgDeBZwAAAAAA7QAAQAAAAAAAAAAAAAAAAAAABMD9gAAAAAAAAKqAAAB/AAAA/YAAAH8AAACzgAABZwAAALOAAAFnAAAAd4AAAFnAAAA7wAAAO8AAACzAAABHwAAAE8AAAEfAAABZwAAAAAECgQKBAoECggUCBQIFAgUCBQIFAgUCBQIFAgUCBQIFAgUCBQIFAABAAAAEwP+AAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACgB+AAEAAAAAABMABQAAAAMAAQQJAAAAaAAFAAMAAQQJAAEACgBtAAMAAQQJAAIADgB3AAMAAQQJAAMADgCFAAMAAQQJAAQAGgCTAAMAAQQJAAUAVgCtAAMAAQQJAAYACgEDAAMAAQQJABMACgENAAMAAQQJAMgAbgEXUnVwZWUAVAB5AHAAZQBmAGEAYwBlACAAqQAgACgAeQBvAHUAcgAgAGMAbwBtAHAAYQBuAHkAKQAuACAAMgAwADEAMAAuACAAQQBsAGwAIABSAGkAZwBoAHQAcwAgAFIAZQBzAGUAcgB2AGUAZABSAHUAcABlAGUAUgBlAGcAdQBsAGEAcgB3AGUAYgBmAG8AbgB0AFIAdQBwAGUAZQAgAFIAZQBnAHUAbABhAHIAVgBlAHIAcwBpAG8AbgAgADEALgAwADAAIABKAHUAbAB5ACAAMQA1ACwAIAAyADAAMQAwACwAIABpAG4AaQB0AGkAYQBsACAAcgBlAGwAZQBhAHMAZQBSAHUAcABlAGUAUgB1AHAAZQBlAFQAaABpAHMAIABmAG8AbgB0ACAAdwBhAHMAIABnAGUAbgBlAHIAYQB0AGUAZAAgAGIAeQAgAHQAaABlACAARgBvAG4AdAAgAFMAcQB1AGkAcgByAGUAbAAgAEcAZQBuAGUAcgBhAHQAbwByAC4AAAIAAAAAAAD/JwCWAAAAAAAAAAAAAAAAAAAAAAAAAAAAEwAAAAEAAgADADUBAgEDAQQBBQEGAQcBCAEJAQoBCwEMAQ0BDgEPB3VuaTAwQTAHdW5pMjAwMAd1bmkyMDAxB3VuaTIwMDIHdW5pMjAwMwd1bmkyMDA0B3VuaTIwMDUHdW5pMjAwNgd1bmkyMDA3B3VuaTIwMDgHdW5pMjAwOQd1bmkyMDBBB3VuaTIwMkYHdW5pMjA1Rg==)
    format('truetype');
    font-weight: normal;
    font-style: normal;
}

</style>
<script>
$(document).ready(function(){
	$("#honoree-detail").hide();
  $("#honoree-box").click(function() {
    // this function will get executed every time the #home element is clicked (or tab-spacebar changed)
    if($(this).is(":checked")) // "this" refers to the element that fired the event
    {
        $("#honoree-detail").show();
    }
	else
	{
		 $("#honoree-detail").hide();
	}
}); 
  
});
</script>

<script>
$(document).ready(function() {
   $('input[type="radio"], input.amount_radio_button').click(function() {
   $('label.ak-radio-checked').removeClass('ak-radio-checked'); 
    $(this).parent('label').addClass('ak-radio-checked');
});
});
</script>

<script>
function clear_radio_buttons() {
$('input.amount_radio_button').attr('checked', false);

}
function clear_other() {
	var value = $( "input:radio[name=orderAmount]:checked" ).val();
	$('#amount_other_field').val(value);	
}

</script>

<script src="http://cdn.webrupee.com/js" type="text/javascript"></script>
</head>
<body>
<div id="page-header">
		<div class="page-wrap">
			<div class="logo-wrapper">
				<a href="http://www.indiaspend.com/"> <img src="images/India_Spend_Logo.png" alt="Citrus" />
				</a>
			</div>
		</div>
</div>

	<div id="page-client-logo">&#160;</div>
	<div id="page-wrapper">
		<div class="box-white">
			<div class="page-content">
	<form action="<?php echo $action;?>" method="POST"
		name="TransactionForm" id="transactionForm">

		<?php 
		if($flag == "post")
		{
			?>
		<p>
			<label> Transaction ID:</label><input name="merchantTxnId"
				type="text" value="<?php echo $merchantTxnId;?>" />
		</p>
		<p>
			<label> addressState:</label><input name="addressState" type="text"
				value="<?php echo $addressState;?>" />
		</p>
		<p>
			<label> addressCity:</label><input name="addressCity" type="text"
				value="<?php echo $addressCity;?>" />
		</p>
		<p>
			<label> addressStreet1:</label><input name="addressStreet1"
				type="text" value="<?php echo $addressStreet1;?>" />
		</p>
		<p>
			<label> addressCountry:</label><input name="addressCountry"
				type="text" value="<?php echo $addressCountry;?>" />
		</p>
		<p>
			<label> addressZip:</label><input name="addressZip" type="text"
				value="<?php echo $addressZip;?>" />
		</p>
		<p>
			<label> firstName:</label><input name="firstName" type="text"
				value="<?php echo $firstName;?>" />
		</p>
		<p>
			<label> lastName:</label><input name="lastName" type="text"
				value="<?php echo $lastName;?>" />
		</p>
		<p>
			<label> Mobile Number:</label><input name="phoneNumber" type="text"
				value="<?php echo $phoneNumber;?>" />
		</p>
		<p>
			<label> email:</label><input name="email" type="text"
				value="<?php echo $email;?>" />
		</p>
		<p>
			<label> paymentMode:</label><input name="paymentMode" type="text"
				value="<?php echo $paymentMode;?>" />
		</p>
		<p>
			<label> issuerCode:</label><input name="issuerCode" type="text"
				value="<?php echo $issuerCode;?>" />
		</p>
		<p>
			<label> cardHolderName:</label><input name="cardHolderName"
				type="text" value="<?php echo $cardHolderName;?>" />
		</p>
		<p>
			<label> cardNumber:</label><input name="cardNumber" type="text"
				value="<?php echo $cardNumber;?>" />
		</p>
		<p>
			<label> expiryMonth:</label><input name="expiryMonth" type="text"
				value="<?php echo $expiryMonth;?>" />
		</p>
		<p>
			<label> cardType:</label><input name="cardType" type="text"
				value="<?php echo $cardType;?>" />
		</p>
		<p>
			<label> cvvNumber:</label><input name="cvvNumber" type="text"
				value="<?php echo $cvvNumber;?>" />
		</p>
		<p>
			<label> expiryYear:</label><input name="expiryYear" type="text"
				value="<?php echo $expiryYear;?>" />
		</p>
		<p>
			<label> returnUrl:</label><input name="returnUrl" type="text"
				value="<?php echo $returnUrl;?>" />
		</p>
		<p>
			<label> amount:</label><input name="orderAmount"  type="text" value="<?php echo $orderAmount;?>" />
			<label> honoree:</label><input name="honoreename"  type="text" value="<?php echo $honoreename;?>" />
		</p>
		<p>
			Time: <input type="text" name="reqtime" value="<?php echo $time;?>" /> <input
				type="hidden" name="secSignature"
				value="<?php echo $secSignature;?>" /> <input type="hidden"
				name="currency" value="<?php echo $currency;?>" />
		</p>
		<!-- Custom parameter section starts here. 
		You can omit this section if no custom parameters have been defined.
		Hidden field value should be the name of the parameter created in Checkout settings page.
		It should follow customParams[0].name, customParams[1].name .. naming convention.
		For each custom parameter created, a text field with the naming convention  
		customParams[0].value,customParams[1].value .. should be captured.
		Please refer below code snippet for passing parameters to SSL Page.
		Uncomment the for loop after the PHP tag to pass parameters to SSL Page
		
		Also refer the else part of this loop to see how to capture Custom Params on your website
		
		
		 -->
		<!-- Code for COD --> 
		<!-- <p>
			<label> COD:</label><input name="COD" type="text"
				value="<?php //echo $iscod;?>" />
		</p> -->
		<?php 
			/* for($i=0;$i<count($customParamsName);++$i)
			{
			
			echo "<p><input type=\"hidden\" name=\"customParams[$i].name\" value=\"$customParamsName[$i]\" /></p>";
			echo "<p>$customParamsName[$i]: <input type=\"text\" name=\"customParams[$i].value\" value=\"$customParamsValue[$i]\" /></p>";
			} */
		}
		else
		{
			?><?php //echo uniqid(); ?>
		<div class="column1">	
		<ul class="form-wrapper add-merchant clearfix">
			<li class="clearfix" style="display:none;"> <label width="125px;">Transaction Number:</label><input class="text" name="merchantTxnId" type="text" value="<?php echo uniqid(); ?>" /></li>				
			
			<li class="clearfix"><input class="text" placeholder="First Name" name="firstName" type="text" required /></li>
			
			<li class="clearfix"><input class="text" placeholder="Last Name" id="lastName" name="lastName" type="text" required /></li>
			
			<li class="clearfix"> <input class="text" placeholder="Mobile Number" name="phoneNumber" type="tel" required /></li>
			
			<li class="clearfix"><input class="text" placeholder="Address"  name="addressStreet1" type="text"  required /></li>
			
			<li class="clearfix"> <input class="text" placeholder="City"  name="addressCity" type="text" required /></li>
			
			<li class="clearfix"><input class="text" placeholder="Pin Code"  name="addressZip" type="text" required /></li>
			
			<li class="clearfix"> <input class="text" placeholder="State"  name="addressState" type="text" required /></li>		
			
			<li class="clearfix"><input class="text" placeholder="Country"  name="addressCountry" type="text" required /></li>		
			
			<li class="clearfix"><input class="text" placeholder="Email"  name="email" type="email" required />
			</li>
		
		<!-- Custom parameter section starts here. 
		You can omit this section if no custom parameters have been defined.
		Hidden field value should be the name of the parameter created in Checkout settings page.
		An array of Custom Parameter's Name and Custom Parameters Value should be passed to the POST script.
		Please refer below code snippet for passing Custom parameters to the POST script Page.
		
		Once the parameters are passed through a text input field they are captured in the script mentioned 
		in the Action attribute of the Form
		-->
		<!-- <input type="hidden" name="customParamsName[]" value="Roll Number" />
		<p>
			Roll Number <input type="text" class="text" name="customParamsValue[]" value="" />
		</p>
		<input type="hidden" name="customParamsName[]" value="age" />
		<p>
			age <input type="text" class="text" name="customParamsValue[]" value="" />
		</p> -->
		
		
		<!-- COD section starts here 
		Uncomment the below cod section if COD to be sent from merchant site
		pass the values as 'Yes' or 'No'
		
		
		<li class="clearfix"><label width="125px;">Is COD:</label> 
			<select class="text" name="COD">
				<option value="">Select...</option>
				<option value="Yes">Yes</option>
				<option value="No">No</option>
			</select>
		</li>
		
		<!-- COD section END -->
		
			<li class="clearfix"><select class="text" name="paymentMode">
					<option value="">Select Payment Mode</option>
					<option value="NET_BANKING">NetBanking</option>
					<option value="CREDIT_CARD">Credit Card</option>
					<option value="DEBIT_CARD">Debit Card</option>
				</select>
			</li>
		
			<li class="clearfix"><select class="text" name="issuerCode">
				<option value="">Select Bank</option><option value="CID001">ICICI Bank</option>
				<option value="CID002">Axis Bank</option><option value="CID003">CityBank</option>
				<option value="CID004">Yes Bank</option><option value="CID005">SBI Bank</option>
				<option value="CID006">Deutsche Bank</option><option value="CID007">Union Bank</option>
				<option value="CID008">Indian Bank</option><option value="CID009">Federal Bank</option>
				<option value="CID010">HDFC Bank</option><option value="CID011">IDBI Bank</option>
				<option value="CID012">State Bank of Hyderabad</option><option value="CID013">State Bank of Bikaner and Jaipur</option>
				<option value="CID014">State Bank of Mysore</option><option value="CID015">State Bank of Travancore</option>
				<option value="CID016">Andhra Bank</option><option value="CID017">Bank of Bahrain & Kuwait</option>
				<option value="CID018">Bank of Baroda Corporate Accounts</option><option value="CID019">Bank of India</option>
				<option value="CID020">Bank of Baroda Retail Accounts</option><option value="CID021">Bank of Maharashtra</option>
				<option value="CID022">Catholic Syrian Bank</option><option value="CID023">Central Bank of India</option>
				<option value="CID024">City Union Bank</option><option value="CID020">Bank of Baroda Retail Accounts</option>
				<option value="CID021">Bank of Maharashtra</option><option value="CID022">Catholic Syrian Bank</option>
				<option value="CID023">Central Bank of India</option><option value="CID024">City Union Bank</option>
				<option value="CID025">Corporation Bank</option><option value="CID026">DCB Bank ( Development Credit Bank )</option>
				<option value="CID027">Indian Overseas Bank</option><option value="CID028">IndusInd Bank</option>
				<option value="CID029">ING Vysya Bank</option><option value="CID030">Jammu & Kashmir Bank</option>
				<option value="CID031">Karnataka Bank</option><option value="CID032">KarurVysya Bank</option>
				<option value="CID033">Kotak Mahindra Bank</option><option value="CID034">Lakshmi Vilas Bank NetBanking</option><option value="CID035">Oriental Bank of Commerce</option><option value="CID036">Punjab National Bank Corporate Accounts</option><option value="CID037">South Indian Bank</option><option value="CID038">Standard Chartered Bank</option><option value="CID039">Syndicate Bank</option><option value="CID040">Tamilnad Mercantile Bank</option><option value="CID041">United Bank of India</option><option value="CID042">Vijaya Bank</option>							
				</select>
			</li>
		
			<li class="clearfix"><input placeholder="Card Holder Name" class="text" name="cardHolderName"
				type="text" value="" />
				</li>
		
			<li class="clearfix"><input placeholder="Card Number" class="text" name="cardNumber" type="text"
				value="" />
				</li>
		
			<li class="clearfix"> 
				<div class="card-exp-date">
				<p style="font-size:16px;">Expiration Date </p>
				<select class="text exp-month" name="expiryMonth">
					<option value="">Month</option>
					<option value="1">Jan</option><option value="2">Feb</option><option value="3">Mar</option>
					<option value="4">Apr</option><option value="5">May</option><option value="6">Jun</option>
					<option value="7">Jul</option><option value="8">Aug</option><option value="9">Sep</option>
					<option value="10">Oct</option><option value="11">Nov</option><option value="12">Dec</option>
				</select>
				<select class="text exp-year" name="expiryYear">
					<option value="">Year</option>
					<option value="2014">2014</option><option value="2015">2015</option><option value="2016">2016</option>
					<option value="2017">2017</option><option value="2018">2018</option><option value="2019">2019</option>
					<option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option>
					<option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option>
					<option value="2026">2026</option><option value="2027">2027</option><option value="2028">2028</option>
					<option value="2029">2029</option><option value="2030">2030</option><option value="2031">2031</option>
					<option value="2032">2032</option><option value="2033">2033</option><option value="2034">2034</option>
					<option value="2035">2035</option><option value="2036">2036</option><option value="2037">2037</option>
					<option value="2038">2038</option><option value="2039">2039</option><option value="2040">2040</option>
					<option value="2041">2041</option><option value="2042">2042</option><option value="2043">2043</option>
					<option value="2044">2044</option><option value="2045">2045</option><option value="2046">2046</option>
					<option value="2047">2047</option><option value="2048">2048</option><option value="2049">2049</option>
					<option value="2050">2050</option><option value="2051">2051</option><option value="2052">2052</option>
					<option value="2053">2053</option><option value="2054">2054</option><option value="2055">2055</option>
					<option value="2056">2056</option><option value="2057">2057</option><option value="2058">2058</option>
					<option value="2059">2059</option><option value="2060">2060</option>
				</select>
				</div>
				<div class="cvv-no">
					<p style="font-size:16px;">CVV No. </p>
					<input class="text cvv-text" name="cvvNumber" type="password" value="" />	</div>
			</li>
			
		
			<li class="clearfix">
				<select class="text" name="cardType">
							<option value="">Card Type</option>
							<option value="VISA">VISA</option>
							<option value="Master Card">Master Card</option>
							<option value="Maestro Card">Maestro Card</option>
						</select>
			</li>
		
			
		</div>
			
				
		<div class="column2">
			<li class="clearfix"> <!--label width="125px;">Return Url:</label--><input class="text" name="returnUrl" type="hidden" value="http://www.indiaspend.com/donate/Response.php" /></li>
		
			<li class="clearfix"> <p style="font-size:18px;font-weight:bold">Donation Amount:</p>
				<label class="amnt-btn"><input onClick="clear_other();" class="text amount_radio_button" type="radio" name="orderAmount" value="250" /><span style="font-family:rupee;font-size:22px">R</span>250</label>
				<label class="amnt-btn"><input onClick="clear_other();" class="text amount_radio_button" type="radio" name="orderAmount" value="500" /><span style="font-family:rupee;font-size:22px">R</span>500</label>
				<label class="amnt-btn"><input onClick="clear_other();" class="text amount_radio_button" type="radio" name="orderAmount" value="1000" /><span style="font-family:rupee;font-size:22px">R</span>1000</label>
				<label class="amnt-btn"><span style="font-family:rupee;font-size:22px">R</span> <input placeholder="" onclick="clear_radio_buttons();" class="text" id="amount_other_field" name="orderAmount" type="text" value="" required /></label>
			</li>
			<!--li class="clearfix">
				<p><input type="checkbox" id="honoree-box"/>Donate in someone's honor.</p>
				<div id="honoree-detail">
					<ul>
						<li class="honoree">
						<input type="hidden" name="customParams[0].name" value="honoreename" />						
						<input placeholder="Honoree's Name" type="text" class="text" name="customParams[0].value" value="" />
						</li>						
						
						<li class="honoree"><input  placeholder="Honoree's Email" class="text" name="honoreeemail" type="text" value="" /></li>
						<li class="honoree"><textarea placeholder="Note to Honoree" class="text" name="msgtohonoree" type="text" value="" ></textarea></li>
					</ul>
				</div>
			</li-->
			</ul>
					<input type="submit" name="submit" class="btn-orange" value="Submit Donation"  /> <input
				type="reset" class="btn" name="reset" value="Cancel" />
		</div>	
		<div class="clearfix"> </div>
		<?php
		}
		?>
	</form>
	</div>
	
	<div style="padding-left: 700px; padding-bottom: 20px; padding-top: 20px;">
		<div>Copyrights © 2014 IndiaSpend.</div>
	</div>
	</div>
		<div id="footer">
			<div id="footer-links"> <a href="http://www.indiaspend.com/about" id="privacy-link">About</a> / <a href="http://www.indiaspend.com/contactus">Contact</a></div>

			<a href="http://www.indiaspend.com" id="footer-logo"><img height="32" width="81" src="images/India_Spend_Logo.png" alt="Citrus" /></a>
			<div class="clear"></div>
		</div>
	
	</div>
	<?php 
	if($flag == "post")
	{
	?>
	<script type="text/javascript">
		//$("input").attr("placeholder", "");
		document.getElementById("transactionForm").submit();
	</script>
	<?php 
	}
	?>
</body>
</html>

<?php
	/*
		Template Name: Response
	*/
?>
<?php
set_include_path('lib'.PATH_SEPARATOR.get_include_path());
require_once 'Zend/Crypt/Hmac.php';


function generateHmacKey($data, $apiKey=null){
	$hmackey = Zend_Crypt_Hmac::compute($apiKey, "sha1", $data);
	return $hmackey;
}
?>
<?php get_header(); ?>

<?php
$txnid = "";
$txnrefno = "";
$txnstatus = "";
$txnmsg = "";
$firstName = "";
$lastName = "";
$email = "";
$street1 = "";
$city = "";
$state = "";
$country = "";
$pincode = "";
$mobileNo = "";
$signature = "";
$reqsignature = "";
$data = "";
$txnGateway = "";
$paymentMode = "";
$maskedCardNumber = "";
$cardType = "";
$honoreename = "";

$flag = "dataValid";

//$session_start();
if(isset($_POST['TxId']))
{
	$txnid = $_POST['TxId'];
	$data .= $txnid;
	//$_SESSION['txnid'] = $txnid;
}
if(isset($_POST['TxStatus']))
{
	$txnstatus = $_POST['TxStatus'];
	$data .= $txnstatus;
	//$_SESSION['txnstatus'] = $txnstatus;
}
if(isset($_POST['amount']))
{
	$amount = $_POST['amount'];
	$data .= $amount;
}
if(isset($_POST['pgTxnNo']))
{
	$pgtxnno = $_POST['pgTxnNo'];
	$data .= $pgtxnno;
}
if(isset($_POST['issuerRefNo']))
{
	$issuerrefno = $_POST['issuerRefNo'];
	$data .= $issuerrefno;
}
if(isset($_POST['authIdCode']))
{
	$authidcode = $_POST['authIdCode'];
	$data .= $authidcode;
}
if(isset($_POST['firstName']))
{
	$firstName = $_POST['firstName'];
	$data .= $firstName;
}
if(isset($_POST['lastName']))
{
	$lastName = $_POST['lastName'];
	$data .= $lastName;
}
if(isset($_POST['pgRespCode']))
{
	$pgrespcode = $_POST['pgRespCode'];
	$data .= $pgrespcode;
}
if(isset($_POST['addressZip']))
{
	$pincode = $_POST['addressZip'];
	$data .= $pincode;
}
if(isset($_POST['signature']))
{
	$signature = $_POST['signature'];
}
/*signature data end*/

if(isset($_POST['TxRefNo']))
{
	$txnrefno = $_POST['TxRefNo'];
}
if(isset($_POST['TxMsg']))
{
	$txnmsg = $_POST['TxMsg'];
}
if(isset($_POST['email']))
{
	$email = $_POST['email'];
}
if(isset($_POST['customParams[0].honoreename']))
{
	$honoreename = $_POST['customParams[0].honoreename'];
}
if(isset($_POST['addressStreet1']))
{
	$street1 = $_POST['addressStreet1'];
}
if(isset($_POST['addressStreet2']))
{
	$street2 = $_POST['addressStreet2'];
}
if(isset($_POST['addressCity']))
{
	$city = $_POST['addressCity'];
}
if(isset($_POST['addressState']))
{
	$state = $_POST['addressState'];
}
if(isset($_POST['addressCountry']))
{
	$country = $_POST['addressCountry'];
}
if(isset($_POST['mandatoryErrorMsg']))
{
	$mandatoryerrmsg = $_POST['mandatoryErrorMsg'];
}
if(isset($_POST['successTxn']))
{
	$successtxn = $_POST['successTxn'];
}
if(isset($_POST['mobileNo']))
{
	$mobileNo = $_POST['mobileNo'];
}
if(isset($_POST['txnGateway']))
{
	$txnGateway = $_POST['txnGateway'];
}
if(isset($_POST['paymentMode']))
{
	$paymentMode = $_POST['paymentMode'];
}
if(isset($_POST['maskedCardNumber']))
{
	$maskedCardNumber = $_POST['maskedCardNumber'];
}
if(isset($_POST['cardType']))
{
	$cardType = $_POST['cardType'];
}
$respSignature = generateHmacKey($data,"c3e48c4dda6f160a68f880b76158058e5428af1a");

if($signature != "" && strcmp($signature, $respSignature) != 0)
{
	$flag = "dataTampered";
}

?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Payment Response</title>
<link href="<?php echo get_template_directory_uri(); ?>/donate/css/default.css" rel="stylesheet" type="text/css">
</head>

	<div id="container">

			<div id="main" class="fullwidth">
			<span id="bcrum"><?php gab_breadcrumb(); ?></span>
				<ul id="MerchantKitResponse" class="tbl-wrapper clearfix">
						<li class="tbl-header">
							<div class="tbl-col col-1">Txn Id</div>
							<div class="tbl-col col-2">Txn Ref No</div>
							<div class="tbl-col col-1">Txn Status</div>
							<div class="tbl-col col-1">Txn Message</div>
							<div class="tbl-col col-1">Amount</div>
						</li>
						<li>
							<div class="tbl-col col-1">
								<?php echo $txnid;?>
							</div>
							<div class="tbl-col col-2" style="height:18px;">
								<?php echo $txnrefno;?>
							</div>
							<div class="tbl-col col-1">
								<?php echo $txnstatus;?>
							</div>
							<div class="tbl-col col-1">
								<?php echo $txnmsg;?>
							</div>
							<div class="tbl-col col-1">
								<?php echo $amount;?>
							</div>
						</li>
					</ul>
					<h3>Consumer Details:</h3>
					<ul class="form-wrapper add-merchant clearfix">
						<li class="clearfix"><label>First Name: </label> <?php echo $firstName;?>
						</li>
						<li class="clearfix"><label>Last Name: </label> <?php echo $lastName;?>
						</li>
						<li class="clearfix"><label>Email: </label> <?php echo $email;?></li>
						<li class="clearfix"><label>Address: </label> <?php echo $street1;?>
						</li>
						<li class="clearfix"><label>City: </label> <?php echo $city;?></li>
						<li class="clearfix"><label>State: </label> <?php echo $state;?></li>
						<li class="clearfix"><label>Country: </label> <?php echo $country;?>
						</li>
						<li class="clearfix"><label>Zip Code: </label> <?php echo $pincode;?>
						</li>
						<li class="clearfix"><label>Mobile Number: </label> <?php echo $mobileNo;?>
						</li>
						<li class="clearfix"><label>Payment Mode: </label> <?php echo $paymentMode;?>
						</li>
						<li class="clearfix"><label>Transaction gateway: </label> <?php echo $txnGateway;?>
						</li>
						<li class="clearfix"><label>Masked Card Number: </label> <?php echo $maskedCardNumber;?>
						</li>
						<li class="clearfix"><label>Card Type: </label> <?php echo $cardType;?>
						</li>
						<!--li class="clearfix"><label>Honoree Name: </label--> <?php echo $honoreename;?>
						</li>
						
						<?php 
						/* Suppose a Custom parameter by name Roll Number Comes in Post Parameter.
						 * then we need to retreive the RollNumber as
						 * $rollNumber = $_POST['Roll Number'];
						 * and the display the response value as shown in below HTML This code 
						 * can be added n times for n number of Custom Parameters*/
						?>
						<!-- <li class="clearfix"><label>Roll Number </label> <?php //echo $rollNumber;?>
						</li>  -->
					</ul>
				
			</div><!-- #main -->
			<div class="clear"></div>
	</div><!-- #Container -->

<?php get_footer(); ?>
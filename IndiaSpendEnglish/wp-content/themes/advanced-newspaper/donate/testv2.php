<?php 
set_include_path('../lib'.PATH_SEPARATOR.get_include_path());
require_once('Citruspay.php');

function generateHmacKey($data, $apiKey=null){
	$hmackey = Zend_Crypt_Hmac::compute($apiKey, "sha1", $data);
	return $hmackey;
}

function curlRequest($data_string,$access_key,$signature){
	/* $absUrl = "http://localhost:8080/citruspay-admin-site/api/v2/txn/create"; */
	$absUrl = "https://sandboxadmin.citruspay.com/api/v2/txn/create";
	$curl = curl_init();
	$opts = array();
	$opts[CURLOPT_POST] = 1;
	$opts[CURLOPT_URL] = $absUrl;
	$opts[CURLOPT_RETURNTRANSFER] = true;
	$opts[CURLOPT_CONNECTTIMEOUT] = 25;
	$opts[CURLOPT_TIMEOUT] = 80;
	$opts[CURLOPT_RETURNTRANSFER] = true;
	$opts[CURLOPT_SSL_VERIFYPEER] = 0;
	$opts[CURLOPT_SSL_VERIFYHOST] = 0;
	$opts[CURLOPT_POSTFIELDS] = $data_string;
	$makeSslRequest = TRUE;
	$headers = array('Accept: application/json',
			'Content-Type: application/json',
			'access_key: '.$access_key,
			'signature: '.$signature);
	$opts[CURLOPT_HTTPHEADER] = $headers;
	curl_setopt_array($curl, $opts);
	$rbody = curl_exec($curl);
	$errno = curl_errno($curl);
	$message = curl_error($curl);
	if ($rbody === false) {
		$errno = curl_errno($curl);
		$message = curl_error($curl);
		if ($errno == CURLE_SSL_CACERT ||
				$errno == CURLE_SSL_PEER_CERTIFICATE ||
				$errno == 77 // CURLE_SSL_CACERT_BADFILE (constant not defined in PHP though)
		) {
			array_push($headers, 'X-CitrusPay-Client-Info: {"ca":"using CitrusPay-supplied CA bundle"}');
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($curl, CURLOPT_CAINFO,
					'/sandbox/php-driver/lib/CACerts/site.crt'); // This path has to be absolute wrt to server.
			/* curl_setopt($curl, CURLOPT_CAINFO,dirname(__FILE__) . '/../CACerts/site.crt'); */
			$rbody = curl_exec($curl);
			if ($rbody === false) {
				$errno = curl_errno($curl);
				$message = curl_error($curl);
			}
		}
	}
}

if(isset($_POST['submit']))
{
	$merchantAccessKey = "4SO1K4XHVR7FQFCJIGH5";
	$transactionID = $_POST['transactionID'];
	$bankName = $_POST['bankName'];
	$issuerCode = $_POST['issuerCode'];
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
	$cardHolderName = $_POST['cardHolderName'];
	$cardNumber = $_POST['cardNumber'];
	$expiryMonth = $_POST['expiryMonth'];
	$cardType = $_POST['cardType'];
	$cvvNumber = $_POST['cvvNumber'];
	$expiryYear = $_POST['expiryYear'];
	$returnUrl = $_POST['returnUrl'];
	$amount = $_POST['amount'];

	$postFields =array();
	$postFields['merchantTxnId'] = $transactionID;
	$postFields['amount'] = $amount;
	$postFields['firstName'] = $firstName;
	$postFields['lastName'] = $lastName;
	$postFields['address'] = $addressStreet1;
	$postFields['addressCity'] = $addressCity;
	$postFields['addressState'] = $addressState;
	$postFields['addressZip'] = $addressZip;
	$postFields['email'] = $email;
	$postFields['mobile'] = $phoneNumber;
	$postFields['paymentMode'] = $paymentMode;
	$postFields['issuerCode'] = $issuerCode;
	$postFields['returnUrl'] = $returnUrl;

	$data_string = json_encode($postFields);
	/* $data_string = '{"merchantTxnId":"1s21ibhaav","amount":"1.0","firstName":"Test","lastName":"Test","address":"Test","addressCity":"Pune","addressState":"Goa","addressZip":"123456","email":"swar@test.com","mobile":"1234567890","paymentMode":"NET_BANKING","issuerCode":"CID001","returnUrl":"http://localhost:1234/sandbox/php-driver/test/Response.php"}'; */
	//Sample JSON String
	/* {"merchantTxnId":"1s21ibhaav","amount":"1.0","firstName":"Test","lastName":"Test","address":"Test","addressCity":"Pune","addressState":"Goa","addressZip":"123456","email":"swar@test.com","mobile":"1234567890","paymentMode":"NET_BANKING","issuerCode":"CID001","returnUrl":"http://localhost:1234/sandbox/php-driver/test/Response.php"} */
	//My local merchant
	$access_key = "4SO1K4XHVR7FQFCJIGH5";
	$secretkey = "e38c1e7c552a89061b3401e413d1b6a0021a9974";
	/* $data = "$merchantAccessKey$amount$merchantTxnId$currency"; */
	$data = "merchantAccessKey=$access_key&transactionId=$transactionID&amount=$amount";
	$signature = generateHmacKey($data,$secretkey);
	curlRequest($data_string,$access_key,$signature);
	/* CitrusPay::setApiKey("a6d228d264111d6bbe30e9b719fb3b0048fdd3bb",'sandbox'); */
	/* $response = Transaction::create($tarr,CitrusPay::getApiKey()); */
	/* $redirectUrl = $response->get_redirect_url();
	 $response_code = $response->get_resp_code();
	if($redirectUrl != "" && $response_code == 200)
	{
	header("Location: $redirectUrl");
	} */
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Test Moto Transaction</title>
<link href="css/default.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div id="page-header">
		<div class="page-wrap">
			<div class="logo-wrapper">
				<a href="http://www.citruspay.com/"> <img height="32" width="81"
					src="images/logo_citrus.png" alt="Citrus" />
				</a>
			</div>
		</div>
	</div>

	<div id="page-client-logo">&#160;</div>
	<div id="page-wrapper">
		<div class="box-white">
			<div class="page-content">
				<div>
					<ul class="form-wrapper add-merchant clearfix">
						<form action="testv2.php" method="POST" name="TransactionForm">
							<li class="clearfix"><label width="125px;">Merchant Access Key:</label><input
								class="text" name="merchantAccessKey" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;">Transaction Number:</label>
								<input class="text" name="transactionID" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;">Bank Name:</label> <select
								class="text" name="bankName">
									<option value="">Select Bank</option>
									<option value="ICICI Bank">ICICI Bank</option>
									<option value="AXIS Bank">AXIS Bank</option>
									<option value="CITI Bank">CITI Bank</option>
									<option value="YES Bank">YES Bank</option>
									<option value="SBI Bank">SBI Bank</option>
									<option value="DEUTSCHE Bank">DEUTSCHE Bank</option>
									<option value="UNION Bank">UNION Bank</option>
									<option value="Indian Bank">Indian Bank</option>
									<option value="Federal Bank">Federal Bank</option>
									<option value="HDFC Bank">HDFC Bank</option>
									<option value="IDBI Bank">IDBI Bank</option>
							</select>
							</li>
							<li class="clearfix"><label width="125px;">Issuer Code:</label><input
								class="text" name="issuerCode" type="text" value="" />
							
							<li class="clearfix"><label width="125px;">State:</label><input
								class="text" name="addressState" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;">City:</label><input
								class="text" name="addressCity" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;">Address:</label><input
								class="text" name="addressStreet1" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;">Country:</label><input
								class="text" name="addressCountry" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;">Pin Code:</label><input
								class="text" name="addressZip" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;">First Name:</label><input
								class="text" name="firstName" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;">Last Name:</label><input
								class="text" name="lastName" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;">Mobile Number:</label><input
								class="text" name="phoneNumber" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;">Email:</label><input
								class="text" name="email" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;">Payment Mode:</label><select
								class="text" name="paymentMode">
									<option value="">Select Payment Mode</option>
									<option value="NET_BANKING">NetBanking</option>
									<option value="CREDIT_CARD">Credit Card</option>
									<option value="DEBIT_CARD">Debit Card</option>
							</select>
							</li>
							<li class="clearfix"><label width="125px;">Card Holder Name:</label><input
								class="text" name="cardHolderName" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;">Card Number:</label><input
								class="text" name="cardNumber" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;">Expiry Month:</label><input
								class="text" name="expiryMonth" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;">Card Type:</label><input
								class="text" name="cardType" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;">CVV Number:</label><input
								class="text" name="cvvNumber" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;">Expiry Year:</label><input
								class="text" name="expiryYear" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;">Return Url:</label><input
								class="text" name="returnUrl" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;">Amount:</label><input
								class="text" name="amount" type="text" value="" />
							</li>
							<li class="clearfix"><label width="125px;"></label> <input
								type="submit" name="submit" class="btn-orange"
								value="Test Transaction" /> <input type="reset" name="reset"
								class="btn" value="Cancel" />
							</li>

						</form>
					</ul>
				</div>
			</div>
			<div
				style="padding-left: 700px; padding-bottom: 20px; padding-top: 20px;">
				<div>Copyrights © 2012 Citrus.</div>
			</div>
		</div>
	</div>

</body>
</html>


<?php

class helper
{

	//
	// Get patment config
	//
	public static function getConfig( )
	{
		return array(
			"test" => array(
				"url"			=> "https://ws.merchanttest.firstdataglobalgateway.com/fdggwsapi/services",
				"http_auth" 	=> "WS1909449222._.1:PNtsyRYQ",
				"ssl_cert" 		=> "testing/WS1909449222._.1.pem",
				"ssl_key"		=> "testing/WS1909449222._.1.key",
				"ssl_key_pass"	=> "ckp_1423720666"
			),
			"live" => array(
				"url"			=> "https://ws.firstdataglobalgateway.com/fdggwsapi/services",
				"http_auth" 	=> "WS1001339284._.1:7tAAEfJb",
				"ssl_cert" 		=> "WS1001339284._.1.pem",
				"ssl_key"		=> "WS1001339284._.1.key",
				"ssl_key_pass"	=> "ckp_1423702785"
			)
		);
	}

	//
	// Build xml
	//
	public static function buildXml( $payment )
	{	
		try {

			$xml = '<?xml version="1.0" encoding="UTF-8"?>';
			$xml .= '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">';
			$xml .= '<SOAP-ENV:Header />';
			$xml .= '<SOAP-ENV:Body>';
			$xml .= '<fdggwsapi:FDGGWSApiOrderRequest xmlns:fdggwsapi="http://secure.linkpt.net/fdggwsapi/schemas_us/fdggwsapi">';
			$xml .= '<v1:Transaction xmlns:v1="http://secure.linkpt.net/fdggwsapi/schemas_us/v1">';
			$xml .= '<v1:CreditCardTxType>';
			$xml .= '<v1:Type>' . $payment->type . '</v1:Type>';
			$xml .= '</v1:CreditCardTxType>';
			$xml .= '<v1:CreditCardData>';
			$xml .= '<v1:CardNumber>' . $payment->ccNo . '</v1:CardNumber>';
			$xml .= '<v1:ExpMonth>' . $payment->ccExpiresMonth . '</v1:ExpMonth>';
			$xml .= '<v1:ExpYear>' . $payment->ccExpiresYear . '</v1:ExpYear>';
			$xml .= '</v1:CreditCardData>';
			$xml .= '<v1:Payment>';
			$xml .= '<v1:ChargeTotal>' . $payment->ccAmount . '</v1:ChargeTotal>';
			$xml .= '</v1:Payment>';
			$xml .= '</v1:Transaction>';
			$xml .= '</fdggwsapi:FDGGWSApiOrderRequest>';
			$xml .= '</SOAP-ENV:Body>';
			$xml .= '</SOAP-ENV:Envelope>';

			return $xml;
		
		} catch(Exception $e) {
			//redirect back to form and show message
			$error = $e-getMessage();
		}

	}
	
	//
	// Submit payment request
	//
	public static function submitPayment( $body, $config, $testing=false )
	{

		//print_r($body);
		$dir = dirname(__FILE__);
		$res = array( "res" => null, "status" => null, "error" => null );

		try {

			// storing the SOAP message in a variable – note that the plain XML code
			// is passed here as string for reasons of simplicity, however, it is
			// certainly a good practice to build the XML e.g. with DOM – furthermore,
			// when using special characters, you should make sure that the XML string
			// gets UTF-8 encoded (which is not done here):
			// initializing cURL with the FDGGWS API URL:
			$ch = curl_init($config['url']);
			// setting the request type to POST:
			curl_setopt($ch, CURLOPT_POST, 1);
			// setting the content type:
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
			// setting the authorization method to BASIC:
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			// supplying your credentials:
			curl_setopt($ch, CURLOPT_USERPWD, $config['http_auth']);
			// filling the request body with your SOAP message:
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

			// configuring cURL not to verify the server certificate:
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			// setting the path where cURL can find the client certificate:
			curl_setopt($ch, CURLOPT_SSLCERT, $dir . "/certs/" . $config['ssl_cert']);
			// setting the path where cURL can find the client certificate’s
			// private key:
			curl_setopt($ch, CURLOPT_SSLKEY, $dir . "/certs/" . $config['ssl_key']);
			// setting the key password:
			curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $config['ssl_key_pass']);

			// telling cURL to return the HTTP response body as operation result
			// value when calling curl_exec:
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			//exec and get result
			$res["res"] = curl_exec($ch); 
			$res["error"] = curl_error($ch);
            $res["status"] = curl_getinfo($ch,CURLINFO_HTTP_CODE); 
         	curl_close($ch);

         	return $res;

		} catch(Exception $e) {
			//redirect back to form and show message
			return $res["error"] = $e-getMessage();
		}
	}

	//
	//	Parse gatewat response
	//
	public static function parseResponse( $payment, $res )
	{

	}

	//
	// Send paymeny receipt
	//
	public static function sendReceiptEmail( $payment, $res )
	{
		// The message
		$message = "Thank you for your payment. " . "\r\n\r\n";
		$message .= "Amount: " . $paymeny->ccAmount . "\r\n";
		$message .= "Card: " . substr($paymeny->ccNo, -4) . "\r\n"; //only show last 4
		$message .= "Receipt: " . substr($paymeny->ccNo, -4) . "\r\n";

		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$message = wordwrap($message, 70, "\r\n");

		// Send
		mail($payment->email, 'LWS Charge Receipt - ' . $paymeny->transactionReceipt, $message);
	}

}

?>
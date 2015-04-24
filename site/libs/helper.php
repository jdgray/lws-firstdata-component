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
				"ssl_key_pass"	=> "ckp_1423720666",
				"email"			=> "jonathon@thesnippetapp.com",
				"success_msg"	=> "Payment received. Thank you. An email receipt will be emailed to %s.",
				"error_msg"		=> "Error. Payment unsuccessful. Please go back and try again."
			),
			"live" => array(
				"url"			=> "https://ws.firstdataglobalgateway.com/fdggwsapi/services",
				"http_auth" 	=> "WS1001339284._.1:7tAAEfJb",
				"ssl_cert" 		=> "WS1001339284._.1.pem",
				"ssl_key"		=> "WS1001339284._.1.key",
				"ssl_key_pass"	=> "ckp_1423702785",
				"email"			=> "personnel@languageworldservices.com",
				"success_msg"	=> "Payment received. Thank you. An email receipt will be emailed to %s.",
				"error_msg"		=> "Error. Payment unsuccessful. Please go back and try again."
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
					$xml .= '<v1:CardCodeValue>' . $payment->ccCode . '</v1:CardCodeValue>';
				$xml .= '</v1:CreditCardData>';
				$xml .= '<v1:Payment>';
					$xml .= '<v1:ChargeTotal>' . $payment->ccAmount . '</v1:ChargeTotal>';
				$xml .= '</v1:Payment>';
				$xml .= '<v1:TransactionDetails>';
					$xml .= '<v1:Recurring>No</v1:Recurring>';
					$xml .= '<v1:InvoiceNumber>' . $payment->invoice . '</v1:InvoiceNumber>';
					$xml .= '<v1:PONumber>' . $payment->invoice . '</v1:PONumber>';
				$xml .= '</v1:TransactionDetails>';
				$xml .= '<v1:Billing>';
					$xml .= '<v1:Name>' . $payment->name . '</v1:Name>';
					$xml .= '<v1:Email >' . $payment->email . '</v1:Email >';
					$xml .= '<v1:Company >' . $payment->company . '</v1:Company >';
				$xml .= '</v1:Billing>';
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
	public static function parseResponse( $curlData )
	{
		$res = array( "error" => null, "order_id" => null, "transaction_id" => null, "result" => null );

		$status = $curlData['status'];
		$error = $curlData['error'];
		$soap = $curlData['res'];

		//convert
		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTML($soap);
		libxml_clear_errors();
		$xml = $doc->saveXML($doc->documentElement);
		$xml = simplexml_load_string($xml);


		//get data
		$body = $xml->body->envelope->body->fdggwsapiorderresponse;


		if ((integer) $body->transactionid > 0) {
			$res['error'] = null;
			$res['order_id'] = (string) $body->orderid[0];
			$res['transaction_id'] = (string) $body->transactionid[0];
			$res['result'] = (string) $body->transactionresult[0];
		} else {
			//$transaction = $body->commercialserviceprovider->transactionid->processorreferencenumber->processorresponsemessage;	
			$res['error'] = (string) $body->errormessage[0];
			$res['order_id'] = (string) $body->orderid[0];
			$res['result'] = (string) $body->transactionresult[0];
		}

		return $res;

	}

	//
	// Send paymeny receipt
	//
	public static function sendReceiptEmail( $email, $payment, $transaction )
	{
		setlocale(LC_MONETARY, 'en_US');
		// The message
		$message = "Payment transaction. " . "\r\n\r\n";
		$message .= "Amount: " . money_format('%i', $payment->ccAmount) . "\r\n";
		$message .= "Date: " . date("F j, Y, g:i a") . "\r\n";
		$message .= "Card: " . substr($payment->ccNo, -4) . "\r\n"; //only show last 4
		$message .= "Invoice: " . $payment->invoice . "\r\n";
		$message .= "Order ID: " . $transaction['order_id'] . "\r\n";
		$message .= "Transaction ID: " . $transaction['transaction_id'] . "\r\n";
		$message .= "Description: " . $payment->desc . "\r\n";
		$message .= "Email: " . $payment->email . "\r\n";
		$message .= "Name: " . $payment->name . "\r\n";
		$message .= "Company: " . $payment->company . "\r\n";

		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$message = wordwrap($message, 70, "\r\n");

		// Send
		//mail($email, 'LWS Charge Receipt - ' . $transaction['transaction_id'], $message);
		$mailer = JFactory::getMailer();
		
		$mailer->setSender(array('donotreply@languageworldservices.com', 'Language World Services'));
		$mailer->addRecipient($email);
		
		$mailer->setSubject('LWS Charge Receipt - ' . $transaction['transaction_id']);
		$mailer->setBody($message);
		
		$send = $mailer->Send();
		if ( $send !== true ) {
		    echo 'Error sending email: ' . $send->__toString();
		} else {
		    echo 'Mail sent';
		}
		
	}

}

?>
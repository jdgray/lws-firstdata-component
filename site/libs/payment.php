<?php

require_once(dirname(__FILE__) . '/firstdata.php');

class payment
{
	//billing
	public $type = "sale";
	public $name = "";
	public $company = "";
	public $address1 = "";
	public $address2 = "";
	public $city = "";
	public $state = "";
	public $zip = "";
	public $country = "";
	public $phone = "";
	//cc
	public $ccAmount = 0.00;
	public $ccNo = 0;
	public $ccExpiresMonth = 0;
	public $ccExpiresYear = 0;
	public $ccCode = 0;
	//contact
	public $email = "";
	public $notes = "";
	//paymant
	public $transactionReceipt = "";


	public function validate()
	{
		try {

			if (empty($this->type)) {
				throw new Exception('Type is required.');
			}
			//user
			if (empty($this->name)) {
				throw new Exception('Name is required.');
			}
			if (empty($this->invoice)) {
				throw new Exception('Invoice/description is required.');
			}
			if (empty($this->email)) {
				throw new Exception('Email is required.');
			}
			if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
				throw new Exception('Email is not valid.');	
			}
			/*if (empty($this->address1)) {
				throw new Exception('Address is required.');
			}
			if (empty($this->city)) {
				throw new Exception('City is required.');
			}
			if (empty($this->state)) {
				throw new Exception('State is required.');
			}
			if (empty($this->zip)) {
				throw new Exception('Zip is required.');
			}
			if (empty($this->country)) {
				throw new Exception('Country is required.');
			}
			if (empty($this->phone)) {
				throw new Exception('Phone is required.');
			}*/
			//cc
			//all fields required
			if (empty($this->ccAmount)) {
				throw new Exception('Charge Amount is required.');
			}
			if ($this->ccAmount <= 0) {
				throw new Exception('Amount is not valid.');
			}
			if (empty($this->ccNo)) {
				throw new Exception('Credit Card Number is required.');
			}
			if (empty($this->ccExpiresMonth)) {
				throw new Exception('Expire Month is required.');
			}
			if (empty($this->ccExpiresYear)) {
				throw new Exception('Expired Year is required.');
			}
			if (empty($this->ccCode)) {
				throw new Exception('Security Code is required.');
			}
			//contact not required
			
			return null;

		} catch(Exception $e) {
			//redirect back to form and show message
			return $e->getMessage();
		}
	}


}

?>
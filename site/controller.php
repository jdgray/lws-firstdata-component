<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');

require_once(dirname(__FILE__) . '/libs/payment.php');
require_once(dirname(__FILE__) . '/libs/helper.php');
 
/**
 * Hello World Component Controller
 */
class FirstDataController extends JController
{

	public function processpayment($cachable = false, $urlparams = false)
	{
		try {

			$testing = true;
			$error = '';
			//JSession::checkToken('request') or jexit( JText::_( 'JINVALID_TOKEN' ) );
			$jinput = JFactory::getApplication()->input;

			//get config
			$config = helper::getConfig();

			//set config
			if ($testing) {
				$config = $config["test"];
			} else {
				$config = $config["live"];
			}

			$payment = new payment();

			//get data
			//user
			$payment->name = $jinput->get('name', '', 'STRING');
			$payment->company = $jinput->get('company', '', 'STRING');
			$payment->invoice = $jinput->get('invoice', '', 'STRING');
			//$payment->address1 = $jinput->get('address1', '', 'STRING');
			//$payment->address2 = $jinput->get('address2', '', 'STRING');
			//$payment->city = $jinput->get('city', '', 'STRING');
			//$payment->state = $jinput->get('state', '', 'STRING');
			//$payment->zip = $jinput->get('zip', '', 'INTEGER');
			//$payment->country = $jinput->get('country', '', 'STRING');
			//$payment->phone = $jinput->get('phone', '', 'STRING');
			//cc
			$payment->ccAmount = $jinput->get('ccAmount', '', 'DECIMAL');
			$payment->ccNo = $jinput->get('ccNo', '', 'STRING');
			$payment->ccExpiresMonth = $jinput->get('ccExpiresMonth', '', 'STRING');
			$payment->ccExpiresYear = $jinput->get('ccExpiresYear', '', 'STRING');
			$payment->ccCode = $jinput->get('ccCode', '', 'INTEGER');
			//contact
			$payment->email = $jinput->get('email', '', 'STRING');
			//$payment->notes = $jinput->get('notes', '', 'STRING');

			$payment->ccNo = str_replace(" ", "", $payment->ccNo);

			//valid form data
			$error = $payment->validate();
			if ($error) {
				throw new Exception($error);
			}

			//build
			$xml = helper::buildxml($payment);

			//send api call
			$res = helper::submitPayment($xml, $config, $testing);

			//parse res
			$transaction = helper::parseResponse($res);

			//print msgs
			if ($transaction['result'] == 'APPROVED') {

				//send email receipt
				helper::sendReceiptEmail($config['email'], $payment, $transaction);

				$msg = sprintf($config['success_msg'], $payment->email);
				JFactory::getApplication()->enqueueMessage($msg);	
				//JFactory::getApplication()->enqueueMessage($transaction['transaction_id'] . ' - Payment successful! Receipt emailed to ' . $payment->email . '.');	
				//
			} else {
				JError::raiseWarning( 100, $config['error_msg'] );
				//JError::raiseWarning( 100, $transaction['error'] );
			}
			
			return true;
			

		} catch(Exception $e) {
			JFactory::getApplication()->redirect('index.php?option=com_firstdata&msg=' + $e->getMessage());
			//redirect back to form and show message
			//$error = $e->getMessage();
		}


	}

}
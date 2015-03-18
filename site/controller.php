<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');

//import logging
jimport('joomla.log.log');

require_once(dirname(__FILE__) . '/libs/payment.php');
require_once(dirname(__FILE__) . '/libs/helper.php');
 
/**
 * Hello World Component Controller
 */
class FirstDataController extends JController
{

	public function load()
	{

		//print any callback messages
		$msg = (isset($_GET['msg']) ? $_GET['msg'] : null);
		$error = (isset($_GET['error']) ? $_GET['error'] : false);

		if ($error && $msg) {
			JError::raiseWarning( 100, $msg );
		} else if ($msg) {
			JFactory::getApplication()->enqueueMessage($msg);
		}

	}

	public function processpayment($cachable = false, $urlparams = false)
	{
		try {

			$testing = false;
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
			$payment->desc = $jinput->get('desc', '', 'STRING');

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
				
				JFactory::getApplication()->redirect('index.php?option=com_firstdata&Itemid=353&view=firstdata&msg=' . $msg);
				
			} else {
				throw new Exception($transaction['error']);
			}
			
			return true;

		} catch(Exception $e) {

			//log message
			JLog::add(JText::_($e->getMessage()), JLog::CRITICAL);

			//repost data
			$qs = '&Itemid=353&view=firstdata';
			$qs .= '&error=true';
			$qs .= '&msg=' . $config['error_msg'];
			$qs .= '&amount=' . $payment->ccAmount;
			$qs .= '&name=' . $payment->name;
			$qs .= '&company=' . $payment->company;
			$qs .= '&invoice=' . $payment->invoice;
			$qs .= '&email=' . $payment->email;

			JFactory::getApplication()->redirect('index.php?option=com_firstdata' . $qs);

		}


	}

}
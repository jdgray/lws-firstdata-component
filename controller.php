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
			//$payment->address1 = $jinput->get('address1', '', 'STRING');
			//$payment->address2 = $jinput->get('address2', '', 'STRING');
			//$payment->city = $jinput->get('city', '', 'STRING');
			//$payment->state = $jinput->get('state', '', 'STRING');
			//$payment->zip = $jinput->get('zip', '', 'INTEGER');
			//$payment->country = $jinput->get('country', '', 'STRING');
			//$payment->phone = $jinput->get('phone', '', 'STRING');
			//cc
			$payment->ccAmount = $jinput->get('ccAmount', '', 'INTEGER');
			$payment->ccNo = $jinput->get('ccNo', '', 'INTEGER');
			$payment->ccExpiresMonth = $jinput->get('ccExpiresMonth', '', 'INTEGER');
			$payment->ccExpiresYear = $jinput->get('ccExpiresYear', '', 'INTEGER');
			$payment->ccCode = $jinput->get('ccCode', '', 'INTEGER');
			//contact
			$payment->email = $jinput->get('email', '', 'STRING');
			//$payment->notes = $jinput->get('notes', '', 'STRING');

			//valid form data
			$error = $payment->validate();
			if ($error) {
				throw new Exception($error);
			}

			//build
			$xml = helper::buildxml($payment);

			
			//send api call
			$res = helper::submitPayment($xml, $config, $testing);

			if ($res["error"]) {
				throw new Exception('Error processing payment.');
			}

			$res = helper::parseResponse($res);

			print '<pre>';
			print_r($res);
			print '<pre>';

			//helper::sendReceiptEmail($payment, $res);
			
			JFactory::getApplication()->enqueueMessage('Payment successful! Receipt emailed to ' . $payment->email . '.');
			
			return true;
			

		} catch(Exception $e) {
			JFactory::getApplication()->redirect('index.php?option=com_firstdata&msg=' + $e->getMessage());
			//redirect back to form and show message
			//$error = $e->getMessage();
		}


	}

}
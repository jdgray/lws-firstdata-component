<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import joomla controller library
jimport('joomla.application.component.controller');

//add any js libs needed
$document = JFactory::getDocument();
$document->addScript('https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.2/jquery.min.js');
$document->addScript('https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.0.6/parsley.min.js');
$document->addScript('https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.0.2/jquery.payment.min.js');

// Get an instance of the controller prefixed by HelloWorld
$controller = JController::getInstance('FirstData');

//perform load tasks
$controller->load();
 
// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();
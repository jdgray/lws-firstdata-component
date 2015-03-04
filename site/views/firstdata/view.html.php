<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class FirstDataViewFirstData extends JView
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
		$document = JFactory::getDocument();

		// Assign data to the view
		$this->msg = 'Make a payment';
 
		// Display the view
		parent::display($tpl);

		//add custom js here
		$document->addScriptDeclaration('
			window.addEvent("domready", function() {
				console.log($("#payment-form.CCNo"));
			    $("input.ccNo").payment("formatCardNumber");
			});
		');

		//add custom css here
		$style = '.payment-wrapper {
				padding-bottom: 20px;
			}';

		$style .= '.payment-left {
				float: left;
				padding-right: 10px;
				border-right: 1px solid #ddd;
			}';

		$style .= '.payment-right {
				float: left;
				padding-left: 25px
			}';

		$style .= '.clear {
				clear: both;
			}';

		$style .= '.payment-header {
			width: 500px;
			border-top: 1px solid #ccc;
			border-left: 1px solid #ccc;
			border-right: 1px solid #ccc;
			padding: 10px;
			background-color: #e0d9d9;
			border-top-left-radius: 4px;
			border-top-right-radius: 4px;
			font-size: 14px;
			}';

		$style .= '.payment-body {
			width: 500px;
			border: 1px solid #ccc;
			padding: 10px;
			}';

		
		$style .= '#payment-form .form-group {
			width: 100%;
			}';

		$style .= '#payment-form .form-item  {
			margin-left: 10px;
			display: inline-block;
			}';

		$style .= '#payment-form .form-item input  {
			width: 100%;
			}';

		$style .= '#payment-form .form-item label {
			padding-left: 5px;
			}';
	
		//basic form styles
		$style .= '#payment-form select {
			padding: 6px 6px;
			margin: 4px 4px 0px 4px;
			height: 40px;
			font-size: 14px;
			border: 1px solid #cccccc;
			border-radius: 4px;
			}';

		$style .= '#payment-form input {
			display: block;
			height: 20px;
			padding: 6px 0px 6px 6px;
			margin: 4px 4px 0px 4px;
			font-size: 14px;
			line-height: 1.428571429;
			vertical-align: middle;
			border: 1px solid #cccccc;
			border-radius: 4px;
			-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
			      box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
			-webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
			      transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
			}';

		$style .= '#payment-form .submit {
			width: 100%;
			height: 40px;
			font-size: 14px;
			background-color: #EAD78C;
			}';

		//form error
		$style .= 'input.parsley-error, select.parsley-error, textarea.parsley-error {
			color: #B94A48;
			background-color: #F2DEDE;
			border: 1px solid #EED3D7;
			}';

		$style .= '.parsley-errors-list {
				margin: 2px 0 3px;
				padding: 0;
				list-style-type: none;
				font-size: 0.9em;
				line-height: 0.9em;
				opacity: 0;
				-moz-opacity: 0;
				-webkit-opacity: 0;

				transition: all .3s ease-in;
				-o-transition: all .3s ease-in;
				-moz-transition: all .3s ease-in;
				-webkit-transition: all .3s ease-in;
			}';

		$style .= '.parsley-errors-list.filled {
				opacity: 1;
			}';

		$style .= '.parsley-errors-list {
				display: none;
			}';

		//form success
		$style .= 'input.parsley-success, select.parsley-success, textarea.parsley-success {
			  color: #468847;
			  background-color: #DFF0D8;
			  border: 1px solid #D6E9C6;
			}';

		$document->addStyleDeclaration( $style );

	}
}
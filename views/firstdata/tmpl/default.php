<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>

<div class="payment-header">
  <div class="paymentMsg"><?php echo $this->msg; ?></div>
</div>

<div class="payment-body">
<form method="post" id="payment-form" name="payment-form" action="<?php echo JRoute::_('index.php'); ?>" ENCTYPE="multipart/form-data" data-parsley-validate >

<div class="form-group">
  <div class="form-item">
      <label>Amount:</label>
      <input name="ccAmount" type="text" required />
  </div>
  <div class="form-item full" style="width: 290px">
      <label>Company:</label>
      <input name="company" type="text">
  </div>
</div>
<div class="form-group">
  <div class="form-item full" style="width: 470px">
      <label>Invoice/Description</label>
      <input name="invoice" type="text" required />
  </div>
</div>
<div class="form-group">
  <div class="form-item full" style="width: 470px">
      <label>Email</label>
      <input name="email" type="text" required />
  </div>
</div>
<div class="form-group">
  <div class="form-item full" style="width: 290px">
      <label>Card Number</label>
      <input name="ccNo" class="ccNo" type="text" maxlength="40" required />
  </div>
  <div class="form-item">
      <label>Expires:</label>
      <div style="width: 180px">
        <div class="form-item">
          <SELECT NAME="ccExpiresMonth" required >
                <OPTION VALUE="01">01</OPTION>
                <OPTION VALUE="02">02</OPTION>
                <OPTION VALUE="03">03</OPTION>
                <OPTION VALUE="04">04</OPTION>
                <OPTION VALUE="05">05</OPTION>
                <OPTION VALUE="06">06</OPTION>
                <OPTION VALUE="07">07</OPTION>
                <OPTION VALUE="08">08</OPTION>
                <OPTION VALUE="09">09</OPTION>
                <OPTION VALUE="10">10</OPTION>
                <OPTION VALUE="11">11</OPTION>
                <OPTION VALUE="12">12</OPTION>
              </SELECT>
        </div>
        <div class="form-item"> / </div>
        <div class="form-item">
          <SELECT NAME="ccExpiresYear" required>
            <OPTION VALUE="15">2015</OPTION>
            <OPTION VALUE="16">2016</OPTION>
            <OPTION VALUE="17">2017</OPTION>
            <OPTION VALUE="18">2018</OPTION>
            <OPTION VALUE="19">2019</OPTION>
            <OPTION VALUE="20">2020</OPTION>
            <OPTION VALUE="21">2021</OPTION>
            <OPTION VALUE="22">2022</OPTION>
            <OPTION VALUE="23">2023</OPTION>
            <OPTION VALUE="24">2024</OPTION>
            <OPTION VALUE="25">2025</OPTION>
            <OPTION VALUE="26">2026</OPTION>
            <OPTION VALUE="27">2027</OPTION>
            <OPTION VALUE="28">2028</OPTION>
            <OPTION VALUE="29">2029</OPTION>
            <OPTION VALUE="30">2030</OPTION>
          </SELECT>
        </div>
      </div>
  </div>
<div class="form-group">
  <div class="form-item full" style="width: 290px">
      <label>Name on card:</label>
      <input name="name" type="text" required />
  </div>
  <div class="form-item">
      <label>Card Code:</label>
      <input name="ccCode" type="text" value="" size="10" maxlength="3" required />
  </div>
</div>
<div class="form-group">
  <input type="hidden" name="option" value="com_firstdata" />
  <input type="hidden" name="task" value="processpayment" />
  <input class="submit" name="Submit" type="submit" value="Send Secure Payment">
</div>
</form>
</div>

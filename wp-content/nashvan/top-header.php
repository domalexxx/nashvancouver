<?php
if( $_REQUEST['item_number'] && $_REQUEST['item_name'] == "classifiedadd" )
{
	$txn_id = $_REQUEST['txn_id'];
	$payment_status = $_REQUEST['payment_status'];
	$payment_fee = $_REQUEST['payment_fee'];
	$mc_currency = $_REQUEST['mc_currency'];
	$item_number = $_REQUEST['item_number'];

	$redirect_page_url = get_permalink( get_page_by_title("Classified add") );

	$urlstring  = '';
	$urlstring .= "?tx=".trim($txn_id)."&";
	$urlstring .= "st=".trim($payment_status)."&";
	$urlstring .= "amt=".trim($payment_fee)."&";
	$urlstring .= "cc=".trim($mc_currency)."&";
	$urlstring .= "item_number=".trim($item_number);

	?>
	<script type="text/javascript">
      window.location="<?php echo $redirect_page_url.$urlstring; ?>";						
    </script>
    <?php
	die;
}

if( $_REQUEST['item_number'] && $_REQUEST['item_name'] == "businessdirectoryadd" )
{
	$txn_id = $_REQUEST['txn_id'];
	$payment_status = $_REQUEST['payment_status'];
	$payment_fee = $_REQUEST['payment_fee'];
	$mc_currency = $_REQUEST['mc_currency'];
	$item_number = $_REQUEST['item_number'];

	$redirect_page_url = get_permalink( get_page_by_title("Business Directory Add") );

	$urlstring  = '';
	$urlstring .= "?tx=".trim($txn_id)."&";
	$urlstring .= "st=".trim($payment_status)."&";
	$urlstring .= "amt=".trim($payment_fee)."&";
	$urlstring .= "cc=".trim($mc_currency)."&";
	$urlstring .= "item_number=".trim($item_number);

	?>
	<script type="text/javascript">
      window.location="<?php echo $redirect_page_url.$urlstring; ?>";						
    </script>
    <?php
	die;
}

if( $_REQUEST['item_number'] && $_REQUEST['item_name'] == "eventadd" )
{
	$txn_id = $_REQUEST['txn_id'];
	$payment_status = $_REQUEST['payment_status'];
	$payment_fee = $_REQUEST['payment_fee'];
	$mc_currency = $_REQUEST['mc_currency'];
	$item_number = $_REQUEST['item_number'];

	$redirect_page_url = get_permalink( get_page_by_title("Event Add") );

	$urlstring  = '';
	$urlstring .= "?tx=".trim($txn_id)."&";
	$urlstring .= "st=".trim($payment_status)."&";
	$urlstring .= "amt=".trim($payment_fee)."&";
	$urlstring .= "cc=".trim($mc_currency)."&";
	$urlstring .= "item_number=".trim($item_number);

	?>
	<script type="text/javascript">
      window.location="<?php echo $redirect_page_url.$urlstring; ?>";						
    </script>
    <?php
	die;
}
?>
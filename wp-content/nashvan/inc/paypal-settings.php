<div class="wrap">
	<h1>Paypal Settings</h1>
    <style type="text/css">
	 .paypal-setting-out{margin-top:10px; padding:10px; width:450px;}
	 .frm-field{width:100%;}
	</style>
     
    <div class="paypal-setting-out">
    <?php
	 if(isset($_POST['paypalSettingSub']))
	 {
		   extract($_POST);
		     if($sendboxStatus=='on')
			{
			  $paypalurl ="https://www.sandbox.paypal.com/cgi-bin/webscr";
			}
			else
			{
			  $paypalurl ="https://www.paypal.com/cgi-bin/webscr";
			}
		   $payPalsettings= array('paypalEmail'=>$paypal_email,'sendbox'=>$sendboxStatus,'paypalurl'=>$paypalurl,
		   'currency_code'=>$currency_code);
		   update_option('mrPaypalsetting',$payPalsettings);
		 
	 }
	 
	 $mrPaypalsetting = get_option('mrPaypalsetting',true);
	?>
     <form method="post">
       <table class="wp-list-table widefat fixed">
        <tr>
          <th width="120">Paypal Email</th>
          <td><input type="email" name="paypal_email" value="<?php echo $mrPaypalsetting['paypalEmail']; ?>"
           class="frm-field" placeholder="Paypal Email Address"></td>
        </tr>
        <tr>
          <th>Currency Code</th>
          <td><input type="text" name="currency_code" value="<?php echo $mrPaypalsetting['currency_code']; ?>"
          class="frm-field" placeholder="Currency Code">
          </td>
        </tr>
        <tr>
          <th>Enable send box</th>
          <td>
           <input type="checkbox" <?php if($mrPaypalsetting['sendbox']=='on'){echo 'checked';}?> 
           class="frm-field" name="sendboxStatus">
          </td>
        </tr>
        <tr>
         <td colspan="2" align="center">
         <input type="submit" class="button button-primary button-large" value="Submit" name="paypalSettingSub">
         </td>
        </tr>
       </table>
     </form>   
    </div>
</div>
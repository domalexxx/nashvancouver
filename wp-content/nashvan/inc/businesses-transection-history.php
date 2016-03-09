<div class="wrap">
<style type="text/css">
.active{color:#090 !important;}
</style>
 <h1>Businesses Transection History</h1>
 <?php
 global $wpdb;
   $payHistorytab = $wpdb->prefix."payment_history";
   
 ?>
 <table class="wp-list-table widefat fixed striped">
    <tr>
     <th width="40">#</th>
     <th>Post Title</th>
     <th>Active Days</th>
     <th>Pay Amount</th>
     <th>Payment Status</th>
     <th>Ad Status</th>
     <th>Activated Date</th>
    </tr>
    <?php
	  $clQry = $wpdb->get_results("select * from $payHistorytab where postType='business' order by tid DESC");
	  if($clQry)
	  {		 
		$i=1;
	   foreach($clQry as $classRes)	  
	   {
		 echo '<tr>';
         echo '<td>'.$i.'</td>';
		 echo '<td><a href="'.get_the_permalink($classRes->postId).'" target="_blank">';
		 echo  get_the_title($classRes->postId);		
		 echo '</a></td>';
		 echo '<td>'.$classRes->activeDays.'</td>';
		 echo '<td>'.$classRes->payAmt.'</td>';
		 echo '<td>'.$classRes->payStatus.'</td>';
		 echo '<td class="'.$classRes->postStatus.'">'.$classRes->postStatus.'</td>';
		 echo '<td>'.$classRes->date.'</td>';
         echo '</tr>';
		 $i++;
	   }
	  }
	  
	?>
 </table>
 
</div>
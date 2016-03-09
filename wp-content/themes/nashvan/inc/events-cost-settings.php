<div class="wrap">
<style type="text/css">
 .cost-setting-outer{width:100%;}
 .cost-setting-outer input[type="text"] {
  margin: 3px 0;
  padding: 3px 5px;
}
 .cost-setting-outer table {
   padding: 15px;
 }
 .cost-setting-outer td p {color: #d30426;font-weight: bold;margin: 0;}
 .adcost-title{width:100%;}
 
</style>
 <h1>Event Cost Settings</h1>
 <div class="cost-setting-outer">
 <?php
 $classCostArr = array();
  if(isset($_POST['eventCsub']))
  {	 
    extract($_POST);	
  	$postEventArr=array();
	if(count($postEventTitle)>0)
	{		
	   $myCostArr = array();
	   for($j=0;$j<count($postEventTitle);$j++)	
	   {
		  $myCostArr['postEventOptTitle']=$postEventTitle[$j];
		  $myCostArr['postEventDays']=$postEventDays[$j];
		  $myCostArr['postEventcost']=$postEventcost[$j];
		  $postEventArr[] =$myCostArr;
	   }
	}
	 $evtCostOptArr = array('NoOfEventOption'=>$NoofPostEventOption,'eventCostOptions'=>$postEventArr);	
     update_option('mrEventCostOptions',$evtCostOptArr);
  }
  $evetArr = get_option('mrEventCostOptions');
  
  $NoofPostEventOption =$evetArr['NoOfEventOption'];
  $EvtCostOptionsArr =$evetArr['eventCostOptions'];
 
 ?>
 <form method="post">
  <table class="wp-list-table widefat fixed">
     <tr>
      <td colspan="4">
       <strong>Enter Number Post Event Option</strong> &nbsp;&nbsp;&nbsp;&nbsp;
       <input type="number" name="NoofPostEventOption" 
       value="<?php echo get_option('NoofPostEventOption',$NoofPostEventOption); ?>" ><hr>
      </td>
     </tr>
     <?php
	   if(!empty($NoofPostEventOption) && $NoofPostEventOption>0)
	   {
		  for($i=0;$i<$NoofPostEventOption;$i++)
		  { 
		   $postEventOptTitle = $EvtCostOptionsArr[$i]['postEventOptTitle'];
		   $postEventDays = $EvtCostOptionsArr[$i]['postEventDays'];
		   $postEventcost = $EvtCostOptionsArr[$i]['postEventcost'];
	 ?>
     <tr>
      <th colspan="2">Post Event Option <?php echo $i+1; ?> 
      <input type="text" class="adcost-title"  value="<?php echo $postEventOptTitle;?>" 
        name="postEventTitle[]" placeholder="Enter Title" >
      </th>
      <td>
      <p>Days</p>
      <input type="text" class="adcost-title" placeholder="Enter days"
       value="<?php echo $postEventDays;?>" name="postEventDays[]" >
      </td>
      <td>
      <p>Cost</p>
       $<input type="text" placeholder="Enter Cost" value="<?php echo $postEventcost;?>" 
        name="postEventcost[]" >
      </td>
     </tr>     
    <?php }
	} ?>
     <tr>     
      <td colspan="2" align="center">
      <input type="submit" class="button button-primary button-large" name="eventCsub" value="Submit">
      </td>
     </tr>
  </table>
 </form> 
 </div>
</div>
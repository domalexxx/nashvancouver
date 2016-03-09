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
 <h1>Business Cost Settings</h1>
 <div class="cost-setting-outer">
 <?php
 $classCostArr = array();
  if(isset($_POST['businessCsub']))
  {	 
    extract($_POST);	
  	$postBusinssArr=array();
	if(count($postBusinssTitle)>0)
	{		
	   $myCostArr = array();
	   for($j=0;$j<count($postBusinssTitle);$j++)	
	   {
		  $myCostArr['postBusinssOptTitle']=$postBusinssTitle[$j];
		  $myCostArr['postBusinssDays']=$postBusinssDays[$j];
		  $myCostArr['postBusinsscost']=$postBusinsscost[$j];
		  $postBusinssArr[] =$myCostArr;
	   }
	}
	 $evtCostOptArr = array('NoOfBusinssOption'=>$NoofPostBusinssOption,'businessCostOptions'=>$postBusinssArr);	
     update_option('mrBusinssCostOptions',$evtCostOptArr);
  }
  $evetArr = get_option('mrBusinssCostOptions');
  $NoofPostBusinssOption =$evetArr['NoOfBusinssOption'];
  $EvtCostOptionsArr =$evetArr['businessCostOptions'];
 
 ?>
 <form method="post">
  <table class="wp-list-table widefat fixed">
     <tr>
      <td colspan="4">
       <strong>Enter Number Post Business Option</strong> &nbsp;&nbsp;&nbsp;&nbsp;
       <input type="number" name="NoofPostBusinssOption" 
       value="<?php echo get_option('NoofPostBusinssOption',$NoofPostBusinssOption); ?>" ><hr>
      </td>
     </tr>
     <?php
	   if(!empty($NoofPostBusinssOption) && $NoofPostBusinssOption>0)
	   {
		  for($i=0;$i<$NoofPostBusinssOption;$i++)
		  { 
		   $postBusinssOptTitle = $EvtCostOptionsArr[$i]['postBusinssOptTitle'];
		   $postBusinssDays = $EvtCostOptionsArr[$i]['postBusinssDays'];
		   $postBusinsscost = $EvtCostOptionsArr[$i]['postBusinsscost'];
	 ?>
     <tr>
      <th colspan="2">Post Business Option <?php echo $i+1; ?> 
      <input type="text" class="adcost-title"  value="<?php echo $postBusinssOptTitle;?>" 
        name="postBusinssTitle[]" placeholder="Enter Title" >
      </th>
      <td>
      <p>Days</p>
      <input type="text" class="adcost-title" placeholder="Enter days"
       value="<?php echo $postBusinssDays;?>" name="postBusinssDays[]" >
      </td>
      <td>
      <p>Cost</p>
       $<input type="text" placeholder="Enter Cost" value="<?php echo $postBusinsscost;?>" 
        name="postBusinsscost[]" >
      </td>
     </tr>     
    <?php }
	} ?>
     <tr>     
      <td colspan="2" align="center">
      <input type="submit" class="button button-primary button-large" name="businessCsub" value="Submit">
      </td>
     </tr>
  </table>
 </form> 
 </div>
</div>
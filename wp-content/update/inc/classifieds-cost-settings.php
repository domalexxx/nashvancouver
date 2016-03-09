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
 <h1>Classified Cost Settings</h1>
 <div class="cost-setting-outer">
 <?php
 $classCostArr = array();
  if(isset($_POST['classifiedCsub']))
  {	 
    extract($_POST);	
	$adCostOptions =array();	
  	$postAdArr=array();
	if(count($postAdTitle)>0)
	{		
	   $myCostArr = array();
	   for($j=0;$j<count($postAdTitle);$j++)	
	   {
		  $myCostArr['postAdOptTitle']=$postAdTitle[$j];
		  $myCostArr['postAdDays']=$postAdDays[$j];
		  $myCostArr['postAdcost']=$postAdcost[$j];
		  $postAdArr[] =$myCostArr;
	   }
	}
	 $classCostOptArr = array('NoOfAdOption'=>$NoofPostAdOption,'adCostOptions'=>$postAdArr);	
    // $classCostOptArr1 = serialize($classCostOptArr);
     update_option('mrAdCostOptions',$classCostOptArr);
	
  }
  $classifiedArr = get_option('mrAdCostOptions');
  $NoofPostAdOption =$classifiedArr['NoOfAdOption'];
  $adCostOptionsArr =$classifiedArr['adCostOptions'];
 ?>
 <form method="post">
  <table class="wp-list-table widefat fixed">
     <tr>
      <td colspan="4">
       <strong>Enter Number Post Ad Option</strong> &nbsp;&nbsp;&nbsp;&nbsp;
       <input type="number" name="NoofPostAdOption" 
       value="<?php echo get_option('NoofPostAdOption',$NoofPostAdOption); ?>" ><hr>
      </td>
     </tr>
     <?php
	   if(!empty($NoofPostAdOption) && $NoofPostAdOption>0)
	   {
		  for($i=0;$i<$NoofPostAdOption;$i++)
		  { 
		   $postAdOptTitle = $adCostOptionsArr[$i]['postAdOptTitle'];
		   $postAdDays = $adCostOptionsArr[$i]['postAdDays'];
		   $postAdcost = $adCostOptionsArr[$i]['postAdcost'];
	 ?>
     <tr>
      <th colspan="2">Post Ad Option <?php echo $i+1; ?> 
      <input type="text" class="adcost-title"  value="<?php echo $postAdOptTitle;?>" 
        name="postAdTitle[]" placeholder="Enter Title" >
      </th>
      <td>
      <p>Days</p>
      <input type="text" class="adcost-title" placeholder="Enter days"
       value="<?php echo $postAdDays;?>" name="postAdDays[]" >
      </td>
      <td>
      <p>Cost</p>
       $<input type="text" placeholder="Enter Cost" value="<?php echo $postAdcost;?>" 
        name="postAdcost[]" >
      </td>
     </tr>     
    <?php }
	} ?>
     <tr>     
      <td colspan="2" align="center">
      <input type="submit" class="button button-primary button-large" name="classifiedCsub" value="Submit">
      </td>
     </tr>
  </table>
 </form> 
 </div>
</div>
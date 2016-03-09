<?php 
/*
Template Name: Classified-add
*/
?>
<?php get_header();
$user_ID = get_current_user_id();
global $current_user;

//Cost settins data
$classifiedCostArr = get_option('mrAdCostOptions'); 
$adCostOptionsArr = $classifiedCostArr['adCostOptions'];

$mrPaypalsetting = get_option('mrPaypalsetting',true);
$currencyCode = $mrPaypalsetting['currency_code'];
$paypalurl = $mrPaypalsetting['paypalurl'];

?>
<div class="container classified">
	<div class="row">
		<?php get_sidebar(); ?>
		<div class="top-buffer2 col-xs-9 col-sm-9 col-md-9 col-lg-9">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<p class="title">Размещение нового объявления</p>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<p class="title4 text-center">Заполните этот раздел для размещения нового объявления</p>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation 
						ullamco laboris nisi ut aliquip ex ea commodo consequat. 
						Duis aute irure dolor in reprehenderit in voluptate velit 
						esse cillum dolore eu fugiat nulla pariatur. Excepteur sint</p>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">                                   
						<?php		
						$currentDate = current_time('mysql'); 				
						if(isset($_SESSION['success_mess']) && $_SESSION['success_mess']==1)
						{
							unset($_SESSION['success_mess']);						
						}
						
						$payHistorytab = $wpdb->prefix."payment_history";	// Payment Status Table						

						//Place new Ad
						  if(isset($_POST['mrPalceNewAd']))
						  {
							  	extract($_POST);
								// Create Ad post 
								$my_newAd = array(
									'post_title' => wp_strip_all_tags($_POST['mrAdTitle'] ),
									'post_type' => 'classified',
									'post_content' => $_POST['description'],
									'post_status' => 'publish',
									'post_author' => $user_ID
								);
								// Insert the Ad into the database
								$insertAdId = wp_insert_post($my_newAd);
								if($insertAdId)
								{
									// Assign category
									$parent = get_term_by( 'id', $category1, 'classified_cat');
									wp_set_object_terms( $insertAdId, intval($parent->parent), 'classified_cat');
									wp_set_object_terms( $insertAdId, intval($category1), 'classified_cat', true);

									if( count($_POST['attachid']) > 0 ) {
									
										if(isset($_POST['attachid'][0])) { set_post_thumbnail( $insertAdId, $_POST['attachid'][0] ); }
	
										if( count($_POST['attachid']) > 1 ) {
											array_shift($_POST['attachid']);
											$attachimplode = implode(",", $_POST['attachid']);
											update_post_meta($insertAdId,'fg_temp_metadata',$attachimplode);
											update_post_meta($insertAdId,'fg_perm_metadata',$attachimplode);
										}
									}

									update_post_meta($insertAdId,'price',$mr_Adprice);
									update_post_meta($insertAdId,'web_site',$websiteUrl);
									update_post_meta($insertAdId,'phone',$phoneNo);
									update_post_meta($insertAdId,'location',$location);
									update_post_meta($insertAdId,'addays',$days);
									
									changePoststatus($insertAdId,'pending'); // Update post status
								}
								
								//Insert data in trasection history	
								$wpdb->query("insert into $payHistorytab set postId='$insertAdId',activeDays='$days',
								payAmt='',userId='$user_ID',payStatus='pending',postType='classified',
								postStatus='pending',date='$currentDate'");

								//PayPal settings
								$paypal_email = $mrPaypalsetting['paypalEmail'];
								$paypalAction_url = $paypalurl;
								$return_url = get_permalink( get_page_by_title("Classified add") );
								$cancel_url = get_permalink( get_page_by_title("Classified add") );
								$notify_url = get_permalink( get_page_by_title("Classified add") );
								$item_name = get_the_title($insertAdId);

								if($days)
								{
									$item_amount="";
									for($i=0;$i<count($adCostOptionsArr);$i++)
									{
										$postAdDays =$adCostOptionsArr[$i]['postAdDays'];
										$postAdcost =$adCostOptionsArr[$i]['postAdcost'];
										if(trim($postAdDays)==trim($days))
										{
											$item_amount = $postAdcost;
										}
									}
								}

								$currency_code = $currencyCode;
								$ccUserName = $current_user->display_name;
						 		
								//Check if paypal request or response
								if($item_amount == 0 || $item_amount == 'free')
						  		{
							 		changePoststatus($insertAdId,'publish'); // Update post status 							
							 		$wpdb->query("update $payHistorytab set payStatus='$st',payAmt='$item_amount',
							 postStatus='active',date='$currentDate' where postId='$insertAdId'");
								 ?>
								  <script type="text/javascript">
								   jQuery(document).ready(function(e) {
									 jQuery('#mrclose-model').click(function(){
									   jQuery("#confirmModal").removeClass('in');
									   jQuery("#confirmModal").hide();
									   jQuery("#confirmModal").after('');
									   window.location="<?php echo get_permalink($insertAdId);?>";
									 });

									 jQuery("#confirmModal").addClass('in');
									 jQuery("#confirmModal").show();
									 jQuery("#confirmModal").after('<div class="modal-backdrop fade in"></div>');
									 setTimeout(function(){window.location="<?php echo get_permalink($insertAdId);?>";},5000);
								   });
								 </script>	
								 <?php
						  		} else if($item_amount > 0) {

									$querystring ='';
							 		$querystring .= "?business=".urlencode($paypal_email)."&";
							 		$querystring .= "item_name=".urlencode('classifiedadd')."&";
							 		$querystring .= "item_number=".urlencode($insertAdId)."&";

									$querystring .= "amount=".urlencode($item_amount)."&";
									$querystring .= "currency_code=".urlencode($currency_code)."&";
									$querystring .= "cmd=".urlencode('_xclick')."&";
									$querystring .= "first_name=".urlencode($ccUserName)."&";

									//Append paypal return addresses
									$querystring .= "return=".urlencode(stripslashes($return_url))."&";
									$querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
									$querystring .= "notify_url=".urlencode($notify_url);
									
									//Redirect to paypal IPN
									?>
									 <script type="text/javascript">
									  window.location="<?php echo $paypalAction_url.$querystring; ?>";						
									 </script>
									<?php
						    	}
						  	}
						  	else if(isset($_GET['tx']) && isset($_GET['st']))						 
						  	{
						     	extract($_GET);
							  	$st = trim($st);
							  	if($st=="Completed")
							  	{
									changePoststatus($item_number,'publish'); // Update post status
									$_SESSION['success_mess'] = 1;
				 			   	}
							 	$wpdb->query("update $payHistorytab set payStatus='$st',payAmt='$amt',postStatus='active',
							 date='$currentDate' where postId='$item_number'");
							?>
                             <script type="text/javascript">
							   jQuery(document).ready(function(e) {
								 jQuery('#mrclose-model').click(function(){
								   jQuery("#confirmModal").removeClass('in');
								   jQuery("#confirmModal").hide();
								   jQuery("#confirmModal").after('');
								   window.location="<?php echo get_permalink($item_number);?>";
								 });

                                 jQuery("#confirmModal").addClass('in');
								 jQuery("#confirmModal").show();
								 jQuery("#confirmModal").after('<div class="modal-backdrop fade in"></div>');
								 setTimeout(function(){window.location="<?php echo get_permalink($item_number);?>";},5000);
                               });
							 </script>	
                             <?php
						  }
						?>

        <form id="classified"  method="POST" role="form" enctype="multipart/form-data">				
           <div class="form-group">
                <span class="required"></span>
                <input type="text" class="form-control" name="mrAdTitle" id="" 
                placeholder="Укажите заголовок вашего объявления" required>
            </div>
            
           <div class="row upload">
            <span class="required"></span>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-5">
                <div class="dropzone" drop-zone="" id="file-dropzone">
                    <div class="text-center dz-message" data-dz-message>
                        <div><img src="<?php bloginfo('template_url') ?>/images/cloud.png"></div>
                        <p>Перетяните изображения сюда<br>или<br>Воспользуйтесь обычным загрузчиком</p>
                        <span>Выбрать изображение</span>
                        <p>Минимальный размер изображений должен быть 318x318 px.<br>Поддерживаемые форматы PNG, JPG, GIF Максимум вы можете загрузить 5 изображений</p>
                    </div>
                </div>
            </div>
            <div class="no-padding col-xs-1 col-sm-1 col-md-1 col-lg-1">
				<div id="attachment-preview-images" style="display:none;"></div>
                <ul class="images"></ul>
            </div>
            <div class="form-horizontal info col-xs-7 col-sm-7 col-md-7 col-lg-6">
                    <div class="form-group">
                        <label class="price text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label" for="">
                        Стоимость:</label>
                        <div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
                            <input name="mr_Adprice" type="text" class="form-control" required 
                            placeholder="Укажите стоимость">
                            <span class="required"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="category text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label" for="">Категория:</label>
                        <div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
                            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#categoryModal">
                              Выберите категорию
                            </button>
                            <span class="required"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="website text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label"
                         for="">Вебсайт:</label>
                            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
                                <input type="text" name="websiteUrl" class="form-control" required
                                 placeholder="Адрес вебсайта">
                                <span class="required"></span>
                            </div>
                    </div>
                    <div class="form-group">
                        <label class="phone text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label" for="">
                        Телефон:</label>
                            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
                                <input type="text" name="phoneNo" required  class="form-control" 
                                placeholder="Номер телефона">
                                <span class="required"></span>
                            </div>
                    </div>
                    <div class="form-group">
                        <label class="location text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label" for="">Расположение:</label>
                            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
                                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">Выберите расположение</button>
                                <span class="required"></span>
                            </div>
                    </div>
            </div>
        </div>
        
           <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <textarea name="description" id="inputDescription" class="form-control"
                 rows="10" ></textarea>
            </div>
        </div>
        
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <p class="title4 text-center">Выберите вариант размещения объявления</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
        </p>
	</div>
	
	<div class="col-centered col-xs-6 col-sm-6 col-md-6 col-lg-7">
	<?php 
		//Dispay Ads options
		if(count($adCostOptionsArr)>0)
		{
			for($i=0;$i<count($adCostOptionsArr);$i++)
			{
				if($adCostOptionsArr[$i]['postAdOptTitle'])
				{ ?>
				<div class="radio">                
					<label>
						<input type="radio" name="days" id="inputDays<?php echo $i;?>" value="<?php echo $adCostOptionsArr[$i]['postAdDays']; ?>" checked="checked">
						<?php echo $adCostOptionsArr[$i]['postAdOptTitle']; ?></span>
					</label>
				</div>
				<?php }
			}
		}
	?>              
	</div>
</div>
     <!-- Category Section -->      
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"></span></button>
            <h4 class="modal-title" id="myModalLabel">
            Выберите категорию для нового объявления</h4>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <!--Авто-->
                <?php
					$flag = 1;
                	$classified_catarr = get_terms('classified_cat', array('hide_empty' => false, 'parent' => 0 ));
                	foreach($classified_catarr as $class_CatArr) { ?>
                    
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    	<b><?php echo $class_CatArr->name; ?></b>
                        <div class="clearfix"></div>
                        <?php $child_catarr = get_terms('classified_cat', array('hide_empty' => false, 'parent' => $class_CatArr->term_id )); ?>
                        <?php foreach($child_catarr as $childcat) { ?>
                            <div class="radio">
                                <label>
                                  <input type="radio" name="category1" id="input" value="<?php echo $childcat->term_id;?>">
                                  <?php echo $childcat->name; ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>

                    <?php if($flag % 3 == 0) { echo '<div class="clearfix"></div>'; } ?>
                    
                <?php $flag++; } ?>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Сохранить изменения</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
          </div>
        </div>
      </div>
</div>
     <!-- Close category -->   
    <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <button type="submit" name="mrPalceNewAd" value="Разместить объявление" class="btn btn-primary">
    Разместить объявление</button>
    </div>
</div>						
        </form>
						</div>
					</div>
				</div>
			</div><!-- end .row -->
		</div><!-- end col-lg-9 -->
	</div><!-- end .row -->
</div><!-- end .container -->

<!--Classified Modal Box-->
<div aria-labelledby="confirmModalLabel" role="dialog" tabindex="-1" id="confirmModal" class="modal fade">
  <div role="document" class="modal-dialog">
    <div class="modal-content text-center">
      <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" id="mrclose-model" data-dismiss="modal" class="close" type="button">
        <span aria-hidden="true"></span>
        </button>
        <h4 class="title4">Спасибо!</h4>
      </div>
      <div class="modal-body">
        	<p>Ваше объявление было успешно размещено на сайте<br> и мы покажем вам его через <strong>5 сек.</strong></p>
      </div>
    </div>
  </div>
</div>
<!--End of Classified Modal Box-->
<?php get_footer(); ?>
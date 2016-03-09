<?php 
/*
Template Name: Classified-add
*/

if(!is_user_logged_in())
{ wp_redirect(home_url()); }

get_header();
$user_ID = get_current_user_id();
global $current_user;

//Cost settins data
$classifiedCostArr = get_option('mrAdCostOptions'); 
$adCostOptionsArr = $classifiedCostArr['adCostOptions'];

$mrPaypalsetting = get_option('mrPaypalsetting',true);
$currencyCode = $mrPaypalsetting['currency_code'];
$paypalurl = $mrPaypalsetting['paypalurl'];

?>
<div id="main-container" class="container classified">
	<div class="row top-buffer2">
		<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<p class="title">Размещение нового объявления</p>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<p class="title4 text-center">Заполните этот раздел для размещения нового объявления</p>
						<p>Для того, чтобы Ваше обьявление было опубликовано, пожалуйста, заполните необходимые поля. Загрузив фотографию (и), привлекательность Вашего обьявления возрастает.</p>
						</div>
					</div>
					
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 error-container" style="display:none;">
						<h4>При создании нового объявления не были заполнены обязательные поля:</h4>
						<ol>
							<li><label for="mrAdTitle" class="error">Please enter Title.</label></li>
							<li><label for="category1" class="error">Please choose event category.</label></li>
							<li><label for="emailAddress" class="error">Please enter a valid email address.</label></li>
							<li><label for="description" class="error">Please enter description.</label></li>
							<li><label for="address" class="error">Please enter location.</label></li>
						</ol>
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
									update_post_meta($insertAdId,'emailaddress',$emailAddress);
									
									$locationArr = array('address'=>trim($address),'lat'=>$addressLat,'lng'=>$addressLong);
									update_post_meta($insertAdId,'location',$locationArr);
									update_post_meta($insertAdId,'info',$info);
									
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
								$cancel_url = get_permalink( get_page_by_title("Cancel Classified") );
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
							 		//changePoststatus($insertAdId,'publish'); // Update post status
									
									$lasttransid = $wpdb->get_row("select tid from $payHistorytab where postId = '$insertAdId' and postType = 'classified' and payStatus = 'pending' ORDER BY tid DESC limit 0,1")->tid;
							 		$wpdb->query("update $payHistorytab set payStatus='$st',payAmt='$item_amount', postStatus='active',date='$currentDate' where postId='$insertAdId' AND tid = '$lasttransid'");
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
									//changePoststatus($item_number,'publish'); // Update post status
									$_SESSION['success_mess'] = 1;
				 			   	}

								$lasttransid = $wpdb->get_row("select tid from $payHistorytab where postId = '$item_number' and postType = 'classified' and payStatus = 'pending' ORDER BY tid DESC limit 0,1")->tid;
							 	$wpdb->query("update $payHistorytab set payStatus='$st',payAmt='$amt',postStatus='active', date='$currentDate' where postId='$item_number'");
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
				<input type="text" class="form-control" name="mrAdTitle" id="mrAdTitle" placeholder="Укажите заголовок вашего объявления" required />
            </div>
            
           <div class="row upload">
            	
                <span class="required"></span>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-5">
                    <div class="dropzone" drop-zone="" id="file-dropzone">
                        <img id="file-dropzone-img" src="http://www.jimpunk.com/Loading/loading34.gif">
                        <div class="text-center dz-message" id="file-dropzone-message" data-dz-message>
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
                    	<label class="price text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label" for="mr_Adprice">Стоимость:</label>
                    	<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
                    		<input name="mr_Adprice" id="mr_Adprice" type="text" class="form-control" placeholder="Укажите стоимость">
                    	</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="category text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label">Категория:</label>
                        <div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
                            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#categoryModal">Выберите категорию</button>
                            <span class="required"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="website text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label" for="websiteUrl">Вебсайт:</label>
                        <div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
                            <input type="text" name="websiteUrl" id="websiteUrl" class="form-control" placeholder="Адрес вебсайта">
                        </div>
                    </div>
                
                    <div class="form-group">
                    	<label class="phone text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label" for="phoneNo">Телефон:</label>
                    	<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
                    		<input type="text" name="phoneNo" id="phoneNo" class="form-control" placeholder="Номер телефона">
                    	</div>
                	</div>
                
                	<div class="form-group">
                		<label class="location text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label" for="">Email:</label>
                		<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
                			<input type="email" name="emailAddress" id="emailAddress" class="form-control" placeholder="Email" required />
                			<span class="required"></span>
                		</div>
                	</div>
                </div>
        	</div>
        
           <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<span class="required req-description" aria-required="true"></span>
				<p class="title4" style="margin-top:30px;">Текст объявления:</p>
					<textarea name="description" id="inputDescription" class="form-control" rows="10" required></textarea>
				</div>
        	</div>
			
			<div class="top col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<span class="row required"></span>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<p class="title4">Расположение:</p>
					<span class="location"></span>
					<input type="text" name="address" id="pacinput" class="form-control" placeholder="Введите месторасположение" required />
					<input type="hidden" name="addressLat" id="addressLat">
					<input type="hidden" name="addressLong" id="addressLong">
				</div>
			</div>
			<!-- Business locaiton map -->
			 <div id="event-location-map"></div> 
			 
			<div class="top2 col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin:15px auto !important;">
				<input type="text" name="info" id="info" class="form-control" placeholder="Укажите дополнительную информацию о расположении мероприятия если необходимо">
			</div>
        
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<p class="title4 text-center">Выберите вариант размещения объявления</p>
				<p>Мы предлагаем несколько вариантов размещения, пожалуйста, выберите, какой Вам подходит лучше всех.</p>
			</div>
	
			<div class="col-centered col-xs-6 col-sm-6 col-md-6 col-lg-10">
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
      <div class="modal-dialog modal-lg" role="document">
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
                    
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3" style="margin-bottom:0px;">
                    	<b><?php echo $class_CatArr->name; ?></b>
                        <div class="clearfix"></div>
                        <?php $child_catarr = get_terms('classified_cat', array('hide_empty' => false, 'parent' => $class_CatArr->term_id )); ?>
                        <?php foreach($child_catarr as $childcat) { ?>
                            <div class="radio" style="width:100%;">
                                <label>
                                  <input type="radio" name="category1" id="input" value="<?php echo $childcat->term_id;?>">
                                  <?php echo $childcat->name; ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>

                    <?php if($flag % 4 == 0) { echo '<div class="clearfix"></div>'; } ?>
                    
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
    <button type="submit" name="mrPalceNewAd" value="Разместить объявление" class="btn btn-primary">Разместить объявление</button>
    </div>
        </form>
						</div>
					</div>
				</div>
			</div><!-- end .row -->
		</div><!-- end col-lg-9 -->
        <?php get_sidebar(); ?>
	</div><!-- end .row -->
</div><!-- end .container -->

<!--Classified Modal Box-->
<div aria-labelledby="confirmModalLabel" role="dialog" tabindex="-1" id="confirmModal" class="modal fade">
  <div role="document" class="modal-dialog">
    <div class="modal-content text-center">
      <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" id="mrclose-model" class="close" type="button"><span aria-hidden="true"></span></button>
        <h4 class="title4">Спасибо!</h4>
      </div>
      <div class="modal-body">
        	<p>Ваш бизнес успешно добавлен в очередь на модерацию и после проверки вы сможете увидеть его на сайте</p>
      </div>
    </div>
  </div>
</div>
<!--End of Classified Modal Box-->
<script type="text/javascript">
  function showLocation(position) {
	var latitude = position.coords.latitude;
	var longitude = position.coords.longitude;
	alert("Latitude : " + latitude + " Longitude: " + longitude);
 }

 function errorHandler(err) {
	if(err.code == 1) {
	   alert("Error: Access is denied!");
	}
	
	else if( err.code == 2) {
	   alert("Error: Position is unavailable!");
	}
 }
			
function getLocation(){

	if(navigator.geolocation){
	   // timeout at 60000 milliseconds (60 seconds)
	   var options = {timeout:60000};
	   navigator.geolocation.getCurrentPosition(showLocation, errorHandler, options);
	}
	
	else{
	   alert("Sorry, browser does not support geolocation!");
	}
}

function initAutocomplete() 
{
   var map = new google.maps.Map(document.getElementById('event-location-map'), {
    center: {lat: -33.8688, lng: 151.2195},
    zoom:7,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  });
  
 //Create the search box and link it to the UI element.
  var input = document.getElementById('pacinput');
  var searchBox = new google.maps.places.SearchBox(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  // Bias the SearchBox results towards current map's viewport.
  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });

  var markers = [];
  // [START region_getplaces]
 
  searchBox.addListener('places_changed', function() {
	 
    var places = searchBox.getPlaces();
       //Get the lat long 
		 var geocoder = new google.maps.Geocoder();
			var address = document.getElementById("pacinput").value;
			geocoder.geocode({ 'address': address }, function (results, status)
			 {
				if (status == google.maps.GeocoderStatus.OK) {
					var latitude = results[0].geometry.location.lat();
					var longitude = results[0].geometry.location.lng();
					document.getElementById("addressLat").value=latitude;
					document.getElementById("addressLong").value=longitude;
					//alert("Latitude: " + latitude + "\nLongitude: " + longitude);
				} else {
					//alert("Request failed.")
				}
			});
	    //End code	
    if (places.length == 0) {
      return;
    }
    // Clear out the old markers.
    markers.forEach(function(marker) {
      marker.setMap(null);
    });
    markers = [];

   //For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
      var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };
	  
      // Create a marker for each place.
      markers.push(new google.maps.Marker({
        map: map,
        icon: icon,
        title: place.name,
        position: place.geometry.location
      }));

      if (place.geometry.viewport) 
	  {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
	
     map.fitBounds(bounds);
	 map.setZoom(14);
  });
  // [END region_getplaces]
}

jQuery(document).ready(function(e) {
    
	initAutocomplete();
	
	jQuery.validator.setDefaults({
        ignore: []
    });
	
	var container = jQuery('div.error-container');
	// validate the form when it is submitted
	var validator = jQuery("#classified").submit(function() { tinyMCE.triggerSave(); }).validate({
	    ignore: "",
		rules: {
			mrAdTitle: "required",
			category1: "required",
			emailAddress: {
				required: true,
				email: true
			},
			description: "required",
			address: "required"
		},
		messages: {
			mrAdTitle: "Пожалуйста введите Заголовок",
			category1: "Пожалуйста выберите категорию",
			emailAddress: "Пожалуйста введите действительный email адрес ",
			description: "Пожалуйста введите описание",
			address: "Пожалуйста введите расположение"
		},
		errorContainer: container,
		errorLabelContainer: jQuery("ol", container),
		wrapper: 'li'
	});
});
</script>
<?php get_footer(); ?>
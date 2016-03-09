<?php 
/*
Template Name: Business Directory Add
*/

if(!is_user_logged_in())
{ wp_redirect(home_url()); }

get_header();

$user_ID = get_current_user_id();
 
 //Cost settins data
 $BusinssCostArr = get_option('mrBusinssCostOptions'); 
 $businessCostOptArr = $BusinssCostArr['businessCostOptions'];

 $mrPaypalsetting = get_option('mrPaypalsetting',true);
 $currencyCode = $mrPaypalsetting['currency_code'];
 $paypalurl = $mrPaypalsetting['paypalurl'];
?>
<div id="main-container" class="container classified event-add business-directory-add">
	<div class="row top-buffer2">
   		<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="overflow">
						<div class="title pull-left">
	                        <span>Размещение в бизнес справочнике</span>
	                    </div>
                    </div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						 <p class="title4 text-center">Укажите ваши данные для размещения в бизнес справочнике</p>
						 <p>Для того, чтобы ваш бизнес был опубликован, пожалуйста, заполните необходимые поля. Загрузив фотографию (и), привлекательность вашего бизнеса возрастает.</p>
						</div>
					</div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 error-container" style="display:none;">
						<h4>При создании нового бизнеса не были заполнены обязательные поля:</h4>
						<ol>
							<li><label for="businessDirtitle" class="error">Please enter Title.</label></li>
							<li><label for="businessCategory" class="error">Please enter url.</label></li>
							<li><label for="phoneNo" class="error">Please enter number.</label></li>
							<li><label for="emailAddress" class="error">Please enter a valid email address.</label></li>
							<li><label for="description" class="error">Please enter description.</label></li>
							<li><label for="address" class="error">Please enter location.</label></li>
						</ol>
					</div>
                    
					<div class="row">
					   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                       <?php
					    $currentDate = current_time('mysql'); 	 	 
					    $payHistorytab = $wpdb->prefix."payment_history";	// Payment Status Table	
					  
						if(isset($_SESSION['success_mess']))
						{
						 ?>
                          <div class="alert alert-success">
                              <strong>Well done !</strong> <?php echo $_SESSION['success_mess']; ?>
                            </div>
                         <?php	 
						 unset($_SESSION['success_mess']);						
						}
						
						// Place new Ad
						  if(isset($_POST['addNewBusinessDir']))
						  {
						  		extract($_POST);
							    // Create Event post 
								$my_newBusinesdir = array(
								  'post_title' => wp_strip_all_tags($_POST['businessDirtitle'] ),
								  'post_type' =>'business',
								  'post_content' => $_POST['description'],
								  'post_status' => 'publish',
								  'post_author' => $user_ID								 						  
								);
								// Insert the Ad into the database
								$insertBdirId = wp_insert_post($my_newBusinesdir);
								if($insertBdirId)
								{
								   //Assign category 	
								   wp_set_object_terms( $insertBdirId, intval($businessCategory), 'bussiness_cat', true);

								   if( count($_POST['attachid']) > 0 ) {

										if(isset($_POST['attachid'][0])) { set_post_thumbnail( $insertBdirId, $_POST['attachid'][0] ); }
	
										if( count($_POST['attachid']) > 1 ) {
											array_shift($_POST['attachid']);
											$attachimplode = implode(",", $_POST['attachid']);
											update_post_meta($insertBdirId,'fg_temp_metadata',$attachimplode);
											update_post_meta($insertBdirId,'fg_perm_metadata',$attachimplode);
										}
									}
								   
								   update_post_meta($insertBdirId,'website',$websiteUrl);	
								   update_post_meta($insertBdirId,'phone',$phoneNo);
								   update_post_meta($insertAdId,'emailaddress',$emailAddress);
								   	
								   $locationArr = array('address'=>trim($address),'lat'=>$addressLat,'lng'=>$addressLong);
								   update_post_meta($insertBdirId,'address',$locationArr);
								   update_post_meta($insertBdirId,'info',$info);
								   update_post_meta($insertBdirId,'stay_in_business_directory',$stay_in_business_directory);

								   changePoststatus($insertBdirId,'pending'); //Update Budsiness status
								   
								   $_SESSION['success_mess'] ='Ваш бизнес успешно добавлен в справочник, но находится на рассмотрении.';								  
								}
								
						  //Insert data in trasection history	
							$wpdb->query("insert into $payHistorytab set postId='$insertBdirId',activeDays='$days', payAmt='',userId='$user_ID',payStatus='pending',postType='business', postStatus='pending',date='$currentDate'");
							
						  //PayPal settings
							$paypal_email = $mrPaypalsetting['paypalEmail'];
							$paypalAction_url =$paypalurl;
							$return_url = get_permalink( get_page_by_title("Business Directory Add") );
							$cancel_url = get_permalink( get_page_by_title("Cancel Business") );
							$notify_url = get_permalink( get_page_by_title("Business Directory Add") );
							$item_name = get_the_title($insertBdirId);
							
							if($days)
							{
							    $item_amount="";																
							  	for($j=0;$j<count($businessCostOptArr);$j++)
								  {
									 $postBusinssDays =$businessCostOptArr[$j]['postBusinssDays'];
									 $postBusinsscost =$businessCostOptArr[$j]['postBusinsscost'];
									 if(trim($postBusinssDays)==trim($days))
									 {
									   $item_amount = $postBusinsscost;
									 }
								  }
							}
							
							
							$currency_code =$currencyCode;
							$ccUserName =$current_user->display_name;
						 //Check if paypal request or response
						 // If free 
						  if($item_amount == 0 || $item_amount == 'free')
						  {
							 //changePoststatus($insertBdirId,'publish'); // Update post status
							 $lasttransid = $wpdb->get_row("select tid from $payHistorytab where postId = '$insertBdirId' and postType = 'business' and payStatus = 'pending' ORDER BY tid DESC limit 0,1")->tid;
							 $wpdb->query("update $payHistorytab set payStatus='$st',payAmt='$item_amount', postStatus='active',date='$currentDate' where postId='$insertBdirId' AND tid = '$lasttransid'");
							 ?>
							  <script type="text/javascript">
							   jQuery(document).ready(function(e) {
								 jQuery('#mrclose-model').click(function(){
								   jQuery("#confirmModal").removeClass('in');
								   jQuery("#confirmModal").hide();
								   jQuery("#confirmModal").after('');
								   window.location="<?php echo get_permalink($insertBdirId);?>";						 
								 }); 
								 
								 jQuery("#confirmModal").addClass('in');
								 jQuery("#confirmModal").show();
								 jQuery("#confirmModal").after('<div class="modal-backdrop fade in"></div>');
								 setTimeout(function(){window.location="<?php echo get_permalink($insertBdirId);?>";},5000);
							   });
							 </script>	
							 <?php
						  } else if($item_amount > 0) {

							 $querystring ='';
							 $querystring .= "?business=".urlencode($paypal_email)."&";
							 $querystring .= "item_name=".urlencode('businessdirectoryadd')."&";
							 $querystring .= "item_number=".urlencode($insertBdirId)."&";
							 
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
							  window.location="<?php echo $paypalAction_url.$querystring;?>";						
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
									
									$lasttransid = $wpdb->get_row("select tid from $payHistorytab where postId = '$item_number' and postType = 'business' and payStatus = 'pending' ORDER BY tid DESC limit 0,1")->tid;
							   		$wpdb->query("update $payHistorytab set payStatus='$st',payAmt='$amt',postStatus='active', date='$currentDate' where postId='$item_number' AND tid = '$lasttransid'");
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
							  else
							  {
								 $_SESSION['success_mess'] ='Bussiness Directory '.$st; 
							  }
						  }
						?>								   	   
						  
							<form id="classified" name="classified" method="POST" role="form">				
								<div class="form-group">
									<span class="required"></span>
									<input type="text" class="form-control" name="businessDirtitle" id="businessDirtitle" placeholder="Укажите заголовок вашего объявления" required />
								</div>
                                <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" 
                                aria-labelledby="categoryModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true"></span></button>
                                             <h4 class="modal-title" id="myModalLabel">
                                              Выберите категорию для нового объявления
                                             </h4>
                                            </div>
                                            
                                            <div class="modal-body">
                                            <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <?php
											$flag = 1;
                                            $classified_catarr = get_terms('bussiness_cat',array('order' => 'DESC', 'hide_empty' => false));
                                            foreach($classified_catarr as $class_CatArr)
                                            { ?>
                                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="margin-bottom:0px;">
												<div class="radio">
                                            		<label>
                                            			<input type="radio" name="businessCategory" id="businessCategory" value="<?php echo $class_CatArr->term_id;?>"><?php echo $class_CatArr->name;?>
                                            		</label>
                                           		</div>
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

                        	<div class="row upload">
								<span class="required"></span>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-5">
									<div class="dropzone" drop-zone="" id="file-dropzone">
							    		
										<img id="file-dropzone-img" src="http://www.jimpunk.com/Loading/loading34.gif">
                    					<div class="text-center dz-message" id="file-dropzone-message" data-dz-message>
										 <div><img src="<?php bloginfo('template_url') ?>/images/cloud.png" ></div>
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
											<label class="category text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 
                                            control-label" for="">Категория:</label>
											<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
												<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#categoryModal">Выберите категорию</button>
												<span class="required"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="website text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label" for="">Вебсайт:</label>
											<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
												<input type="text" class="form-control" id="websiteUrl" name="websiteUrl" placeholder="Адрес вебсайта" />
												<span class="required"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="phone text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label" for="">Телефон:</label>
											<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
												<input type="text" class="form-control" id="phoneNo" name="phoneNo" placeholder="Номер телефона" required />
												<span class="required"></span>
											</div>
										</div>
										
										<div class="form-group">
											<label for="" class="location text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label">Email:</label>
											<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
												<input type="email" placeholder="Email" class="form-control" name="emailAddress" id="emailAddress" required />
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
                                    <input type="text" name="address" id="pac-input" class="form-control" placeholder="Введите адрес вашего бизнеса" required />
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
									<p class="title4 text-center">Выберите вариант размещения в бизнес справочнике</p>
									<p class="text">Мы предлагаем несколько вариантов размещения, пожалуйста, выберите, какой Вам подходит лучше всех</p>
										<div class="col-centered col-xs-6 col-sm-6 col-md-6 col-lg-9">
                                         <?php 
										 if(count($businessCostOptArr)>0)
										 {
											  for($i=0;$i<count($businessCostOptArr);$i++)
											  {
												  if($businessCostOptArr[$i]['postBusinssOptTitle'])
												  {
										?>
											<div class="radio">                
											<label>
											<input type="radio" name="days" id="inputDays<?php echo $i;?>"
											value="<?php echo $businessCostOptArr[$i]['postBusinssDays']; ?>"
                                             checked="checked">
											 <?php echo $businessCostOptArr[$i]['postBusinssOptTitle']; ?></span>
											</label>
											</div>
										 <?php    }
											  }
										  }
										?>   
										</div>
									</div>
									<div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
										<button type="submit" name="addNewBusinessDir" value="Разместить бизнес" class="btn btn-primary">Разместить бизнес</button>
										<a type="button" href="javascript:void(0);" onclick="window.history.back();" name="cancleNewBusinessDir" value="Отмена" class="btn btn-primary">Отмена</a>
									</div>
								</div>
							</form>
                            
							<span class="row required text-required"></span>
							<span class="row required address-required"></span>
							<span class="row required info-required"></span>
							<span class="row required select-required"></span>
						</div>
					</div>
				</div>
			</div><!-- end .row -->
		</div><!-- end col-lg-9 -->
        <?php get_sidebar(); ?>
	</div><!-- end .row -->
</div><!-- end .container -->

<div aria-labelledby="confirmModalLabel" role="dialog" tabindex="-1" id="confirmModal" class="modal fade">
  <div role="document" class="modal-dialog">
    <div class="modal-content text-center">
      <div class="modal-header">
        <button aria-label="Close" id="mrclose-model" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"></span></button>
        <h4 class="title4">Спасибо!</h4>
      </div>
      <div class="modal-body">
        	<p>Ваш бизнес успешно добавлен в очередь на модерацию и после проверки вы сможете увидеть его на сайте</p>
      </div>
    </div>
  </div>
</div>
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
  var input = document.getElementById('pac-input');
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
			var address = document.getElementById("pac-input").value;
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
	
	var container = jQuery('div.error-container');
	// validate the form when it is submitted
	var validator = jQuery("#classified").submit(function() { tinyMCE.triggerSave(); }).validate({
		rules: {
			businessDirtitle: "required",
			businessCategory: "required",			
			phoneNo: "required",
			emailAddress: {
				required: true,
				email: true
			},
			address: "required",
			description: "required"
		},
		messages: {
			businessDirtitle: "Пожалуйста введите Заголовок",
			businessCategory: "Пожалуйста выберите категорию",
			phoneNo: "Пожалуйста введите номер телефона",
			emailAddress: "Пожалуйста введите действительный email",
			address: "Пожалуйста введите расположение",
			description: "Пожалуйста введите описание"
		},
		errorContainer: container,
		errorLabelContainer: jQuery("ol", container),
		wrapper: 'li'
	});
});
</script>
<?php get_footer(); ?>
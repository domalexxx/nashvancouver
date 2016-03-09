<?php 
/*
Template Name: Event-Add
*/
if(!is_user_logged_in())
{
 wp_redirect(home_url()); 
}
get_header(); 

//Cost settins data
$user_ID = get_current_user_id();
$eventCostArr = get_option('mrEventCostOptions'); 
$eventCostOptArr = $eventCostArr['eventCostOptions'];

$mrPaypalsetting = get_option('mrPaypalsetting',true);
$currencyCode = $mrPaypalsetting['currency_code'];
$paypalurl = $mrPaypalsetting['paypalurl'];

?>
<div class="container classified event-add">
	<div class="row">
		<?php get_sidebar(); ?>
		<div class="top-buffer2 col-xs-9 col-sm-9 col-md-9 col-lg-9">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<p class="title">Размещение нового мероприятия</p>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<p class="title4 text-center">Заполните этот раздел для размещения нового мероприятия</p>
                       <?php
					     if(have_posts())
						 {
							 while(have_posts())
							 {
							   the_post();
						       the_content();	
							 }
						 }						 
					   ?>     						
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                         <?php
					  $currentDate = current_time('mysql'); 	 	 
					  $payHistorytab = $wpdb->prefix."payment_history";	// Payment Status Table		
					  
					  //Place new Ad
					  if(isset($_POST['mrNewEventSub']))
					  {
						extract($_POST);
						$event_date =$scDate."/".$scMonth; 
					   //Event Start Time
						 $event_start_time =$evnt_time_start_hours.":".$evnt_time_start_minutes." ".$event_start_time;
						 $event_start_time = strtotime($event_start_time);
						 
					   //Event End Time 						
						 $event_end_time = $evnt_time_end_hours.":".$evnt_time_end_minutes." ".$evnt_end_time;
						 $event_end_time = strtotime($event_end_time);
						 
								//Create Event post 
								$my_newEvent = array(
								  'post_title'    => wp_strip_all_tags($_POST['eventTitle'] ),
								  'post_type' =>'event',
								  'post_content'  => $_POST['description'],
								  'post_status'   => 'publish',
								  'post_author'   => $user_ID								 						  
								);
								//Insert the Ad into the database
								$insertEventId = wp_insert_post($my_newEvent);
								if($insertEventId)
								{
								   //Assign category
								   wp_set_object_terms( $insertEventId, intval($eventCategory), 'event_cat', true);
								   
								   if( count($_POST['attachid']) > 0 ) {

										if(isset($_POST['attachid'][0])) { set_post_thumbnail( $insertEventId, $_POST['attachid'][0] ); }
	
										if( count($_POST['attachid']) > 1 ) {
											array_shift($_POST['attachid']);
											$attachimplode = implode(",", $_POST['attachid']);
											update_post_meta($insertEventId,'fg_temp_metadata',$attachimplode);
											update_post_meta($insertEventId,'fg_perm_metadata',$attachimplode);
										}
									}
								   
								   update_post_meta($insertEventId,'price',$mr_price);	
								   update_post_meta($insertEventId,'website',$websiteUrl);	
								   update_post_meta($insertEventId,'phone',$phoneNo);	
					
							      //Google map location and lat long 
								   $locationArr = array('address'=>trim($location),'lat'=>$addressLat,'lng'=>$addressLong);
								   update_post_meta($insertEventId,'location',$locationArr);
								   update_post_meta($insertEventId,'event_date',$event_date);
								   update_post_meta($insertEventId,'event_start_time',$event_start_time);
								   update_post_meta($insertEventId,'event_end_time',$event_end_time);
								   update_post_meta($insertEventId,'event_info',$info);
								   update_post_meta($insertEventId,'eventForDays',$eventForDays);
								   
								   changePoststatus($insertEventId,'pending');	//Update post status
								}	
							 //Insert data in trasection history	
							$wpdb->query("insert into $payHistorytab set postId='$insertEventId',activeDays='$days',
							payAmt='',userId='$user_ID',payStatus='pending',postType='event',
							postStatus='pending',date='$currentDate'");
							
						  //PayPal settings
							$paypal_email = $mrPaypalsetting['paypalEmail'];
							$paypalAction_url =$paypalurl;
							$return_url = get_permalink( get_page_by_title("Event Add") );
							$cancel_url = get_permalink( get_page_by_title("Event Add") );
							$notify_url = get_permalink( get_page_by_title("Event Add") );
							$item_name = get_the_title($insertEventId);
							
							if($days)
							{
							    $item_amount="";																
							  	for($j=0;$j<count($eventCostOptArr);$j++)
								  {
									 $postEventDays =$eventCostOptArr[$j]['postEventDays'];
									 $postEventcost =$eventCostOptArr[$j]['postEventcost'];
									 if(trim($postEventDays)==trim($days))
									 {
										$item_amount = $postEventcost;
									 }
								  }
							}
							
							$currency_code =$currencyCode;
							$ccUserName =$current_user->display_name;
						 //Check if paypal request or response
						 if($item_amount==0 || $item_amount=='free')
						  {
							 changePoststatus($insertBdirId,'publish'); // Update post status 							
							 $wpdb->query("update $payHistorytab set payStatus='$st',payAmt='$item_amount',
							 postStatus='active',date='$currentDate' where postId='$insertEventId'");
							 ?>
							  <script type="text/javascript">
							   jQuery(document).ready(function(e) {
								 jQuery('#mrclose-model').click(function(){
								   jQuery("#confirmModal").removeClass('in');
								   jQuery("#confirmModal").hide();
								   jQuery("#confirmModal").after('');
								   window.location="<?php echo get_permalink($insertEventId);?>";						 
								 }); 
								 
								 jQuery("#confirmModal").addClass('in');
								 jQuery("#confirmModal").show();
								 jQuery("#confirmModal").after('<div class="modal-backdrop fade in"></div>');
								 setTimeout(function(){window.location="<?php echo get_permalink($insertEventId);?>";},5000);
							   });
							 </script>	
							 <?php
						  } else if($item_amount > 0) {

							 $querystring ='';
							 $querystring .= "?business=".urlencode($paypal_email)."&"; 
							 $querystring .= "item_name=".urlencode('eventadd')."&";
							 $querystring .= "item_number=".urlencode($insertEventId)."&";
							 
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
								changePoststatus($item_number,'publish'); // Update post status
								$_SESSION['success_mess']=1;
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
						
							<form id="classified" action="" method="POST" role="form">				
								<div class="form-group">
									<span class="required"></span>
									<input type="text" class="form-control" name="eventTitle" 
                                     placeholder="Укажите заголовок вашего мероприятия" required>
								</div>
                              <!-- Categrory popup -->  
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
                                                    <!--Авто-->
                                                    <?php 
                                                    $classified_catarr = get_terms('event_cat',array('order'=> 'DESC', 'hide_empty' => false));
                                                     foreach($classified_catarr as $class_CatArr)
                                                     {
                                                     ?>
                                                       <div class="radio">
                                                        <label>
                                                         <input type="radio" name="eventCategory" id="input" 
                                                         value="<?php echo $class_CatArr->term_id;?>">
                                                            <?php echo $class_CatArr->name;?>
                                                        </label>
                                                    </div>
                                                     <?php  	 
                                                     }				  
                                                    ?>
                                                </div>
                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">
                                        Сохранить изменения</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                        Отменить</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              <!-- Close category popup -->

							<div class="row upload">
								<span class="required"></span>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-5">
									<div class="dropzone" drop-zone="" id="file-dropzone">
							    		<div class="text-center dz-message" data-dz-message>
											<div>
                                            <img src="<?php bloginfo('template_url') ?>/images/cloud.png" alt=""></div>
								    		<p>Перетяните изображения сюда<br>
								    		или<br>Воспользуйтесь обычным загрузчиком</p>
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
												<button type="button" class="btn btn-primary btn-lg" data-toggle="modal"
                                                 data-target="#categoryModal">
												  Выберите категорию
												</button>
												<span class="required"></span>
											</div>
										</div>
										
                                        <div class="form-group">
										<label class="website text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 
                                            control-label" for="">Вебсайт:</label>
												<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
                                                <input name="websiteUrl" type="text" class="form-control"
                                                 placeholder="Адрес вебсайта">
											 <span class="required"></span>
										   </div>
										</div>
                                        
										<div class="form-group">
											<label class="phone text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 
                                            control-label" for="">Организаторы:</label>
												<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
													<input type="text" class="form-control" name="phoneNo"  
                                                    placeholder="Номер телефона">
													<span class="required"></span>
												</div>
										</div>
										<div class="form-group">
											<label class="price text-left col-xs-10 col-sm-10 col-md-10 col-lg-4
                                             control-label" for="">Стоимость:</label>
											<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
												<input type="text" class="form-control" name="mr_price"
                                                placeholder="Укажите стоимость">
												<span class="required"></span>
											</div>
										</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<textarea name="description" id="inputDescription" class="form-control" 
                                    rows="10"></textarea>
								</div>
							</div>
							<div class="top ol-xs-12 col-sm-12 col-md-12 col-lg-12">
							<span class="row required"></span>
								<div class="schedule col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<p class="title4">Расписание:</p>
									<div class="row">
										<div class="col1 col-xs-1 col-sm-1 col-md-1 col-lg-1">
											<span class="date control-label">Число:</span>
										</div>
										<div class="row month col-xs-4 col-sm-4 col-md-4 col-lg-4">
											<span class="date control-label">Месяц:</span>
										</div>
										<div class="time col-xs-4 col-sm-4 col-md-4 col-lg-4">
											<span class="date control-label">Время начала:</span>
										</div>
										<div class="time col-xs-4 col-sm-4 col-md-4 col-lg-4">
											<span class="date control-label">Время окончания</span>
										</div>
									</div>
									<div class="row">
										<div class="col1 col-xs-1 col-sm-1 col-md-1 col-lg-1">
											<div class="form-group">
												<select name="scDate" id="scDate" class="selectpicker form-control"
                                                 required>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
													<option value="6">6</option>
													<option value="7">7</option>
													<option value="8">8</option>
													<option value="9">9</option>
													<option value="10">10</option>
													<option value="11">11</option>
													<option value="12">12</option>
													<option value="13">13</option>
													<option value="14">14</option>
													<option value="15">15</option>
													<option value="16">16</option>
													<option value="17">17</option>
													<option value="18">18</option>
													<option value="19">19</option>
													<option value="20">20</option>
													<option value="21">21</option>
													<option value="22">22</option>
													<option value="23">23</option>
													<option value="24">24</option>
													<option value="25">25</option>
													<option value="26">26</option>
													<option value="27">27</option>
													<option value="28">28</option>
													<option value="29">29</option>
													<option value="30">30</option>
													<option value="31">31</option>
												</select>
											</div>
										</div>
										<div class="row month col-xs-3 col-sm-3 col-md-3 col-lg-4">
											<div class="form-group">
										<select name="scMonth" id="scMonth" class="selectpicker form-control" required>
													<option value="01">Января</option>
													<option value="02">Февраля</option>
													<option value="03">Марта</option>
													<option value="04">Апреля</option>
													<option value="05">Мая</option>
													<option value="06">Июня</option>
													<option value="07">Июля</option>
													<option value="08">Августа</option>
													<option value="09">Сентября</option>
													<option value="10">Октября</option>
													<option value="11">Ноября</option>
													<option value="12">Декабря</option>
												</select>
											</div>
										</div>
										<div class="col1 col-xs-3 col-sm-3 col-md-3 col-lg-1">
											<div class="form-group">
												<select name="evnt_time_start_hours" id="time-start-hours" 
                                                class="selectpicker form-control" required>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
													<option value="6">6</option>
													<option value="7">7</option>
													<option value="8">8</option>
													<option value="9">9</option>
													<option value="10">10</option>
													<option value="11">11</option>
													<option value="12">12</option>
												</select>
											</div>
										</div>
										<div class="col1 row col-xs-3 col-sm-3 col-md-3 col-lg-1">
											<div class="form-group">
												<select name="evnt_time_start_minutes" id="time-start-minutes" 
                                                class="selectpicker form-control" required>
													<option value="00">0</option>
													<option value="01">1</option>
													<option value="02">2</option>
													<option value="03">3</option>
													<option value="04">4</option>
													<option value="05">5</option>
													<option value="06">6</option>
													<option value="07">7</option>
													<option value="08">8</option>
													<option value="09">9</option>
													<option value="10">10</option>
													<option value="11">11</option>
													<option value="12">12</option>
													<option value="13">13</option>
													<option value="14">14</option>
													<option value="15">15</option>
													<option value="16">16</option>
													<option value="17">17</option>
													<option value="18">18</option>
													<option value="19">19</option>
													<option value="20">20</option>
													<option value="21">21</option>
													<option value="22">22</option>
													<option value="23">23</option>
													<option value="24">24</option>
													<option value="25">25</option>
													<option value="26">26</option>
													<option value="27">27</option>
													<option value="28">28</option>
													<option value="29">29</option>
													<option value="30">30</option>
													<option value="31">31</option>
													<option value="32">32</option>
													<option value="33">33</option>
													<option value="34">34</option>
													<option value="35">35</option>
													<option value="36">36</option>
													<option value="37">37</option>
													<option value="38">38</option>
													<option value="39">39</option>
													<option value="40">40</option>
													<option value="41">41</option>
													<option value="42">42</option>
													<option value="43">43</option>
													<option value="44">44</option>
													<option value="45">45</option>
													<option value="46">46</option>
													<option value="47">47</option>
													<option value="48">48</option>
													<option value="49">49</option>
													<option value="50">50</option>
													<option value="51">51</option>
													<option value="52">52</option>
													<option value="53">53</option>
													<option value="54">54</option>
													<option value="55">55</option>
													<option value="56">56</option>
													<option value="57">57</option>
													<option value="58">58</option>
													<option value="59">59</option>
												</select>
											</div>
										</div>
										<div class="col1 time-divider col-xs-3 col-sm-3 col-md-3 col-lg-1">
											<div class="form-group">
										    <select name="event_start_time" id="time" class="selectpicker form-control" required>
                                             <option value="am">AM</option>
                                             <option value="pm">PM</option>
                                            </select>
											</div>
										</div>
										<div class="col1 col-xs-3 col-sm-3 col-md-3 col-lg-1">
											<div class="form-group">
												<select name="evnt_time_end_hours" id="time-start-hours" 
                                                class="selectpicker form-control" required>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
													<option value="6">6</option>
													<option value="7">7</option>
													<option value="8">8</option>
													<option value="9">9</option>
													<option value="10">10</option>
													<option value="11">11</option>
													<option value="12">12</option>
												</select>
											</div>
										</div>
										<div class="col1 row col-xs-3 col-sm-3 col-md-3 col-lg-1">
											<div class="form-group">
												<select name="evnt_time_end_minutes" id="time-start-minutes"
                                                 class="selectpicker form-control" required>
													<option value="00">0</option>
													<option value="01">1</option>
													<option value="02">2</option>
													<option value="03">3</option>
													<option value="04">4</option>
													<option value="05">5</option>
													<option value="06">6</option>
													<option value="07">7</option>
													<option value="08">8</option>
													<option value="09">9</option>
													<option value="10">10</option>
													<option value="11">11</option>
													<option value="12">12</option>
													<option value="13">13</option>
													<option value="14">14</option>
													<option value="15">15</option>
													<option value="16">16</option>
													<option value="17">17</option>
													<option value="18">18</option>
													<option value="19">19</option>
													<option value="20">20</option>
													<option value="21">21</option>
													<option value="22">22</option>
													<option value="23">23</option>
													<option value="24">24</option>
													<option value="25">25</option>
													<option value="26">26</option>
													<option value="27">27</option>
													<option value="28">28</option>
													<option value="29">29</option>
													<option value="30">30</option>
													<option value="31">31</option>
													<option value="32">32</option>
													<option value="33">33</option>
													<option value="34">34</option>
													<option value="35">35</option>
													<option value="36">36</option>
													<option value="37">37</option>
													<option value="38">38</option>
													<option value="39">39</option>
													<option value="40">40</option>
													<option value="41">41</option>
													<option value="42">42</option>
													<option value="43">43</option>
													<option value="44">44</option>
													<option value="45">45</option>
													<option value="46">46</option>
													<option value="47">47</option>
													<option value="48">48</option>
													<option value="49">49</option>
													<option value="50">50</option>
													<option value="51">51</option>
													<option value="52">52</option>
													<option value="53">53</option>
													<option value="54">54</option>
													<option value="55">55</option>
													<option value="56">56</option>
													<option value="57">57</option>
													<option value="58">58</option>
													<option value="59">59</option>
												</select>
											</div>
										</div>
										<div class="col1 col-xs-3 col-sm-3 col-md-3 col-lg-1">
											<div class="form-group">
												<select name="evnt_end_time" id="evnt_end_time" 
                                                class="selectpicker form-control" required>
													<option value="am">AM</option>
													<option value="pm">PM</option>
												</select>
											</div>
										</div>
										
									</div>
									<!--
                                    <a href="#" class="add_schedule text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
										 + Добавить еще расписание
									</a>
                                    -->
								</div>
							</div>
							<div class="top col-xs-12 col-sm-12 col-md-12 col-lg-12">
							    <span class="row required"></span>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<p class="title4">Адрес:</p>
									<span class="location"></span>
                                   <div class="mr-address-search-box"> 
                                    <input type="text" name="location" id="pac-input" class="form-control" 
                                     required  placeholder="Введите адрес мероприятия">
                                     <input type="hidden" name="addressLat" id="addressLat">
                                    <input type="hidden" name="addressLong" id="addressLong">
                                   </div>  
								</div>
							</div>                           
							<div id="event-location-map"></div> 
							<div class="top2 col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<span class="row required"></span>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<input type="text" name="info" id="info" class="form-control"  
                                     placeholder="Укажите дополнительную информацию о расположении мероприятия 
                                     если необходимо">
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<p class="title4 text-center">Выберите вариант размещения объявления</p>
									<p class="title5">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
									</div>
									
									<div class="col-centered col-xs-6 col-sm-6 col-md-6 col-lg-7">
									<?php 
									 if(count($eventCostOptArr)>0)
									 {
										  for($i=0;$i<count($eventCostOptArr);$i++)
										  {
											  if($eventCostOptArr[$i]['postEventOptTitle'])
											  { ?>
												<div class="radio">                
													<label>
														<input type="radio" name="days" id="inputDays<?php echo $i;?>" value="<?php echo $eventCostOptArr[$i]['postEventDays']; ?>" checked="checked">
													 	<?php echo $eventCostOptArr[$i]['postEventOptTitle']; ?></span>
													</label>
												</div>
									 			<?php }
										  }
									  }
									?>
									</div>
									
									<div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
										<button type="submit" name="mrNewEventSub" value="Разместить мероприятие" class="btn btn-primary">
										Разместить мероприятие</button>
									</div>
								</div>
							</div>
							</form>
							<span class="row required text-required"></span>
							<span class="row required schedule-required"></span>
							<span class="row required address-required"></span>
							<span class="row required info-required"></span>
							<span class="row required select-required"></span>
						</div>
					</div>
				</div>
			</div><!-- end .row -->
		</div><!-- end col-lg-9 -->
	</div><!-- end .row -->
</div><!-- end .container -->

<div aria-labelledby="confirmModalLabel" role="dialog" tabindex="-1" id="confirmModal" class="modal fade" style="display: none;">
  <div role="document" class="modal-dialog">
    <div class="modal-content text-center">
      <div class="modal-header">
        <button aria-label="Close" id="mrclose-model" data-dismiss="modal" class="close" type="button">
        <span aria-hidden="true"></span></button>
        <h4 class="title4">Спасибо!</h4>
      </div>
      <div class="modal-body">
        	<p>Ваше мероприятие было успешно размещено на сайте<br> и мы покажем вам его через <strong>5 сек.</strong></p>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

function initAutocomplete() 
{
   var map = new google.maps.Map(document.getElementById('event-location-map'), {
    center: {lat: -33.8688, lng: 151.2195},
    zoom:12,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });
  // Create the search box and link it to the UI element.
  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.SearchBox(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  // Bias the SearchBox results towards current map's viewport.
  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });

  var markers = [];
  // [START region_getplaces]
  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
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

    // For each place, get the icon, name and location.
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

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);
  });
  // [END region_getplaces]
}
jQuery(document).ready(function(e) {
    initAutocomplete();
});
</script>

<?php get_footer(); ?>
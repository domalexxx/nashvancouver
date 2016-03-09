<?php 
/*
Template Name: Event-Add
*/

if(!is_user_logged_in())
{ wp_redirect(home_url()); }

get_header(); 

//Cost settins data
$user_ID = get_current_user_id();
$eventCostArr = get_option('mrEventCostOptions'); 
$eventCostOptArr = $eventCostArr['eventCostOptions'];

$mrPaypalsetting = get_option('mrPaypalsetting',true);
$currencyCode = $mrPaypalsetting['currency_code'];
$paypalurl = $mrPaypalsetting['paypalurl'];

?>
<div id="main-container" class="container classified event-add">
	<div class="row top-buffer2">
		<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<p class="title">Размещение нового мероприятия</p>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<p class="title4 text-center">Заполните этот раздел для размещения нового мероприятия</p>
							<p>Для того, чтобы ваше мероприятие было опубликовано, пожалуйста, заполните необходимые поля. Загрузив фотографию (и), привлекательность вашего мероприятия возрастает.</p>
						</div>
					</div>
                    
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 error-container" style="display:none;">
						<h4>При создании нового мероприятия не были заполнены обязательные поля:</h4>
						<ol>
							<li><label for="eventTitle" class="error">Пожалуйста введите Заголовок</label></li>
							<li><label for="eventCategory" class="error">Please choose event category.</label></li>
							<li><label for="organiser" class="error">Please enter organiser.</label></li>
							<li><label for="emailAddress" class="error">Пожалуйста введите действительный email адрес</label></li>
							<li><label for="description" class="error">Please enter description.</label></li>
							<li><label for="location" class="error">Please enter location.</label></li>
						</ol>
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
							 
							//Create Event post 
							$my_newEvent = array(
							  'post_title'    => wp_strip_all_tags($_POST['eventTitle'] ),
							  'post_type'     => 'event',
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
							   update_post_meta($insertEventId,'organiser',$organiser);
							   update_post_meta($insertEventId,'emailaddress',$emailAddress);
				
							  //Google map location and lat long 
							   $locationArr = array('address'=>trim($location),'lat'=>$addressLat,'lng'=>$addressLong);
							   update_post_meta($insertEventId,'location',$locationArr);
							   update_post_meta($insertEventId,'event_info',$info);

							    if( count($_POST['scDate']) > 0 && count($_POST['scMonth']) > 0 ){
								
									for($counter=0; $counter < count($_POST['scDate']); $counter++){
										update_post_meta($insertEventId,'event_date_'.$counter, $_POST['scDate'][$counter]);
										update_post_meta($insertEventId,'event_month_'.$counter, $_POST['scMonth'][$counter]);
										update_post_meta($insertEventId,'evnt_time_start_hours_'.$counter, $_POST['evnt_time_start_hours'][$counter]);
										update_post_meta($insertEventId,'evnt_time_start_minutes_'.$counter, $_POST['evnt_time_start_minutes'][$counter]);
										update_post_meta($insertEventId,'event_start_time_'.$counter, $_POST['event_start_time'][$counter]);
										//update_post_meta($insertEventId,'evnt_time_end_hours_'.$counter, $_POST['evnt_time_end_hours'][$counter]);
										//update_post_meta($insertEventId,'evnt_time_end_minutes_'.$counter, $_POST['evnt_time_end_minutes'][$counter]);
										//update_post_meta($insertEventId,'evnt_end_time_'.$counter, $_POST['evnt_end_time'][$counter]);
									}
								}
							   
							   changePoststatus($insertEventId,'pending');	//Update post status
							}
							
							//Insert data in trasection history	
							$wpdb->query("insert into $payHistorytab set postId='$insertEventId',activeDays='$days', payAmt='',userId='$user_ID',payStatus='pending',postType='event', postStatus='pending',date='$currentDate'");
							
						  //PayPal settings
							$paypal_email = $mrPaypalsetting['paypalEmail'];
							$paypalAction_url =$paypalurl;
							$return_url = get_permalink( get_page_by_title("Event Add") );
							$cancel_url = get_permalink( get_page_by_title("Cancel Event") );
							$notify_url = get_permalink( get_page_by_title("Event Add") );
							$item_name = get_the_title($insertEventId);
							
							if($days)
							{
								$item_amount="";																
								for($j=0; $j<count($eventCostOptArr); $j++)
								{
									$postEventDays = $eventCostOptArr[$j]['postEventDays'];
									$postEventcost = $eventCostOptArr[$j]['postEventcost'];
									if(trim($postEventDays) == trim($days))
									{
										$item_amount = $postEventcost;
									}
								}
							}
							
							$currency_code = $currencyCode;
							$ccUserName = $current_user->display_name;
						 //Check if paypal request or response
						 if($item_amount==0 || $item_amount=='free')
						  {
							 //changePoststatus($insertEventId,'publish'); // Update post status
							 
							 $lasttransid = $wpdb->get_row("select tid from $payHistorytab where postId = '$insertBdirId' and postType = 'event' and payStatus = 'pending' ORDER BY tid DESC limit 0,1")->tid;
							 $wpdb->query("update $payHistorytab set payStatus='$st',payAmt='$item_amount', postStatus='active',date='$currentDate' where postId='$insertEventId' AND tid = '$lasttransid'");
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
							if($st=="Completed") {					
								//changePoststatus($item_number,'publish'); // Update post status
								$_SESSION['success_mess']=1;
							}
							   
							 $lasttransid = $wpdb->get_row("select tid from $payHistorytab where postId = '$item_number' and postType = 'event' and payStatus = 'pending' ORDER BY tid DESC limit 0,1")->tid;
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
						?>				  
						
							<form id="classified" name="classified" method="POST" role="form">
								<span class="required req-image"></span>
								<span class="required req-description"></span>
								<span class="required req-schedule"></span>
								<span class="required req-address"></span>
								<span class="required req-location"></span>
								<span class="required req-select"></span>
								<div class="form-group">
									<span class="required"></span>
									<input type="text" class="form-control" name="eventTitle" id="eventTitle" placeholder="Укажите заголовок вашего мероприятия" required />
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
													$flag = 1;
                                                    $classified_catarr = get_terms('event_cat',array('order'=> 'DESC', 'hide_empty' => false));
                                                     foreach($classified_catarr as $class_CatArr)
                                                     { ?>
														 <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="margin-bottom:0px;">
														   <div class="radio">
															<label>
															 <?php if($flag == 1) { ?>
															 	<input type="radio" name="eventCategory" id="input-<?php echo $class_CatArr->term_id;?>" value="<?php echo $class_CatArr->term_id;?>" required /><?php echo $class_CatArr->name;?>
															 <?php } else { ?>
															 	 <input type="radio" name="eventCategory" id="input-<?php echo $class_CatArr->term_id;?>" value="<?php echo $class_CatArr->term_id;?>"><?php echo $class_CatArr->name;?>
															 <?php } ?>
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
                              <!-- Close category popup -->

							<div class="row upload top-buffer3">
								<span class="required"></span>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-5">
									<div class="dropzone" drop-zone="" id="file-dropzone">
							    		<img id="file-dropzone-img" src="http://www.jimpunk.com/Loading/loading34.gif">
                    					<div class="text-center dz-message" id="file-dropzone-message" data-dz-message>
											<div><img src="<?php bloginfo('template_url') ?>/images/cloud.png" alt=""></div>
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
											<label class="category text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label" for="">Категория:</label>
											<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
												<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#categoryModal">
												  Выберите категорию
												</button>
												<span class="required"></span>
											</div>
										</div>
										
                                        <div class="form-group">
										<label class="website text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label" for="">Вебсайт:</label>
												<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
                                                <input name="websiteUrl" id="websiteUrl" type="text" class="form-control" placeholder="Адрес вебсайта"  />
											 <!--<span class="required"></span>-->
										   </div>
										</div>
                                        
										<div class="form-group">
											<label class="phone text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 
                                            control-label" for="">Организаторы:</label>
												<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
													<input type="text" class="form-control" name="organiser" id="organiser" placeholder="Название компании" required />
													<span class="required"></span>
												</div>
										</div>
										<div class="form-group">
											<label class="phone text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 
                                            control-label" for="">Телефон:</label>
												<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
													<input type="text" class="form-control" name="phoneNo" id="phoneNo" placeholder="Номер телефона"  />
													<!--<span class="required"></span>-->
												</div>
										</div>
										<div class="form-group">
											<label class="price text-left col-xs-10 col-sm-10 col-md-10 col-lg-4
                                             control-label" for="">Стоимость:</label>
											<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
												<input type="text" class="form-control" name="mr_price" id="mr_price" placeholder="Укажите стоимость"  />
												<!--<span class="required"></span>-->
											</div>
										</div>
										
										<div class="form-group">
											<label class="email text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label" for="">Email:</label>
											<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7">
												<input type="text" class="form-control" name="emailAddress" id="emailAddress" placeholder="Email"  required />
											</div>
										</div>
										
										<div class="form-group">
											<label for="" class="buy text-left col-xs-10 col-sm-10 col-md-10 col-lg-4 control-label">Купить:</label>
											<div class="col-xs-10 col-sm-10 col-md-10 col-lg-7" style="padding-left: 0; width: 64%;">
												<input type="text" placeholder="Ссылка на покупку билетов" class="form-control" name="link" id="link"  />
												<!--<span class="required"></span>-->
											</div>
										</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 top-buffer2">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<textarea name="description" id="eventInputDescription" class="form-control" rows="10" required /></textarea>
								</div>
							</div>
							<div class="top ol-xs-12 col-sm-12 col-md-12 col-lg-12">
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
										<!--<div class="time col-xs-4 col-sm-4 col-md-4 col-lg-4">
											<span class="date control-label">Время окончания</span>
										</div>-->
									</div>
									
									<div class="row event_schedule schedulediv">
										<div class="col1 col-xs-1 col-sm-1 col-md-1 col-lg-1">
											<div class="form-group">
												<select name="scDate[]" class="selectpicker form-control scDate">
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
										<select name="scMonth[]" class="selectpicker form-control scMonth">
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
												<select name="evnt_time_start_hours[]" class="selectpicker form-control time-start-hours">
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
												<select name="evnt_time_start_minutes[]" class="selectpicker form-control time-start-minutes">
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
										    <select name="event_start_time[]" class="selectpicker form-control event_start_time">
                                             <option value="am">AM</option>
                                             <option value="pm">PM</option>
                                            </select>
											</div>
										</div>
									</div>

                                    <a href="javascript:void(0);" id="create_event_schedule" class="add_schedule text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">+ Добавить еще расписание</a>
								</div>
							</div>

							<div class="top col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<p class="title4">Адрес:</p>
									<span class="location"></span>
                                   <div class="mr-address-search-box"> 
                                    <input type="text" name="location" id="pac-input" class="form-control" required  placeholder="Введите адрес мероприятия">
                                     <input type="hidden" name="addressLat" id="addressLat">
                                    <input type="hidden" name="addressLong" id="addressLong">
                                   </div>  
								</div>
							</div>                           
							<div id="event-location-map"></div> 
							<div class="top2 col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<span class="row required"></span>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<input type="text" name="info" id="info" class="form-control" placeholder="Укажите дополнительную информацию о расположении мероприятия если необходимо">
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<p class="title4 text-center">Выберите вариант размещения объявления</p>
									<p class="text">Мы предлагаем несколько вариантов размещения, пожалуйста, выберите, какой Вам подходит лучше всех</p>
									</div>
									
									<div class="col-centered col-xs-6 col-sm-6 col-md-6 col-lg-9 select-ad">
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
										<button type="submit" name="mrNewEventSub" value="Разместить мероприятие" class="btn btn-primary">Разместить мероприятие</button>
									</div>
								</div>
							</form>
                            </div>
							<span class="row required text-required"></span>
							<span class="row required schedule-required"></span>
							<span class="row required address-required"></span>
							<span class="row required info-required"></span>
							<span class="row required select-required"></span>
						</div>
					</div>
				</div>
			</div><!-- end .row -->
					<?php get_sidebar(); ?>
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
        	<p>Ваше мероприятие было успешно добавленно в очередь<br>на модерацию и после проверки вы сможете увидеть его на сайте.</strong></p>
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
	
	/*jQuery("#create_event_schedule").click(function(){
		jQuery('<div class="row event_schedule schedulediv">' + jQuery(".event_schedule").first().html() + '</div>').insertAfter(".event_schedule");
		jQuery( "div.event_schedule" ).first().removeClass( "event_schedule" );
		jQuery( "div.event_schedule" ).find('.bootstrap-select').remove();
		jQuery( "div.event_schedule" ).find('select').selectpicker();
	});*/

	jQuery("#create_event_schedule").click(function(){
				
		jQuery.ajax({
			type:'post',
			url:'<?php echo get_bloginfo('stylesheet_directory'); ?>/schedule-front.php',
			success:function(result) {

				jQuery(result).insertAfter(".event_schedule");
				jQuery( "div.event_schedule" ).first().removeClass( "event_schedule" );
				jQuery( "div.event_schedule" ).find('.bootstrap-select').remove();
				jQuery( "div.event_schedule" ).find('select').selectpicker();
			}
		});
	});
	
	jQuery(".schedule").on('click', '.remove-current-schedule', function(){
		jQuery(this).parent().parent().remove();
		jQuery( "div.schedulediv" ).last().addClass( "event_schedule" );
	});
	
	jQuery.validator.setDefaults({
        ignore: []
    });
	
	var container = jQuery('div.error-container');
	
	// validate the form when it is submitted
	var validator = jQuery("#classified").submit(function() { tinyMCE.triggerSave(); }).validate({
		ignore: "",
		rules: {
			eventTitle: "required",
			eventCategory: "required",
			organiser: "required",
			emailAddress: {
				required: true,
				email: true
			},
			description: "required",
			location: "required"
		},
		messages: {
			eventTitle: "Пожалуйста введите Заголовок",
			eventCategory: "Пожалуйста выберите категорию",
			organiser: "Пожалуйста введите организатора",
			emailAddress: "Пожалуйста введите действительный email адрес",
			description: "Пожалуйста введите описание",
			location: "Пожалуйста введите расположение"
		},
		errorContainer: container,
		errorLabelContainer: jQuery("ol", container),
		wrapper: 'li'
	});
});
</script>

<?php get_footer(); ?>
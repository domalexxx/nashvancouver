<footer>
  <div class="container">
	<div class="column">
		<?php if ( is_active_sidebar( 'footer-sidebar-1' ) ) : ?>
			<?php dynamic_sidebar( 'footer-sidebar-1' ); ?>
		<?php endif; ?>
	</div>
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-2">
	  <?php if ( is_active_sidebar( 'footer-sidebar-2' ) ) : ?>
		<?php dynamic_sidebar( 'footer-sidebar-2' ); ?>
	  <?php endif; ?>
    </div>
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
      <?php if ( is_active_sidebar( 'footer-sidebar-3' ) ) : ?>
		<?php dynamic_sidebar( 'footer-sidebar-3' ); ?>
	  <?php endif; ?>
    </div>
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
      <?php if ( is_active_sidebar( 'footer-sidebar-4' ) ) : ?>
		<?php dynamic_sidebar( 'footer-sidebar-4' ); ?>
	  <?php endif; ?>
    </div>
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
      <?php if ( is_active_sidebar( 'footer-sidebar-5' ) ) : ?>
		<?php dynamic_sidebar( 'footer-sidebar-5' ); ?>
	  <?php endif; ?>
    </div>
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
      <?php if ( is_active_sidebar( 'footer-sidebar-6' ) ) : ?>
		<?php dynamic_sidebar( 'footer-sidebar-6' ); ?>
	  <?php endif; ?>
    </div>
  </div>
  <div class="copyright text-center">2015 Copyright. Nashvancouver.com  All right reserved Create by <a href="#">ClickWebStudio.com</a></div>
</footer>

<div class="modal fade" id="login" role="dialog">
  <div class="login_popup modal-dialog">
    <div class="row modal-content">
	  <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
		<div class="clearfix"></div>
      </div>
	  <div class="modal-body">
		  <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<p class="header text-center">Вход на сайт</p>
			<p class="text-center">Введите свои данные для входа на сайт</p>
			<form name="login-form" id="login-form" method="post">
			  <div class="form-group" id="login-error" style="display:none;">
				  <div class="alert alert-danger">
					<strong><?php _e( 'Error!', 'nashvan' ); ?></strong> <?php _e( 'Email or password Incorrect.', 'nashvan' ); ?>
				  </div>
			  </div>
			  <div class="form-group">
				<label for="email">Email</label>
				<input type="email" class="form-control" id="email" name="email" required />
			  </div>
			  <div class="form-group">
				<label for="password">Пароль</label>
				<a href="#" class="pull-right">Забыли пароль?</a>
				<input type="password" class="form-control" id="password" name="password" required />
			  </div>
			  <div class="form-group">
				<div class="checkbox">
				  <label>
				  <input type="checkbox" name="rememberme" id="rememberme" value="1">
				  Запомнить данные </label>
				</div>
			  </div>
			  <div class="form-group text-center">
				<button type="submit" value="loginuser" name="loginuser" id="loginuser" class="btn btn-danger">Войти</button>
			  </div>
			</form>
		  </div>
		  <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<p class="header text-center">Вход через социальные сети</p>
			<p class="text-center">Выберите социальную сеть через, которую хотели бы войти</p>
			<ul class="social_login">
			  <li>
			  <a class="facebook" href="<?php echo home_url();?>/wp-login.php?loginFacebook=1&redirect=<?php echo home_url();?>" onclick="window.location ='<?php echo home_url();?>/wp-login.php?loginFacebook=1&redirect='+window.location.href; return false;">Вход через Фейсбук <span></span></a></li>
			  <li><a class="twiiter" href="<?php echo home_url();?>/wp-login.php?loginTwitter=1&redirect=<?php echo home_url();?>" onclick="window.location = '<?php echo home_url();?>/wp-login.php?loginTwitter=1&redirect='+window.location.href; return false;">Вход через Твиттер <span></span></a></li>
			  <li>
			   <a class="google" href="<?php echo home_url();?>/wp-login.php?loginGoogle=1&redirect=<?php echo home_url();?>" onclick="window.location ='<?php echo home_url();?>/wp-login.php?loginGoogle=1&redirect='+window.location.href; return false;">Вход через Гугл+ <span></span></a>
			  </li>
			</ul>
		  </div>
		</div>
		<div class="clearfix"></div>
		<div class="modal-footer">
			<p class="text-center"><a title="Registration" href="javascript:void(0);" id="dataregistrationmodal">Зарегистрируйтесь</a> если у вас нет аккаунта</p>
			<div class="clearfix"></div>
		</div>
    </div>
  </div>
</div>
<!-- end #login -->

<div class="modal fade" id="registration" role="dialog">
  <div class="login_popup modal-dialog">
    <div class="row modal-content">
	  <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
		<div class="clearfix"></div>
      </div>
	  <div class="modal-body">
		  <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<p class="header text-center">Регистрация</p>
			<p class="text-center">Введите свои данные<br>
			  для регистрации  на сайте</p>
			<form name="registration-form" id="registration-form" method="post">
			  <div class="form-group" id="register-error" style="display:none;">
				  <div class="alert alert-danger">
					<strong><?php _e( 'Error!', 'nashvan' ); ?></strong> <?php _e( 'Username or Email already exists.', 'nashvan' ); ?>
				  </div>
			  </div>
			  <div class="form-group">
				<label for="username">Username</label>
				<input type="text" class="form-control" id="registration-username" name="registration-username" required />
			  </div>
			  <div class="form-group">
				<label for="email">Email</label>
				<input type="email" class="form-control" id="registration-email" name="registration-email" required />
			  </div>
			  <div class="form-group">
				<label for="password">Пароль</label>
				<input type="password" class="form-control" id="registration-password" name="registration-password" required />
			  </div>
			  <div class="form-group">
				<div class="checkbox">
				  <label>
				  <input type="checkbox" name="registration-rememberme" id="registration-rememberme" value="1">
				  Запомнить данные </label>
				</div>
			  </div>
			  <div class="form-group text-center">
				<button type="submit" name="registerme" id="registerme" value="registerme" class="btn btn-danger">Зарегистрироваться</button>
			  </div>
			</form>
		  </div>
		  <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<p class="header text-center">Вход через социальные сети</p>
			<p class="text-center">Выберите социальную сеть через, которую хотели бы войти</p>
			<ul class="social_login">
			  <li><a class="facebook" href="<?php echo home_url();?>/wp-login.php?loginFacebook=1&redirect=<?php echo home_url();?>" onclick="window.location = '<?php echo home_url();?>/wp-login.php?loginFacebook=1&redirect='+window.location.href; return false;">Вход через Фейсбук <span></span></a></li>
			  <li><a class="twiiter" href="<?php echo home_url();?>/wp-login.php?loginTwitter=1&redirect=<?php echo home_url();?>" onclick="window.location = '<?php echo home_url();?>/wp-login.php?loginTwitter=1&redirect='+window.location.href; return false;">Вход через Твиттер <span></span></a></li>
			  <li><a class="google" href="<?php echo home_url();?>/wp-login.php?loginGoogle=1&redirect=<?php echo home_url();?>" onclick="window.location = '<?php echo home_url();?>/wp-login.php?loginGoogle=1&redirect='+window.location.href; return false;">Вход через Гугл+ <span></span></a></li>
			</ul>
		  </div>
		</div>
		<div class="clearfix"></div>
		<div class="modal-footer">
			<p class="text-center">Используйте <a title="Login" href="javascript:void(0);" id="dataloginmodal">форму входа</a> если вы уже зарегистрованы на сайте</p>
			<div class="clearfix"></div>
		</div>
    </div>
  </div>
</div>
<div class="location-mappopWrap">
 <div class="poplmap-out"><p class="mclose-pop">x</p>
   <div id="mr-loc-popmap"></div>
 </div>
</div>

<?php wp_footer(); ?>
<script type="text/javascript" charset="utf-8">
	jQuery(document).ready(function(){
		
		jQuery("#dataloginmodal").click(function(){
			jQuery('#login').modal('show');
			jQuery('#registration').modal('hide');
		});
		
		jQuery("#dataregistrationmodal").click(function(){
			jQuery('#registration').modal('show');
			jQuery('#login').modal('hide');
		});
	
		/* login */
		jQuery("#login-form").submit(function( event ) {
			
			var login_username = jQuery('#email').val();
			var login_password = jQuery('#password').val();
			var rememberme = jQuery('#rememberme').val();

			jQuery.ajax({
				type:'post',
				url:'<?php echo get_bloginfo('stylesheet_directory'); ?>/user_login.php',
				data: {login_username:login_username,login_password:login_password,rememberme:rememberme},
				success:function(result) {
					if( result == 1 ) {
						window.location.replace("<?php echo get_option('siteurl'); ?>");
					} else {
						jQuery( "#login-error" ).show().fadeOut(5000);
					}
				}
			});
			
			event.preventDefault();
		});
		/* end of login */
		
		/* register */
		jQuery("#registration-form").submit(function( event ) {
			
			var email      = jQuery('#registration-email').val();
			var username   = jQuery('#registration-username').val();
			var password   = jQuery('#registration-password').val();
			var rememberme = jQuery('#registration-rememberme').val();

			jQuery.ajax({
				type:'post',
				url:'<?php echo get_bloginfo('stylesheet_directory'); ?>/user_register.php',
				data: {email:email,username:username,password:password,rememberme:rememberme},
				success:function(result) {
					if( result == 1 ) {
						window.location.replace("<?php echo get_option('siteurl'); ?>");
					} else {
						jQuery( "#register-error" ).show().fadeOut(5000);
					}
				}
			});
			
			event.preventDefault();
		});
		/* end of register */
	});
</script>
<!--<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
<script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text'; 
	$.extend($.validator.messages, {
			required: "Это поле необходимо заполнить.",
			remote: "Пожалуйста, введите правильное значение.",
			email: "Пожалуйста, введите корректный адрес электронной почты.",
			url: "Пожалуйста, введите корректный URL.",
			date: "Пожалуйста, введите корректную дату.",
			dateISO: "Пожалуйста, введите корректную дату в формате ISO.",
			number: "Пожалуйста, введите число.",
			digits: "Пожалуйста, вводите только цифры.",
			creditcard: "Пожалуйста, введите правильный номер кредитной карты.",
			equalTo: "Пожалуйста, введите такое же значение ещё раз.",
			accept: "Пожалуйста, выберите файл с правильным расширением.",
			maxlength: $.validator.format("Пожалуйста, введите не больше {0} символов."),
			minlength: $.validator.format("Пожалуйста, введите не меньше {0} символов."),
			rangelength: $.validator.format("Пожалуйста, введите значение длиной от {0} до {1} символов."),
			range: $.validator.format("Пожалуйста, введите число от {0} до {1}."),
			max: $.validator.format("Пожалуйста, введите число, меньшее или равное {0}."),
			min: $.validator.format("Пожалуйста, введите число, большее или равное {0}.")
	});}(jQuery));
	var $mcj = jQuery.noConflict(true);
</script>-->
<script type='text/javascript' src='<?php echo get_template_directory_uri();?>/js/dropzone.js'></script>
<script type='text/javascript' src='<?php echo get_template_directory_uri();?>/js/owl.carousel.min.js'></script>
<script>
  // Setting auto discover to false immediately after including the script.
  Dropzone.autoDiscover = false;
</script>

<script type="text/javascript">
  jQuery(document).ready(function(){
	jQuery('#file-dropzone').dropzone({
		maxFiles: 5,
    	url: "<?php echo get_bloginfo('stylesheet_directory'); ?>/upload_file.php",
    	maxFilesize: 100,
    	paramName: "uploadfile",
   		maxThumbnailFilesize: 5,
		previewsContainer: '#attachment-preview-images',
    	init: function() {

			this.on('success', function(file, json) {

				var jsonparse = JSON.parse(json);
				if(JSON.parse(json).status = "success") {
					jQuery('.images').append('<li><img width="56px" height="56px" src="' + jsonparse.message + '"><input type="hidden" name="attachid[]" value="' + jsonparse.attachment_id + '"></li>');
				} else {
					alert(jsonparse.message);
				}
			});

			this.on("maxfilesexceeded", function(file){ jQuery('#file-dropzone').addClass('max-files-reached'); this.removeFile(file); });
			//this.on('addedfile', function(file) {});
      		//this.on('drop', function(file) {});
			//this.on("complete", function(file) { this.removeFile(file); });
    	}
    });  
  });
</script>

<script type="text/javascript">
    var map;
	function initMap(aDlat,aDlng)
	{
	   if(aDlat)
	   {aDlat=aDlat;}else{aDlat=62.120327;}		   
	  
	  if(aDlng)
	   {aDlng=aDlng;}else{aDlng=99.25048828125;}		   
   
	    map = new google.maps.Map(document.getElementById('mr-loc-popmap'), {
		center: {lat:parseFloat(aDlat), lng:parseFloat(aDlng)},
		zoom: 4
	  });	  
	    
	    marker = new google.maps.Marker({
			map: map,
			draggable: true,
			animation: google.maps.Animation.DROP,
			position: {lat:parseFloat(aDlat), lng: parseFloat(aDlng)}
		  });
    }

  jQuery(document).ready(function(e) {
       jQuery('.mrclGlists a').click(function(event){		
		event.preventDefault();
		var largeImg = jQuery(this).attr('data-thumb-src');  
		var lgimgContent = '<img src="'+largeImg+'" height="318" widht="318">';
		jQuery('.mr-classified-ad-thumb').html(lgimgContent);
	});
  });
  
//Home Video Slider
 jQuery(document).ready(function() 
 {
      jQuery("#homevgslider").owlCarousel({
		 items : 1,
		 autoPlay: false,
		 margin:10,
		 navigation: true,
		 navigationText: ['<span class="arrow-left"></span>','<span class="arrow-right"></span>'],		 
		 pagination:false		 
	  });
 });
 
//Ad carousel
 jQuery(document).ready(function() 
 {
	  jQuery(".mradv-slider").owlCarousel({
		 items:5,
		 slideSpeed : 200,
		 navigation: true,
		 responsive: true,
		 navigationText: ['<span class="arrow-left"></span>','<span class="arrow-right"></span>'],
		 pagination:false		 
	  });
 });
 
//Google map pop
 jQuery(document).ready(function(){	 
  jQuery(".mclose-pop").click(function(){
   jQuery(".location-mappopWrap").hide();
  });
  
  jQuery(".top-buffer2").on('click', '.mr-gmap-location-pop' ,function(e)
   {
	 jQuery(".location-mappopWrap").show();	   
	 e.preventDefault();	   
     var addLat= jQuery(this).attr('data-add-lat');	
	 var addLong=jQuery(this).attr('data-add-long');	 
	 setTimeout(function(){initMap(addLat,addLong)},100);	 
   });   
   jQuery(".author-personal-page").on('click', '.mr-gmap-location-pop' ,function(e)
   {
	 jQuery(".location-mappopWrap").show();	   
	 e.preventDefault();	   
     var addLat= jQuery(this).attr('data-add-lat');	
	 var addLong=jQuery(this).attr('data-add-long');	 
	 setTimeout(function(){initMap(addLat,addLong)},100);	 
   });
 });
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyClS2-gkqokco8Lt2GRGfDsjNP8eQyv7X8&
libraries=places"></script>
<script type="text/javascript" src="<?php echo bloginfo('template_url');?>/js/masonry.pkgd.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
  jQuery('.mr-classifieds-cat-out').masonry({  
   itemSelector: '.clss-catlis-box',
   columnWidth:265
  });
});
</script>
</body>
</html>
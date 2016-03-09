<?php 
/*
Template Name: Event Template
*/
 ?>
<?php get_header(); ?>
<div id="main-container" class="container classified-inside event-inside">
    <div class="row top-buffer2">
               <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="overflow">
				
				<?php
					if(isset($_SESSION['delete_post_mess'])) {

						echo '<div style="margin-top:18px;" class="alert alert-success fade in">';
							echo '<a title="close" aria-label="close" data-dismiss="alert" class="close" href="#">×</a>';
							echo $_SESSION['delete_post_mess'];
						echo '</div>';
						unset($_SESSION['delete_post_mess']);
					}
				?>
				
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="title pull-left">
                        <span>Последние добавленные мероприятия</span>
                    </div>
					<ul class="links pull-right">
						<li>
							<?php
								if(is_user_logged_in())
								{
									echo '<a href="'.get_permalink(get_page_by_title('Event Add')).'">Добавить новое</a>';
								}
								else
								{                            
									echo '<a data-target="#login" data-toggle="modal" href="javascript:void(0);" class="pull-right">Добавить новое</a>';
								}
							?>
						</li>
					</ul>
                    </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row event mr-more-events-block">
                         <?php
                            $flag = 1;
                            query_posts(array('post_type'=>'event','post_status'=>'publish','posts_per_page'=>'6'));
                                   
                             if(have_posts())
                               {
                                    while(have_posts())
                                    {
                                       the_post();                                          
                                       $eventId = get_the_ID(); // Event id 
                                       $website =  get_post_meta($eventId,"website",true);
                                       $evPhone =  get_post_meta($eventId,"phone",true);
                                       $evLocation =  get_post_meta($eventId,"location",true);
                                       $evPrice =  get_post_meta($eventId,"price",true);  
									   $evlink = get_post_meta($eventId,"link",true);                                                                    
                             ?>
                                    <div class="col-sm-4 col-md-4 col-lg-4 event-block">
                                        <div class="event-block-thumb">
                                            <a href="<?php the_permalink(); ?>">
                                            <?php 
                                                if(has_post_thumbnail())
                                                {
                                                the_post_thumbnail('thumbnail',false,false);
                                                }
                                                else
                                                {
                                                 echo '<img src="'.get_template_directory_uri().'/images/empty-pic.png">'; 
                                                }
                                                ?> 
                                                <div class="opacity">
                                                    <div class="event-info text-center">
                                                        <h3><?php the_title(); ?></h3>
                                                        <?php
                                                            $terms = get_the_terms($eventId, 'event_cat');
                                                            if ($terms) {
                                                                $out = array();
                                                                foreach ($terms as $term) {
                                                                    $out[] = $term->name;
                                                                }
                                                                echo '<p>'.join( ', ', $out ).'</p>';
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="event-block-content">
                                                
                                                <?php
                                                    $countrows = $wpdb->get_row("SELECT count(*) as countevent FROM $wpdb->postmeta WHERE 1 AND post_id = '$eventId' AND meta_key LIKE 'event_date_%'");
                                                    if( intval($countrows->countevent) > 0 ){
                                                            
                                                        for($counter=0; $counter < intval($countrows->countevent); $counter++){
                                                            
                                                            echo '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 date">';
                                                                echo '<span><strong>'. get_post_meta($eventId, 'event_date_'.$counter, true) .'</strong>&nbsp;'. the_russian_time( monthName(get_post_meta($eventId, 'event_month_'.$counter, true)) ) .'&nbsp;'.get_post_meta ($eventId, 'evnt_time_start_hours_0', true ) .':'. get_post_meta ($eventId, 'evnt_time_start_minutes_0', true ) . '&nbsp;'. get_post_meta ($eventId, 'event_start_time_0', true ).'</span>';
                                                            echo '</div>';
                                                        }
                                                    }
                                                ?>
                                                <div class="clearfix"></div>
                                                <p><?php the_excerpt(); ?></p>
                                        </div>
                                         <div class="bg-info event-block-location col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                           <a href="javascript:void(0);" class="location" onclick="myMapFunction('<?php echo $evLocation['address']; ?>')">
                                            <?php
                                                if(!empty($evLocation['address']))
                                                { echo mb_strimwidth($evLocation['address'],0,40,'...').', OR'; }
                                            ?>
                                            </a>
                                        </div>
                                        <div class="event-buybtn-out bg-primary text-center col-xs-12 col-sm-12 
                                        col-md-12 col-lg-12">
                                            <p>Стоимость билетов от <strong>
                                            <?php if($evPrice){ echo "$". $evPrice; }?></strong><p>
                                            <a href="<?php if($evlink){ echo "http://". $evlink; }?>" target="_blank"><button class="btn btn-primary" type="button">Купить</button></a>
                                        </div>
                                    </div>
                             <?php
                                    if($flag % 3 == 0) { echo '<div class="clear"></div>'; }
                                    $flag++;
                                }
                               }
                               wp_reset_query();
                             ?>
                        </div>
                        <?php
                          $eventQryc =query_posts(array('post_type'=>'event','post_status'=>'publish'));
                          if(count($eventQryc)>6)
                          {
                        ?>
                        <div class="clear text-center more no-padding">
                         <a href="#" id="mr-more-events-btn">Показать еще
                           <img src="<?php bloginfo('template_url');?>/images/arrow_down.png"></a>
                        </div>                        
                      <?php } ?>
                    </div>
                    </div>
                </div>
            </div><!-- end .row -->
            <?php get_sidebar(); ?>
        </div><!-- end col-lg-9 -->
    </div>
<script>
function myMapFunction(location) {
    var myWindow = window.open("https://www.google.com/maps?q=" + location, "", "width=800, height=800");
}
</script>
<script type="text/javascript">
 //Code for load more posts
 jQuery(document).ready(function()
 {
    var pageno =3;  
    jQuery("#mr-more-events-btn").click(function(event)
    {      
       event.preventDefault();  
       var catids = jQuery(this).attr('data-cat-ids');
       var cpid = jQuery(this).attr('data-curr-postid');

      jQuery.ajax({
          url: "<?php echo get_template_directory_uri(); ?>/inc/custom-result.php?noofevents="+pageno,
          method: "POST",
          dataType: "html",
          success: function(result)
          {
            if(jQuery(".mr-more-events-block").children('.No-moreposts').length>0)
            {       
                jQuery('#mr-more-events-btn').hide();
            }
            else
            {
             jQuery(result).appendTo(".mr-more-events-block").show(2000);   
            }
          }
     });
      pageno++;  
    });
    
 });
</script>
<?php get_footer(); ?>
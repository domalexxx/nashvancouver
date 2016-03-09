<?php get_header(); ?>

<div id="main-container" class="container">
  <div class="row">
    <div class="left_column col-xs-6 col-sm-6 col-md-6 col-lg-6">
      <p class="title">Последние новости</p>
      <a href="#"><img src="<?php bloginfo('template_url');?>/images/canada_big.jpg" alt="canada"></a>
      <div class="text_block">
        <h1>Заголовок новости</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut 
          labore et dolore magna aliqua. Ut enim ad minim ... </p>
        <ul class="info">
          <li><span class="glyphicon glyphicon-eye-open"></span>1200</li>
          <li>11</li>
        </ul>
      </div>
    </div>
    <div class="right_column col-xs-6 col-sm-6 col-md-6 col-lg-6">
      <div class="row">
        <ul class="links pull-right">
          <li><a href="#">Добавить новую статью</a></li>
          <li><a href="#">Посмотреть все новости</a></li>
        </ul>
        <div class="col col-xs-6 col-sm-6 col-md-6 col-lg-6"> <a href="#"><img src="<?php bloginfo('template_url');?>/images/canada_small.jpg" alt="canada"></a>
          <div class="text_block">
            <h2>Заголовок новости будет идти здесь будет идти здесь будет ...</h2>
            <ul class="info">
              <li><span class="glyphicon glyphicon-eye-open"></span>1200</li>
              <li>11</li>
            </ul>
          </div>
        </div>
        <div class="col col-xs-6 col-sm-6 col-md-6 col-lg-6"> <a href="#"><img src="<?php bloginfo('template_url');?>/images/canada_small.jpg" alt="canada"></a>
          <div class="text_block">
            <h2>Заголовок новости будет идти здесь будет идти здесь будет ...</h2>
            <ul class="info">
              <li><span class="glyphicon glyphicon-eye-open"></span>1200</li>
              <li>11</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col col-xs-6 col-sm-6 col-md-6 col-lg-6"> <a href="#"><img src="<?php bloginfo('template_url');?>/images/canada_small.jpg" alt="canada"></a>
          <div class="text_block">
            <h2>Заголовок новости будет идти здесь будет идти здесь будет ...</h2>
            <ul class="info">
              <li><span class="glyphicon glyphicon-eye-open"></span>1200</li>
              <li>11</li>
            </ul>
          </div>
        </div>
        <div class="col col-xs-6 col-sm-6 col-md-6 col-lg-6"> <a href="#"><img src="<?php bloginfo('template_url');?>/images/canada_small.jpg" alt="canada"></a>
          <div class="text_block">
            <h2>Заголовок новости будет идти здесь будет идти здесь будет ...</h2>
            <ul class="info">
              <li><span class="glyphicon glyphicon-eye-open"></span>1200</li>
              <li>11</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="text-center bg-info block">
  <div class="container">
    <div class="row">
      <p class="title2">Подпишитесь на еженедельную рассылку от нашего сайта</p>
      <p class="title3">Оставайтесь всегда в курсе последних новостей, происходящих в Ванкувере и Канаде</p>
      <!-- Begin MailChimp Signup Form -->
      <div id="mc_embed_signup">
        <form role="form" action="//nashvancouver.us12.list-manage.com/subscribe/post?u=0810fd4893e767495da87d9cd&amp;id=3fed80c408" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate form-inline" target="_blank" novalidate>
          <div id="mc_embed_signup_scroll">
            <div class="mc-field-group form-group">
              <input type="text" value="" name="FNAME" class="form-control" id="mce-FNAME" placeholder="Ваше имя">
            </div>
            <div class="mc-field-group form-group">
              <input type="email" value="" name="EMAIL" class="form-control" id="mce-EMAIL" placeholder="Ваш email адрес">
            </div>
    
            <button type="submit" name="subscribe" id="mc-embedded-subscribe" class="button btn btn-danger">Подписаться</button>
            <div id="mce-responses" class="clear">
              <div class="response" id="mce-error-response" style="display:none"></div>
              <div class="response" id="mce-success-response" style="display:none"></div>
            </div>
    
            <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
            <div style="position: absolute; left: -5000px;">
              <input type="text" name="b_0810fd4893e767495da87d9cd_3fed80c408" tabindex="-1" value="">
            </div>
                 </div>
        </form>
      </div>
      <!--End mc_embed_signup-->
    </div>
  </div>
</div>
<div class="bg-primary block2 text-center">
  <p class="title2">Следите за нами в социальных сетях</p>
<?php echo do_shortcode('[easy-social-like facebook="true" facebook_url="www.facebook.com/nashvancouver" skin="metro" counters=0 align="left"]'); ?> 
</div>
<div class="container">
  <p class="title">Последние объявления</p>
  <div class="row adv">
    <div class="col col-xs-2 col-sm-2 col-md-2 col-lg-2">
      <h3 class="bg-primary"><a href="#">Заголовок объявления будет идти здесь будет ...</a></h3>
      <div class="bg-warning block3"> <a href="#"><img src="http://dummyimage.com/60x60/4d494d/686a82.gif&text=image" alt="image" class="pull-left"></a>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
      </div>
      <div class="block3 bg-primary"><span class="view"><span class="glyphicon glyphicon-eye-open"></span>1200</span><span class="pull-right">2 часа назад</span></div>
    </div>
    <div class="col col-xs-2 col-sm-2 col-md-2 col-lg-2">
      <h3 class="bg-primary"><a href="#">Заголовок объявления будет идти здесь будет ...</a></h3>
      <div class="bg-warning block3"> <a href="#"><img src="http://dummyimage.com/60x60/4d494d/686a82.gif&text=image" alt="image" class="pull-left"></a>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
      </div>
      <div class="block3 bg-primary"><span class="view"><span class="glyphicon glyphicon-eye-open"></span>1200</span><span class="pull-right">2 часа назад</span></div>
    </div>
    <div class="col col-xs-2 col-sm-2 col-md-2 col-lg-2">
      <h3 class="bg-primary"><a href="#">Заголовок объявления будет идти здесь будет ...</a></h3>
      <div class="bg-warning block3"> <a href="#"><img src="http://dummyimage.com/60x60/4d494d/686a82.gif&text=image" alt="image" class="pull-left"></a>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
      </div>
      <div class="block3 bg-primary"><span class="view"><span class="glyphicon glyphicon-eye-open"></span>1200</span><span class="pull-right">2 часа назад</span></div>
    </div>
    <div class="col col-xs-2 col-sm-2 col-md-2 col-lg-2">
      <h3 class="bg-primary"><a href="#">Заголовок объявления будет идти здесь будет ...</a></h3>
      <div class="bg-warning block3"> <a href="#"><img src="http://dummyimage.com/60x60/4d494d/686a82.gif&text=image" alt="image" class="pull-left"></a>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
      </div>
      <div class="block3 bg-primary"><span class="view"><span class="glyphicon glyphicon-eye-open"></span>1200</span><span class="pull-right">2 часа назад</span></div>
    </div>
    <div class="col col-xs-2 col-sm-2 col-md-2 col-lg-2">
      <h3 class="bg-primary"><a href="#">Заголовок объявления будет идти здесь будет ...</a></h3>
      <div class="bg-warning block3"> <a href="#"><img src="http://dummyimage.com/60x60/4d494d/686a82.gif&text=image" alt="image" class="pull-left"></a>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
      </div>
      <div class="block3 bg-primary"><span class="view"><span class="glyphicon glyphicon-eye-open"></span>1200</span><span class="pull-right">2 часа назад</span></div>
    </div>
  </div>
</div>
<div class="container-fluid bg-warning top-buffer">
  <div class="container">
    <div class="row">
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-center"> <span class="title">Русскоязычные бизнесы Ванкувера</span>
        <ul class="links">
          <li><a href="#">Разместить свой бизнес</a></li>
        </ul>
        <div class="banner"><img class="pull-left" src="http://dummyimage.com/500x85/4d494d/686a82.gif&text=BANNER 500X85" alt="BANNER 500X85"></div>
      </div>
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-center"> <span class="title">Ближайшие мероприятия в Ванкувере</span>
        <ul class="links">
          <li><a href="#">Разместить мероприятие</a></li>
        </ul>
        <div class="banner"><img src="http://dummyimage.com/500x85/4d494d/686a82.gif&text=BANNER 500X85" alt="BANNER 500X85"></div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <ul class="catalog">
          <li><a class="auto" href="#">Авто</a></li>
          <li><a class="realestate" href="#">Недвижимость</a></li>
          <li><a class="medicine" href="#">Медицина</a></li>
          <li><a class="health" href="#">Здоровье и спорт</a></li>
          <li><a class="shops" href="#">Магазины и рестораны</a></li>
          <li><a class="school" href="#">Школы и уроки</a></li>
          <li><a class="joy" href="#">Развлечения и досуг</a></li>
          <li><a class="services" href="#">Услуги</a></li>
          <li><a class="computers" href="#">Компьютеры и интернет</a></li>
          <li><a class="others" href="#">Другое</a></li>
        </ul>
        <ul class="links text-center">
          <li><a href="#">Посмотреть весь каталог бизнесов</a></li>
        </ul>
      </div>
      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <ul class="events">
          <li><a href="">
            <div class="event text-center"> <img src="<?php bloginfo('template_url') ?>/images/event.jpg">
              <p>2016</p>
              <p>23</p>
              <p>Января</p>
              <p>5:00 PM</p>
            </div>
            <div>
              <div class="bg-info">
                <h4>“Поздняя любовь”</h4>
                <p>«Поздняя любовь» появилась в афише столичного Театра на Малой Бронной после возвращения в Москву артиста Леонида ос   ... </p>
                <p class="location">Rogers Arena | 400 Southwest Kingston Avenue Portland, OR</p>
              </div>
              <p class="bg-primary"><span>Спектакль</span><span class="button">Купить билеты</span><span class="price">Стоимость от $20</span></p>
            </div>
            </a></li>
          <li><a href="">
            <div class="event text-center"> <img src="<?php bloginfo('template_url') ?>/images/event.jpg">
              <p>2016</p>
              <p>23</p>
              <p>Января</p>
              <p>5:00 PM</p>
            </div>
            <div>
              <div class="bg-info">
                <h4>“Поздняя любовь”</h4>
                <p>«Поздняя любовь» появилась в афише столичного Театра на Малой Бронной после возвращения в Москву артиста Леонида ос   ... </p>
                <p class="location">Rogers Arena | 400 Southwest Kingston Avenue Portland, OR</p>
              </div>
              <p class="bg-primary"><span>Спектакль</span><span class="button">Купить билеты</span><span class="price">Стоимость от $20</span></p>
            </div>
            </a></li>
          <li><a href="">
            <div class="event text-center"> <img src="<?php bloginfo('template_url') ?>/images/event.jpg">
              <p>2016</p>
              <p>23</p>
              <p>Января</p>
              <p>5:00 PM</p>
            </div>
            <div>
              <div class="bg-info">
                <h4>“Поздняя любовь”</h4>
                <p>«Поздняя любовь» появилась в афише столичного Театра на Малой Бронной после возвращения в Москву артиста Леонида ос   ... </p>
                <p class="location">Rogers Arena | 400 Southwest Kingston Avenue Portland, OR</p>
              </div>
              <p class="bg-primary"><span>Спектакль</span><span class="button">Купить билеты</span><span class="price">Стоимость от $20</span></p>
            </div>
            </a></li>
          <li><a href="">
            <div class="event text-center"> <img src="<?php bloginfo('template_url') ?>/images/event.jpg">
              <p>2016</p>
              <p>23</p>
              <p>Января</p>
              <p>5:00 PM</p>
            </div>
            <div>
              <div class="bg-info">
                <h4>“Поздняя любовь”</h4>
                <p>«Поздняя любовь» появилась в афише столичного Театра на Малой Бронной после возвращения в Москву артиста Леонида ос   ... </p>
                <p class="location">Rogers Arena | 400 Southwest Kingston Avenue Portland, OR</p>
              </div>
              <p class="bg-primary"><span>Спектакль</span><span class="button">Купить билеты</span><span class="price">Стоимость от $20</span></p>
            </div>
            </a></li>
        </ul>
        <ul class="links text-center">
          <li><a href="#">Посмотреть все мероприятия</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="container">
  <div class="row">
    <p class="title pull-left">Фотогалерея</p>
    <ul class="links pull-right top-buffer2">
      <li><a href="#">Добавить фото</a></li>
      <li><a href="#">Посмотреть все альбомы</a></li>
    </ul>
  </div>
</div>
<?php get_footer(); ?>
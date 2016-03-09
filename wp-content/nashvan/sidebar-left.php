<?php
/**
 * The sidebar containing the main widget area
 *
 * If no active widgets are in the sidebar, hide it completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

<div class="left_menu col-xs-2 col-sm-2 col-md-2 col-lg-3">
  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingOne">
        <h4 class="panel-title">
			<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> Новости </a>
		</h4>
      </div>
      <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">
          <ul>
			<?php
				$categorys = get_terms( 'category', array( 'orderby' => 'id', 'order' => 'ASC', 'hide_empty' => true, 'fields' => 'all', 'pad_counts' => true ) );
				foreach($categorys as $category) {
					echo '<li><a href="'.get_term_link( $category ).'">'.$category->name.'</a></li>';
				}
			?>
            <li><a href="<?php echo get_permalink( get_page_by_title("Добавить новую статью") ); ?>">Добавить новую статью</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingTwo">
        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"> Фотогалерея </a> </h4>
      </div>
      <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
        <div class="panel-body">
          <ul>
            <li><a href="#">Самые популярные фото</a></li>
            <li><a href="#">Последние добавленные</a></li>
            <li><a href="#">Посмотреть все альбомы</a></li>
            <li><a href="#">Добавить новые фото</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingThree">
        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree"> Видеогалерея </a> </h4>
      </div>
      <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
        <div class="panel-body">
          <ul>
            <li><a href="#">Самые популярные видео</a></li>
            <li><a href="#">Последние добавленные</a></li>
            <li><a href="#">Посмотреть все видео</a></li>
            <li><a href="#">Добавить новые видео</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingFour">
        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour"> Доска обьявлений </a> </h4>
      </div>
      <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
        <div class="panel-body">
          <ul>
            <li><a href="#">Последние объявления</a></li>
            <li><a href="#">Добавить новое</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingFive">
        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive"> Бизнес справочник </a> </h4>
      </div>
      <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
        <div class="panel-body">
          <ul>
            <li><a href="#">Последние добавленные</a></li>
            <li><a href="#">Добавить свой бизнес</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingSix">
        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix"> Афиша </a> </h4>
      </div>
      <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
        <div class="panel-body">
          <ul>
            <li><a href="#">Ближайшие события</a></li>
            <li><a href="#">Добавить новое событие</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingSeven">
        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven"> Про иммиграцию </a> </h4>
      </div>
      <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
        <div class="panel-body">
          <ul>
            <li><a href="#">Категория 1</a></li>
            <li><a href="#">Категория 1</a></li>
            <li><a href="#">Добавить новую статью</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- end .panel-group -->
</div>
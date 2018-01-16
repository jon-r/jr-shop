<?php
$carouselList = jrQ_carousel();
$carouselCount = count($carouselList);
?>

<article class="flex-container" >

  <section class="flex-1 tile-outer carousel hide-mobile" ng-controller="carouselCtrl" >
    <ul id="js-carousel-main" class="carousel-container">

      <?php for ($i = 0; $i < $carouselCount; $i++) :
        $slide = jr_magicRoundabout($carouselList[$i]);
      ?>

      <li carousel-slide class="slide<?php echo $i == 0 ? ' is-active' : null ?>" >
        <a href="<?php echo $slide['weblink']; ?>">
          <img class="framed" src="<?php echo $slide['image']; ?>" alt="<?php echo $slide['title']; ?>" >
          <?php echo jr_carouselStr($slide, 'title', 'h2', 'slider-title'); ?>

          <div class="slider-text <?php echo $slide['textPos']; ?>">
            <?php echo jr_carouselStr($slide, 'text1', 'span', null); ?>
            <?php echo jr_carouselStr($slide, 'text2', 'span', null); ?>
            <?php echo jr_carouselStr($slide, 'text3', 'span', null); ?>
          </div>

          <?php echo jr_carouselStr($slide, 'link', 'h3', 'slider-link'); ?>
        </a>
      </li>

      <?php endfor ?>
    </ul>

    <ul id="js-carousel-blips" class="carousel-blips" >
      <?php for ($i = 0; $i < $carouselCount; $i++) :
        $title = $carouselList[$i]['Slide_Tab'];
      ?>
        <li class="blip <?php echo $i == 0 ? 'active' : null ?>" ></li>
      <?php endfor ?>
    </ul>
  </section>

</article>

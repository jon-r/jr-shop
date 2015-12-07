<?php
$carouselList = jrQ_carousel();
$carouselCount = count($carouselList);
?>

<article class="flex-container" >

  <section class="flex-1 tile-outer carousel"
           ng-controller="carouselCtrl" ng-mouseover="slidePause=true;" ng-mouseleave="slidePause=false;">
    <ul id="js-carousel-main" class="carousel-container" >

      <?php for ($i = 0; $i < $carouselCount; $i++) :
        $slide = jr_magicRoundabout($carouselList[$i]);
      ?>

      <li class="slide" ng-class="sl<?php echo $i ?>" >
        <!--<a href="<?php echo $slide['link']; ?>">-->
          <img class="framed" class="" src="<?php echo $slide['image']; ?>" alt="<?php echo $slide['title']; ?>" >
          <h2 class="slider-title <?php echo $slide['titlePos'].' '.$slide['titleCol']; ?>"><?php echo $slide['title']; ?></h2>

          <div class="slider-text <?php echo $slide['textPos']; ?>">
            <span class="<?php echo $slide['style1']; ?>"><?php echo $slide['text1']; ?></span>
            <span class="<?php echo $slide['style2']; ?>"><?php echo $slide['text2']; ?></span>
            <span class="<?php echo $slide['style3']; ?>"><?php echo $slide['text3']; ?></span>
          </div>

          <h3 class="slider-link <?php echo $slide['linkPos'].' '.$slide['linkCol']; ?>">Click Here</h3>
        <!--</a>-->
      </li>

      <?php endfor ?>
    </ul>

    <ul class="carousel-blips" >
      <?php for ($i = 0; $i < $carouselCount; $i++) :
        $title = $carouselList[$i]['Slide_Tab'];
      ?>
        <li class="blip" ng-class="sl<?php echo $i ?>" ng-click="go(<?php echo $i ?>);" ></li>
      <?php endfor ?>
    </ul>
  </section>

</article>

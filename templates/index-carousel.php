<?php
$carouselList = jrQ_carousel();
$carouselCount = count($carouselList);
?>

<div class="nav-right carousel" >
  <ul id="js-carousel-main" class="carousel-container bar-left">

    <?php for ($i = 0; $i < $carouselCount; $i++) :
      $slide = jr_magicRoundabout($carouselList[$i]);
    ?>

    <li class="slide<?php echo $i == 0 ? ' is-active' : null ?>" >
      <a href="<?php echo $slide[link]; ?>">

        <img class="" src="<?php echo $slide[image]; ?>" alt="<?php echo $slide[title]; ?>" >

        <h2 class="slider-title <?php echo $slide[titlePos]; ?>"><?php echo $slide[title]; ?></h2>

        <div class="slider-text <?php echo $slide[textPos]; ?>">
          <span class="<?php echo $slide[style1]; ?>"><?php echo $slide[text1]; ?></span>
          <span class="<?php echo $slide[style2]; ?>"><?php echo $slide[text2]; ?></span>
          <span class="<?php echo $slide[style3]; ?>"><?php echo $slide[text3]; ?></span>
        </div>

        <h3 class="slider-link <?php echo $slide[linkPos]; ?>">Click Here</h3>
      </a>
    </li>

    <?php endfor ?>

  </ul>
  <ul id="js-carousel-tabs" class="carousel-tabs" >
    <?php for ($i = 0; $i < $carouselCount; $i++) :
      $title = $carouselList[$i][Title];
    ?>
    <li class="tab <?php echo $i == 0 ? 'active' : null ?>" ><?php echo $title ?></li>
    <?php endfor ?>
  </ul>

<!--
  <ul id="js-blipParent" class="carousel-blips">
    <?php// for ($i = 0; $i < count($carouselList); $i++) : ?>
    <li class="blipper<?//php echo $i == 0 ? ' active' : null ?>" data-blipNum="<?php// echo $i ?>" ></li>
    <?php //endfor ?>
  </ul>
-->
</div>

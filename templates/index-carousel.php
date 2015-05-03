<?php
$carouselList = jrx_query_carousel();
?>


<ul id="js-carouselMain" class="carousel-container">

  <?php for ($i = 0; $i < count($carouselList); $i++) :
    $slide = magic_roundabout($carouselList[$i]);
  ?>

  <li class="slide<?php echo $i == 0 ? ' is-active' : null ?>" data-slideNum="<?php echo $i ?>" >
    <a href="<?php echo $slide[link]; ?>">

      <img class="slide-image" src="<?php echo $slide[image]; ?>" alt="<?php echo $slide[title]; ?>" >

      <h2 class="slide-title <?php echo $slide[titlePos]; ?>"><?php echo $slide[title]; ?></h2>

      <div class="slide-text <?php echo $slide[textPos]; ?>">
        <span class="<?php echo $slide[style1]; ?>"><?php echo $slide[text1]; ?></span>
        <span class="<?php echo $slide[style2]; ?>"><?php echo $slide[text2]; ?></span>
        <span class="<?php echo $slide[style3]; ?>"><?php echo $slide[text3]; ?></span>
      </div>

      <h3 class="slide-link <?php echo $slide[linkPos]; ?>">Click Here</h3>
    </a>
  </li>

  <?php endfor ?>

</ul>

<ul id="js-blipParent" class="carousel-blips">
  <?php for ($i = 0; $i < count($carouselList); $i++) : ?>
  <li class="blipper<?php echo $i == 0 ? ' active' : null ?>" data-blipNum="<?php echo $i ?>" ></li>
  <?php endfor ?>
</ul>

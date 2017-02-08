<section class="flex-1 tile-outer carousel hide-mobile" >
  <ul class="carousel-container">

    <li carousel-slide class="slide is-active" >
      <a href="<?php echo $slide['link']; ?>">
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
  </ul>

</section>

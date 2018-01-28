<section class="flex-1 tile-outer carousel hide-mobile" >
  <ul class="carousel-container">

    <li carousel-slide class="slide is-active" >
      <a href="<?php echo $slide['link']; ?>">
        <img class="framed" src="<?php echo $slide['image']; ?>" alt="<?php echo $slide['title']; ?>" >
        <h2 class="slider-title <?php echo $slide['titlePos'].' '.$slide['titleCol']; ?>"><?php echo $slide['title']; ?></h2>

        <div class="slider-text <?php echo $slide['textPos']; ?>">
          <span class="<?php echo $slide['style1']; ?>"><?php echo $slide['text1']; ?></span>
          <span class="<?php echo $slide['style2']; ?>"><?php echo $slide['text2']; ?></span>
          <span class="<?php echo $slide['style3']; ?>"><?php echo $slide['text3']; ?></span>
        </div>

        <h3 class="slider-link <?php echo $slide['linkPos'].' '.$slide['linkCol']; ?>">Click Here</h3>
      </a>
    </li>
  </ul>

</section>
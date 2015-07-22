
/*region Caramel */
/*
 * Caramel (http://caramel.ga)
 * Copyright 2015, All Rights Reserved
 * GPL v2 License
 */
 $(document).ready(function(){var n=$(".nav");n.on("click",".collapse",function(){$(this).parents("ul").toggleClass("open")}),n.on("click",".dropdown",function(n){n.preventDefault(),$(this).parents("li").find("> ul").toggleClass("open")}),$(".dismiss").click(function(){$(this).closest("#note").fadeOut(500,function(){$(this).remove()})}),$(window).resize()});
/*endregion*/

/* ----------- cleanup 1 ------------------------------------------------------------ */
//first finds and lists the number of 'obsolete' images
var $btnFindDeadImg = $('#js-oldImageFind'),
    $btnDelDeadImg = $('#js-oldImageDelete'),
    $outputGallery = $('#js-output-gallery');

$btnFindDeadImg.click(function () {
  $outputGallery.html('');

  $.get(fileSrc.admin, {
    keyword: 'gallery',
    action: "jra_deadimgstats"
  }, listDeadImg)
});

function listDeadImg(data) {
  var results = $.parseJSON(data);
  var output = '<b>Removable Images: </b>' + results.count +
    '<br><b>Space To Save: </b>' + results.size +
    '<p>(To override this, mark items as \'force show\' on the database)</p>';
  $outputGallery.append(output);
  $btnDelDeadImg.show();
}

$btnDelDeadImg.click(function() {
  $outputGallery.html('');

  $.get(fileSrc.admin, {
    keyword: 'gallery',
    action: "jra_deadimgdel"
  }, confirmDeadImg);
});

function confirmDeadImg(data) {
  var results = $.parseJSON(data);
  var output = '<p>' + results + ' Images Deleted</p>'
  $outputGallery.append(output);
}
/* ----------- cleanup 1 ------------------------------------------------------------ */
//used to remove old thumbnail/tile images (targets specific). to hopefully cleanup any incorrect.
//in theory any other incorrect are mispellings
var $btnFindSpecific = $('#js-targetted-removal'),
    $outputSpecific = $('#js-output-specific'),
    $inputRef = $('#js-specific-ref');

$btnFindSpecific.click(function() {
  var ref = $inputRef.val();

  $outputSpecific.html('');
  $.get(fileSrc.admin, {
    reference: ref,
    action: "jra_specificimg"
  }, listSpecific)
});

function listSpecific(data) {
  var output = null,
      results = $.parseJSON(data);

  if (results) {
    var firstImg = results.first;
    var allImg = results.all;

    output = '<p class="box col-12">Layout reset. The following images will be on the site.<br>' +
        'If wrong, make sure all images are correctly named and organised before hitting the database \'sync\' button </p>' +
        '<div class="row box col-4"><b>First Image</b><br>' +
        '<img class="box col-12" src="' + firstImg + '" ></div>' +
        '<div class="row box col-8"><b>All Images (includes first intentionally)</b><br>';
    for (i = 0; i < allImg.length; i++) {
      var str = '<img class="box col-3" src="' + allImg[i] + '" >'
      output = output.concat(str);
    }
    output = output.concat('</div>');
  } else {
    output = '<p>Images Not Found. Please check the reference is accurate.</p>'
  }
  $outputSpecific.append(output);
}

/* ----------- cleanup 2 ------------------------------------------------------------ */
//clearing caches
var $btnClearCache = $('#js-clearCache'),
    $outputCache = $('#js-output-cache');


$btnClearCache.click(function() {
  $outputCache.html( '' );
  $.get(fileSrc.admin, {
    action: "jra_clearcache"
  }, listSpecific);
});

function listSpecific(data) {
  var results = $.parseJSON(data);

  var output = results.count + 'cached items Removed';

  $outputCache.html( output );
}

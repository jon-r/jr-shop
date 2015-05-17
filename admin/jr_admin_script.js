
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
    $outputGallery = $('#js-output-gallery');

$btnFindDeadImg.click(function() {

  $outputGallery.html('');

  $.get(fileSrc.ajaxAdmin, {
    keyword: 'gallery',
    action: "jra_deadimgstats"
  }, listDeadImg)
});

function listDeadImg(data) {
  var results = $.parseJSON(data);
  output = '<b>Removable Images:</b>' + results.count +
    '<br><b>Space To Save:</b>' + results.size +
    '<br><input type="submit" class="btn error" value="Delete Images" >' +
    '<p>(To overwrite this, mark items as \'force show\' on the database)</p>';
  $outputGallery.append(output);
}

function deleteDeadImg() {
  //placeholder
}

/* ----------- cleanup 1 ------------------------------------------------------------ */
//used to remove old thumbnail/tile images (targets specific). to hopefully cleanup any incorrect.
//in theory any other incorrect are mispellings

var $btnFindSpecific = $('#js-targetted-removal'),
    $outputSpecific = $('#js-output-specific'),
    $inputRef = $('#js-specific-ref');

$btnFindSpecific.click(function() {
  $outputSpecific.html('');

  var ref = $inputRef.val();

  $.get(fileSrc.ajaxAdmin, {
    reference: ref,
    action: "jra_specificimg"
  }, listSpecific)
});

function listSpecific(data) {
  var output = null;
  var results = $.parseJSON(data);
  if (results) {
    var firstImg = results.first;
    var allImg = results.all;

    output = '<br><input type="submit" class="btn error box col-12" value="Reload Specific Images" ><br>' +
        'if these images are correct, the reload button should update the website. <br>' +
        'If not, make sure all images are correctly named and organised before hitting the database \'sync\' button <br>' +
        '<b>First Image</b><br>' +
        '<img class="box col-12" src="' + firstImg + '" >' +
        '<b>All Images (includes first intentionally)</b><br>';

    for (i = 0; i < allImg.length; i++) {
      var str = '<img class="box col-4" src="' + allImg[i] + '" >'
      output = output.concat(str);
    }

  } else {
    output = '<p>Images Not Found. Double Check whether your reference is accurate.</p>'
  }
  $outputSpecific.append(output);
}

function RefreshSpecificImg() {
  //placeholder
}

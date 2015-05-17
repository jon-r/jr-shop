<main class="row">
  <h1>Admin for JR-Shop</h1>
  <section class="box col-4">

    <article class="panel success">
      <?php $imgDir = getDirectorySize("../images/"); ?>


      <div class="panel-head">FIle Cleanup</div>
      <div class="panel-body">
        <b>Image Count: </b><?php echo $imgDir['count'] ?> files.<br>
        <b>Total Image Filesize: </b><?php echo sizeFormat($imgDir['size']) ?><br>
        <hr>
        <p>You can also manually delete the files in the images folder [here] and then re-sync</p>
        <hr>
        <input type="submit" id="js-oldImageFind" class="btn success" value="Check for removable images" >
        <div id="js-output-gallery" class="has-loader" >
          <p>Be sure to clean up any unnecessary files (mostly long sold products) to save space.</p>
        </div>

      </div>
    </article>

  </section>

  <section class="box col-8">
    <article class="panel info">
      <div class="panel-head">Contact Details</div>
      <div class="panel-body">coming soon</div>
    </article>

    <article class="panel success image-fix">
      <div class="panel-head">Image Fix</div>
      <div class="panel-body">
        <input type="submit" id="js-targetted-removal" class="btn success" value="Manual Update" ><br>
          <b>Item Ref:</b> <input type="text" id="js-specific-ref" placeholder="RHC(s)###" >
        <div id="js-output-specific" class="has-loader row" >
          <p>Use this to force update any misbehaving images. If this doesnt work, make sure all images are correctly named and organised before hitting the database 'sync' button</p>
        </div>
      </div>
    </article>


    <article class="panel warn">
      <div class="panel-head">Cache Reset</div>
      <div class="panel-body">coming soon</div>
    </article>

    <article class="panel error">
      <div class="panel-head">404 log</div>
      <div class="panel-body">coming soon</div>
    </article>
  </section>

  <section class="box col-12">
    <article class="panel">
      <div class="panel-head">database tables</div>
      <div class="panel-body">coming soon</div>
    </article>
  </section>



</main>

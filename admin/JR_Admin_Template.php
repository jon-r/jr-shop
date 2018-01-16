<main class="row">

  <h1>Admin for JR-Shop</h1>
  <section class="box col-4">

    <article class="panel success panel-images">
      <?php $imgDir = getDirectorySize("../images/"); ?>
      <div class="panel-head">FIle Cleanup</div>
      <div class="panel-body">
        <b>Image Count: </b><?php echo $imgDir['count'] ?> files.<br>
        <b>Total Image Filesize: </b><?php echo sizeFormat($imgDir['size']) ?><br>
        <hr>
        <input type="submit" id="js-oldImageFind" class="btn success" value="Check for removable images" >
        <div id="js-output-gallery" class="has-loader" >
          <p>Be sure to clean up any unnecessary files (mostly sold products) to save space. </p>
        </div>
        <input id="js-oldImageDelete" type="submit" class="btn error" value="Delete Images" >
      </div>
    </article>

  </section>

  <section class="box col-8">

    <article class="panel info panel-cache">
      <?php $listPageCache = jrA_getHTMLCache() ?>
      <?php $listTransients = jrA_getTransients(); ?>
      <div class="panel-head">Cache Reset</div>
      <div class="panel-body row">
        <div class="box col-6">
          <b>Database Cache: </b><?php echo $listTransients ?> querys cached.
          <hr>
          <b>Page Cache: </b><?php echo $listPageCache ?> page elements cached.

        </div>
        <div class="box col-6">
          <input id="js-clearCache" type="submit" class="btn info" value="Clear Cache" >

          <div id="js-output-cache" class="has-loader">
          <p>Use this to update any change to page options, carousel, categories. Basically everything that isnt on the products page is cached.</p>
          </div>
        </div>
      </div>
    </article>

    <article class="panel error">
      <div class="panel-head">404 log</div>
      <div class="panel-body">coming soon</div>
    </article>

  </section>

  <section class="box col-12">

    <article class="panel warn panel-image-fix">
      <div class="panel-head">Image Fix</div>
      <div class="panel-body">
        <b>Item Ref:</b>
        <input type="text" id="js-specific-ref" placeholder="RHC### / RHCS###">
        <input type="submit" id="js-targetted-removal" class="btn warn" value="Manual Update">
        <br>
        <div id="js-output-specific" class="has-loader">
          <p>Use this to force update any misbehaving images. If this doesnt work, make sure all images are correctly named and organised before hitting the database 'sync' button</p>
        </div>
      </div>
    </article>

    <article class="panel">
      <div class="panel-head">database tables</div>
      <div class="panel-body">coming soon
      </div>
    </article>

  </section>

</main>

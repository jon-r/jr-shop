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
        <p>Be sure to clean up any unnecessary files (mostly long sold products) to save space.</p>
        <p>You can also manually delete the files in the images folder [here] and then re-sync</p>
        <hr>
        <?php $deadImgs = jrA_DeadImageSpecs ("gallery") ?>
        <b>Removable Images:</b> <?php echo $deadImgs['Count']; ?><br>
        <b>Space To Save:</b> <?php echo $deadImgs['Size']; ?><br>

        <input type="submit" class="btn error" value="Delete Images" >
        <p>(Only Deletes files online. To overwrite this, mark items as 'force show' on the database )</p>



      </div>
    </article>

  </section>

  <section class="box col-6">
    <article class="panel info">
      <div class="panel-head">Contact Details</div>
      <div class="panel-body">coming soon</div>
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

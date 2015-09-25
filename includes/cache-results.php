
<!doctype html>

<html >
  <?php if (isset($_GET['refs']) || isset($_GET['ssrefs'])) : ?>
  <?php $lists = jr_clearCache() ?>

  <style>
    .good {
      color: #2a8338
    }
    .bad {
      color: #aa0909
    }
  </style>

  <body>
    <h1>RHC Cache Cleanup</h1>

    <h3>Cache Files Removed:</h3>
    <ul class="good">
      <?php echo $lists['success']; ?>
    </ul>
    <h3>Cache Files Errors:</h3>
    <ul class="bad" >
      <?php echo $lists['fail']; ?>
    </ul>
  </body>
  <?php else :
    $blah = [
      'refs' => [123, 456, 789],
      'ssrefs' => [987, 654, 321]
    ];
  echo http_build_query($blah);
  ?>
  404 not found

  <?php endif ?>
</html>

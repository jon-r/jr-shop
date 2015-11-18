<?php
/*
generates a sitemap xml page, for seo and stuff.
currently called on a small cache clear
*/
class sitemap {

  public $urlset;
  public $test;

  public function build() {
    global $wpdb;

    $this->urlset = '<?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" >';

    $this->wpdb = $wpdb;
    //builds from WP pages first
    $this->getWP();
    //next categories
    $this->getCats();
    //brands
    $this->getBrands();
    //items
    $this->getItems();
    $this->getItemsSS();

    $this->urlset .= '</urlset>';


    $mapFile = fopen('../sitemap.xml', 'w');

    fwrite($mapFile, compress_page($this->urlset));
    fclose($mapFile);
  }

  private function getWP() {
    $pageList = get_pages(['exclude'=>'16,21,24,30']);//array of "shop" pages

    foreach($pageList as $pg) {
      $url = get_permalink($pg->ID);
      $mod = substr($pg->post_modified,0,10);

      $this->urlset .= $this->printXML(['url'=>$url,'mod'=>$mod]);
    }
  }

  private function getCats() {
    $pageList = $this->wpdb->get_results("SELECT `Category_ID`, `Name` FROM `rhc_categories` WHERE `ShowMe` = 1");

    foreach($pageList as $pg) {
      $url = home_url('/products/category/'.$pg->Category_ID.'/'.sanitize_title($pg->Name));
      $freq = 'weekly';
      $this->urlset .= $this->printXML(['url'=>$url,'freq'=>$freq]);
    }
  }

  private function getBrands() {
    $pageList = $this->wpdb->get_col("SELECT DISTINCT `Brand` FROM `networked db` WHERE `Quantity` > 0 AND `LiveonRHC` = 1");

    foreach($pageList as $brand) {
      if ($brand != '0' && $brand != null) {
        $url = home_url('/products/brand/'.sanitize_title($brand));
        $freq = 'weekly';
        $this->urlset .= $this->printXML(['url'=>$url,'freq'=>$freq]);
      }
    }
  }

  private function getItems() {
    $pageList = $this->wpdb->get_results("SELECT `RHC`, `ProductName`, `DateLive` FROM `networked db` WHERE (`LiveonRHC` = 1 AND `Quantity` > 0)");
    foreach($pageList as $pg) {
      $url = home_url('rhc/'.$pg->RHC.'/'.sanitize_title($pg->ProductName));
      $mod = str_replace('2001','2015', $pg->DateLive);
      $prio = '0.8';
      $extra = $this->setImg('RHC'.$pg->RHC,$pg->ProductName);
      $this->urlset .= $this->printXML(['url'=>$url,'mod'=>$mod,'prio'=>$prio,'extra'=>$extra]);
    }
  }

  private function getItemsSS() {
    $pageList = $this->wpdb->get_results("SELECT `RHCs`, `ProductName`, `DateLive` FROM `benchessinksdb` WHERE (`LiveonRHC` = 1 AND `Quantity` > 0)");
    foreach($pageList as $pg) {
      $url = home_url('rhcs/'.$pg->RHCs.'/'.sanitize_title($pg->ProductName));
      $mod = str_replace('2001','2015', $pg->DateLive);
      $prio = '0.8';
      $extra = $this->setImg('RHCs'.$pg->RHCs,$pg->ProductName);
      $this->urlset .= $this->printXML(['url'=>$url,'mod'=>$mod,'prio'=>$prio,'extra'=>$extra]);
    }
  }

  private function setImg($ref,$name) {
    $src = "images/gallery/$ref.jpg";
    if (file_exists(ABSPATH.$src)) {
      $loc = site_url($src);
      ob_start() ?>
        <image:image>
          <image:loc><?php echo $loc ;?></image:loc>
          <image:caption><?php echo $src ;?></image:caption>
        </image:image>
      <?php $out = ob_get_clean();
      return $out;
    }

  }


  private function printXML( $args = array() ) {
    $defaults = [
      'url'  => home_url(),
      'mod'  => date("Y-m-d"),
      'freq' => 'monthly',
      'prio' => 0.5,
      'extra' => ''
    ];

    $e = wp_parse_args($args, $defaults);

    ob_start() ?>
<url>
  <loc><?php echo $e['url'] ?></loc>
  <lastmod><?php echo $e['mod'] ?></lastmod>
  <changefreq><?php echo $e['freq'] ?></changefreq>
  <?php echo $e['extra'] ?>
  <priority><?php echo $e['prio'] ?></priority>
</url>
<?php $out = ob_get_clean();
    return $out;
  }

}

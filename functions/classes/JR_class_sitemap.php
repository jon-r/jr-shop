<?php
/*
generates a sitemap xml page, for seo and stuff.
currently called on a small cache clear
*/
class sitemap {

  public $outTEMP;

  public function build() {
    global $wpdb;
    $this->wpdb = $wpdb;
    //builds from WP pages first
    $this->getWP();
    //next categories

    //brands

    //items


  }

  private function getWP() {
    $pageList = wp_list_pages(['echo'=>false,'exclude'=>'24,16,21,30'],'title_li'=>false);
      //$this->wpdb->get_results("SELECT `post_name` FROM `wp_posts` WHERE `post_status` LIKE 'publish' AND `post_type` LIKE 'page'");
    $this->outTEMP =  $pageList;
  }
}

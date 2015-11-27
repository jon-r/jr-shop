<?php


/* SECURITY:
  > Since the Back-End is Based on in house PCs, theres a fairly limited amount that the customer can access.
  > no personal details are to be keeped on internet databases
  > Light security whitlelist sanitises the input to prevent injection just in case
/*--------------------------------------------------------------------------------------*/
//idea of this function is to act as a wall between input and output.
//the user can input whatever they want, but only strings on this function are used in sql queries


class pageValidate {

  private $params;

  public $title;  //title of the page
  public $ref;    //user friendly reference (ie forms). if unused defaults to $this->title
  public $unique;  //unique URL friendly reference (ie cache). if unused the "unique" cache is ingored (ie search results)
  public $args; //filters for the wpdb queries;
  public $url;
  public $desc; //

  private function getParams() {
    $this->getUrl();
    $slashedParams = str_replace(home_url(), '', $this->url);
    $this->params = explode('/',strtolower($slashedParams));
  }

  private function getUrl() {
    $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] : 'https://'.$_SERVER["SERVER_NAME"];
    $url .= $_SERVER["REQUEST_URI"];
    $this->url = $url;
  }

  private function getCategory($id) {
    global $wpdb;
    $query = "SELECT `Name`, `CategoryDescription`, `Is_RHCs` FROM `rhc_categories` WHERE `Category_ID` LIKE '$id'";
    return $wpdb->get_row($query);
  }

  private function getItem() {
    global $wpdb, $itemSoldDuration;
    $p = $this->params;
    if ($p[1] == 'rhc') {
      $ref = 'RHC'; $tbl = 'networked db';
    } else {
      $ref = 'RHCs'; $tbl = 'benchessinksdb';
    }
    $num = $p[2];

    $queryStr = "SELECT `ProductName`,`Category` FROM `$tbl` WHERE `$ref` = %s AND `LiveonRHC` = 1 AND ((`Quantity` > 0) OR ( `Quantity` = 0 AND `DateSold` BETWEEN CURDATE() - INTERVAL $itemSoldDuration DAY AND CURDATE()))";
    $out = $wpdb->get_row($wpdb->prepare($queryStr, $num));
    return($out);
  }

  public function init() {
    $this->getParams();
    $p = $this->params;

    switch ($p[1]) {
      case '':
        $this->ref = ''; //blank to leave the form blank.
        break;
      case 'categories':
      case 'products':
        $page = isset($_GET['pg']) ? '_pg'.$_GET['pg'] : null;
        switch ($p[2]) {
          case 'category':
            $category = $this->getCategory($p[3]);
            if ($category) {
              $this->title = $category->Name;
              $this->args = ['category'=>$category->Name,'ss'=>$category->Is_RHCs];
              $this->unique = 'category-'.$p[3];
              $this->desc = $category->CategoryDescription != 0 ? jr_format($category->CategoryDescription) : null;
            } else {
              $this->title = 'Not Found';
            }
            break;
          case 'search-results':
            $search = $_GET['q'];
            $urlSearch = str_replace(' ','-',$search);
            $this->title = "Search results for $search";
            $this->args = ['search'=>$search,'ss'=>$_GET['ss']];
            $this->unique = 'category-search-'.$urlSearch.$page;
            $this->desc = jr_format(get_option('jr_shop_page_text_search'));
            break;
          case 'arrivals':
            $this->title = "Just In";
            $this->args = ['arrivals'=>true];
            $this->unique = 'category-arrivals'.$page;
            $this->desc = jr_format(get_option('jr_shop_page_text_arrivals'));
            break;
          case 'sold':
            $this->title = "Just In";
            $this->args = ['sold'=>true];
            $this->unique = 'category-sold'.$page;
            $this->desc = jr_format(get_option('jr_shop_page_text_sold'));
            break;
          case 'special-offers':
            $this->title = "Sales";
            $this->args = ['sale'=>true];
            $this->unique = 'category-sale'.$page;
            $this->desc = jr_format(get_option('jr_shop_page_text_sale'));
            break;
          case 'brand':
            $brand = jr_urlToBrand($p[3]);
            $this->title = "Products by $brand";
            $this->args = ['brand'=>$brand];
            $this->unique = 'category-brand-'.$brand.$page;
            break;
          default:
            $this->title = "All Products";
            $this->args = [];
            $this->unique = 'category-all'.$page;
            $this->desc = jr_format(get_option('jr_shop_page_text_all'));
        }
        $this->ref = $this->title;
        break;
      case 'brands' :
        $this->title = "Shop by Brand";
        $this->unique = 'category-brands-all';
        break;
      case 'rhcs' :
      case 'rhc' :
        $item = $this->getItem();
        if ($item) {
          $this->title = $item->ProductName;
          $this->args = ['ref'=>$p[1],'id'=>$p[2],'category'=>$item->Category];
          $this->ref = $p[1].$p[2].' - '.$item->ProductName;
          $this->unique = 'item-'.$p[1].$p[2];
        } else {
          $this->title = 'Not Found';
        }
        break;
    }
  }
}

// adds basic find/replace, with custom "markdown".
function jr_format($in) {
  // basic formatting replaces the 'easier' bits
  $find = [
    '/\[ref:(rhc|rhcs)(\d+)\]/i',
    '/\[italic:([^\]]+)\]/i',
    '/\[bold:([^\]]+)\]/i',
    '/\[red:([^\]]+)\]/i',
    '/\[tel\]/i',
    '/\[email\]/i',
  ];
  $replace = [
    '<a href="'.home_url('$1/$2').'" >$1$2</a>',
    '<em>$1</em>',
    '<strong>$1</strong>',
    '<em class="greater">$1</em>',
    jr_linkTo('phone'),
    jr_linkTo('eLink')
  ];


  $out = preg_replace($find,$replace,$in);

  return $out;
}

?>

<?php
class pageValidate {

  private $params;

  public $title;  //title of the page
  public $ref;    //user friendly reference (ie forms). if unused defaults to $this->title
  public $unique;  //unique URL friendly reference (ie cache). if unused the "unique" cache is ingored (ie search results)
  public $args; //filters for the wpdb queries;


  private function getParams() {
    $url = jr_getUrl();
    $slashedParams = str_replace(home_url(), '', $url);
    $this->params = explode('/',strtolower($slashedParams));
  }

  private function getCategory($id) {
    global $wpdb;
    $query = "SELECT `Name`, `CategoryDescription`, `Is_RHCs` FROM `rhc_categories` WHERE `Category_ID` LIKE '$id'";
    return $wpdb->get_row($query);
  }

  private function getItem() {
     global $wpdb, $itemSoldDuration;
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
        switch ($p[2]) {
          case 'category':
            $category = $this->getCategory($p[3]);
            if ($category) {
              $this->title = $category->Name;
              $this->args = ['category'=>$category->Name,'ss'=>$category->Is_RHCs];
              $this->unique = 'category-'.$p[3];
            } else {
              $this->title = 'Not Found';
            }
            break;
          case 'search-results':
            $search = $_GET['q'];
            $this->title = "Search results for $search";
            $this->args = ['search'=>$search];
            break;
          case 'arrivals':
            $this->title = "Just In";
            $this->args = ['arrivals'=>true];
            $this->unique = 'category-arrivals';
            break;
          case 'sold':
            $this->title = "Just In";
            $this->args = ['sold'=>true];
            $this->unique = 'category-sold';
            break;
          case 'special-offers':
            $this->title = "Sales";
            $this->args = ['sale'=>true];
            $this->unique = 'category-sale';
            break;
          case 'brand':
            $brand = jr_urlToBrand($params[3]);
            $this->title = "Products from $brand";
            $this->args = ['brand'=>$brand];
            $this->unique = 'category-brand-'.$brand;
            break;
          default:
            $this->title = "All Products";
            $this->args = [];
            $this->unique = 'category-all';
        }
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

/*

<?php
/* this is the first point of action. gets the baseline validateion/variables used on every page.
this stuff is on everypage (in the main menu at very least), so called straight away
$jr_safeArray = jr_validate_urls(jr_getUrl());


/* SECURITY:
  > Since the Back-End is Based on in house PCs, theres a fairly limited amount that the customer can access.
  > no personal details are to be keeped on internet databases
  > Light security whitlelist sanitises the input to prevent injection just in case
/*--------------------------------------------------------------------------------------
//idea of this function is to act as a wall between input and output.
//the user can input whatever they want, but only strings on this function are used in sql queries

function jr_validate_urls($url) {
  $slashedParams = str_replace(home_url(), '', $url);
  $params = explode('/',strtolower($slashedParams));
  $out = [
    'title' => null, 'unique' => false,
    'ss' => false, 'filterType' => null, 'filterVal' => null
  ];

  if ($params[1] == '') { // index
    $out['title'] = 'Home';
    $out['formRef'] = ''; //this page is intentionally left blank

  } elseif ($params[1] == 'brands') {
    $out['title'] = "Shop by Brand";
    $out['filterVal'] = 'brand';
    $out['unique'] = 'category-brands-all';

  } elseif ($params[1] == 'categories') {
    $out['title'] = 'All Products'; //everything - google search override
    $out['filterType'] = 'all';
    $out['args'] = [];

  } elseif ($params[1] == 'products') {

    if ($params[2] == 'all' || $params[2] == '') {
      $out['title'] = 'All Products'; //everything
      $out['filterType'] = 'all';
      $out['args'] = [];

    } elseif ($params[2] == 'search-results') {
      $out['title'] = 'Search Results for \''.$_GET['q'].'\'';
      $out['filterType'] = 'search';
      $out['filterVal'] = str_replace(' ', '|', $_GET['q']);
      $out['args'] = ['search'=>$_GET['q']];

    } elseif ($params[2] == 'category') { //categories
      $getCategory = jrQ_categoryDetails($params[3]);
      if ($getCategory) {
        $out['id'] = $params[3];
        $out['filterType'] = $getCategory['Is_RHCs'] ? 'itemsSS' : 'items';
        $out['unique'] = 'category-'.$params[3];
        $out['title'] = $out['formRef'] = $getCategory['Name'];
        $out['unique'] .= (isset($_GET['pg']) && $_GET['pg'] > 1) ? '-pg'.$_GET['pg'] : null;
        $out['args'] = ['category'=>$getCategory['Name'],'ss'=>$getCategory['Is_RHCs']];
      } else {
        $out['filterVal'] = 'Not Found';
      }

    } elseif ($params[2] == 'arrivals') { //new in
      $out['filterType'] = 'arrivals';
      $out['title'] = 'Just In';
      $out['args'] = ['arrivals'=>true];

    } elseif ($params[2] == 'sold') { //sold
      $out['filterType'] = 'sold';
      $out['title'] = 'Recently Sold';
      $out['unique'] = 'category-sold';
      $out['args'] = ['sold'=>true];

    } elseif ($params[2] == 'special-offers') { //sale
      $out['filterType'] = 'sale';
      $out['filterVal'] = $params[3];
      $out['title'] = 'Special Offers';
      $out['args'] = ['sale'=>$params[3]];

    } elseif ($params[2] == 'brand') { //brand
      $out['filterType'] = 'brand';
      $out['filterVal'] = jr_urlToBrand($params[3]);
      $out['title'] = 'Products from '.$out['filterVal'];
      $out['unique'] = 'category-brand-'.$params[3];
      $out['args'] = ['brand'=>$params[3]];
    }

  } elseif ($params[1] == 'rhc' || $params[1] == 'rhcs') { //product
    $out['filterType'] = 'item';
    $getItem = jrQ_rhc($params);
    if ($getItem) {
      $out['filterVal'] = $params[2];
      $out['title'] = $getItem->ProductName;
      $out['ref'] = $params[1];
      $out['formRef'] = $params[1].$params[2].' - '.$out['title'];
      $out['unique'] = 'item-rhc'.$params[2];
      $out['category'] = $getItem->Category;
    } else {
      $out['filterVal'] = 'Not Found';
    }

/*  } elseif ($params[1] == 'rhcs') { //product-ss
    $out['filterType'] = 'item';
    $getItem = jrQ_rhcs($params[2]);
    if ($getItem) {
      $out['filterVal'] = $params[2];
      $out['ss'] = true;
      $out['title'] = $getItem->ProductName;
      $out['formRef'] = 'RHCs'.$out['filterVal'].' - '.$out['title'];
      $out['unique'] = 'item-rhcs'.$params[2];
      $out['category'] = $getItem->Category;
    } else {
      $out['filterVal'] = 'Not Found';
    }

  };

  return $out;
}; */

?>

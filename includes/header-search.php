<div class="right" ng-class="{'is-fixed':scroller.fix}" >
  <form id="search-box" class="form-search" method="get" action="<?php echo home_url('search-me'); ?>" autocomplete="off"
        ng-controller="searchCtrl" >

    <h2 class="text-icon-left search-w">Search Catering Equipment</h2>

    <input class="search-in text-input" name="search" search-focus="-1"
           ng-model="searchValue" ng-keydown="searchList(-1,$event)"
           placeholder="Enter Keyword, Reference or Manufacturer">

    <button class="btn-red form-btn" type="submit">
      <h3 class="text-icon search-w">Go</h3>
    </button>
<!--ng-if="result.length > 2"-->
    <ul ng-if="results.length > 0" class="tile-outer dark search-out">
      <li ng-repeat="result in results | limitTo: 5">
        <a ng-keydown="searchList($index,$event)" search-focus="$index"
           href="<?php echo home_url('/products/') ?>{{result.filter + '/' + result.url}}" >
          {{result.name}}
          <span>{{result.filter}}</span>
        </a>
      </li>
    </ul>
  </form>
  no cached
</div>
<div class="js-filler" ng-show="scroller.fix" ng-style="scroller.filler"></div>


<!--
  var results = $.parseJSON(data);
  $searchOut.html('');
  $(results).each(function (i) {
    if (i < 4) {
      var link = fileSrc.site + 'products/' + this.filter + '/' + this.url + '/';
      var extra = (this.filter == 'brand') ? '<span> - Brand</span>' : '<span> - Category</span>';
      var output = '<li><a href="' + link + '" >' + this.name + extra + '</a></li>';
      $searchOut.append(output);
    }
  })
-->

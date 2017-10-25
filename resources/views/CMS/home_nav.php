<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="."><img alt="<?php echo env('PAGE_TITLE'); ?>" src="<?php echo env('SITE_URL'); ?>/IMG/icon.png" height=20 width=20> <?= env('PAGE_TITLE'); ?></a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
<?php
foreach($types as $ct){
  if($ct->multi==1){//for multi content types
    $url = env('SITE_URL').'/CMS/content/'.$ct->type;
  }else{//for single content types (multi=false)
    $url = env('SITE_URL').'/CMS/content/'.$ct->type.'/1';
  }
  echo '<li><a href="'.$url.'"><span class="glyphicon '.$ct->icon.'" aria-hidden="true"></span> '.$ct->{'label-plural'}.'</a></li>';
}
?>
        <li><a href="<?=env('SITE_URL'); ?>/CMS/logout"><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Logout</a></li>
      </ul>

    </div>
  </div>
</nav>
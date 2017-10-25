<?php
//echo '<br><br><br>';
//print_r($selectedType);
//die();

//$ct = getContentType($_GET['content']);
?>

  <h1 class="page-header">
  	<span class="glyphicon <?=$selectedType->icon?>" aria-hidden="true"></span> <?=$selectedType->{'label-plural'}?> <a class="btn btn-success" href="<?php echo env('SITE_URL'); ?>/CMS/content/<?=$selectedType->{'type'}?>/add"><span class="glyphicon glyphicon-plus" aria-hidden="false"></a>
  </h1>
<?php
//DELETE CONFIRMATION
if(isset($_GET['delete']) && !isset($_GET['confirm'])){
	echo '<div class="alert alert-danger" role="alert">
		  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
		  Confirm deletion of '.$_GET['delete'].' 
		  <a type="button" class="btn btn-success" href="?content='.$_GET['content'].'&delete='.$_GET['delete'].'&confirm">Yes</a> 
		  <a type="button" class="btn btn-success" href="?content='.$_GET['content'].'">No</a>
		</div>';
}

//DELTE
if(isset($_GET['delete']) && isset($_GET['confirm'])){
	echo '<div class="alert alert-success" role="alert">
		  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
		  The item '.$_GET['delete'].' whas deleted 
		</div>';
	deleteRowFromJson($_GET['content'], $_GET['delete']);
}


?>

<?php
//TABLES TABS
echo $contentTable;
?>


<script>


$( document ).ready(function() {
    $('#langTabs a').click(function (e) {
	  e.preventDefault()
	  $(this).tab('show')
	})
});
</script>
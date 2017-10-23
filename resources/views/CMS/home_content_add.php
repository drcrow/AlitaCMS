 <?php
//gets the definition of the current content type
//$ct = getContentType($_GET['content']);
//flag indicaing the id of the item if it's an edition
//false if it's an "add new"
if(isset($_GET['edit'])){
	$isEdit = $_GET['edit'];
}else{
	$isEdit = false;
}
?>

  <h1 class="page-header">
<?php
if(@$selectMode=='add'){
	echo '<span class="glyphicon '.$selectedType->icon.'" aria-hidden="true"></span> Add '.$selectedType->{'label-singular'};
}elseif(@$selectMode=='edit'){
	echo '<span class="glyphicon '.$selectedType->icon.'" aria-hidden="true"></span> Edit '.$selectedType->{'label-singular'};
}
?>
  </h1>

<?php
	
	/*
	echo '<pre>'.print_r($selectedType, true).'</pre>';
	foreach($selectedTypeFields as $selectedTypeField){
		echo '<pre>'.print_r($selectedTypeField, true).'</pre>';
	}
	*/
	//die();

//SAVE (or EDIT)
if(count($_POST)>0){
	if(saveData($ct, $_POST)){
		echo '<div class="alert alert-success" role="alert">Data saved!</div>';
	}else{
		echo '<div class="alert alert-danger" role="alert">Error saving the data!</div>';
	}
}


//FORMS TABS
echo $contentForm;

?>



<script>
//language tabs</div>
$( document ).ready(function() {
    $('#langTabs a').click(function (e) {
	  e.preventDefault()
	  $(this).tab('show')
	})
});
</script>



<script>
//WYSIWYG
$('.add_trumbowyg_wysiwyg').trumbowyg();
</script>

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
if(isset($_GET['add'])){
	echo '<span class="glyphicon '.$ct->icon.'" aria-hidden="true"></span> Add '.$ct->single;
}elseif($isEdit){
	echo '<span class="glyphicon '.$ct->icon.'" aria-hidden="true"></span> Edit '.$ct->single;
}
?>
  </h1>

<?php

echo '<pre>'.print_r($selectedType, true).'</pre>';
	foreach($selectedTypeFields as $selectedTypeField){
		echo '<pre>'.print_r($selectedTypeField, true).'</pre>';
	}
	//die();

//SAVE (or EDIT)
if(count($_POST)>0){
	if(saveData($ct, $_POST)){
		echo '<div class="alert alert-success" role="alert">Data saved!</div>';
	}else{
		echo '<div class="alert alert-danger" role="alert">Error saving the data!</div>';
	}
}


//echo '<pre>'.print_r($_POST, true).'</pre>';

//LANGUAGES TABS
if(count($languages)>1){
	$first = true;
	echo '<ul class="nav nav-tabs" id="langTabs">';
	foreach($languages as $lang){
		if($first){
			$first = false;
			$tempClass = 'active';	
		}else{
			$tempClass = '';
		}

		echo '<li role="presentation" class="'.$tempClass.'"><a href="#langTab-'.$lang.'"><img src="CMS/img/lang/'.$lang.'.png"> '.$lang.'</a></li>';
	}
	echo '</ul>';
}
?>

<?php
//FORMS TABS
if(count($languages)>1){
	$first = true;
	echo '<form class="form-horizontal content-form" method="post">';
	echo '<div class="tab-content" id="langTabContent"> ';
	foreach($languages as $lang){
		if($first){
			$first = false;
			$tempClass = 'active in';	
		}else{
			$tempClass = '';
		}

		echo '<div class="tab-pane fade '.$tempClass.'" role="tabpanel" id="langTab-'.$lang.'" aria-labelledby="home-tab"> ';
		//echo getForm($ct, $lang, $isEdit);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$form = '';
		if($isEdit){
			die('edition not working :)');
			/*
			$dataFilePath = CONTENT_DATA_DIR.$ct->type.'-'.$lang.'.json';
			$data = getArrayFromJsonFile($dataFilePath, true);
			$data = $data[$isEdit];
			*/
			//echo '<pre>'.print_r($data, true).'</pre>';
		}
		foreach($selectedTypeFields as $field){
			$enabled = true;
			if($isEdit && @$field->index==1){
				$enabled = false;
			}
			$required = false;
			/*
			if(getIndexId($ct->type) == $field->id){
				$required = true;
			}
			*/
			$form .= getFormField($field, $lang, $enabled, @$data[$field->id], $required);
		}
		$form .= '<div class="pull-right" style="overflow:auto"><button class="btn btn-primary" type="submit">Save</button></div>';
		echo $form;

		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		echo '</div>';
		
	}
	echo '<input type="hidden" name="type" value="'.$ct->type.'">';
	echo '<input type="hidden" name="edit" value="'.@$_GET['edit'].'">';
	echo '</div>';
	echo '</form>';
}
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
//index fields relations
//one language changes all the others
$( document ).ready(function() {
<?php
$index = getIndexId($ct->type);

foreach($languages as $lang){
	$id = 'input[name="'.$lang.'['.$index.']"]';
	echo '$(\''.$id.'\').keyup(function() {';
	foreach($languages as $lang2){
		$id2 = 'input[name="'.$lang2.'['.$index.']"]';
		echo '$(\''.$id2.'\').val($(this).val());';
	}
	echo '});';
}
?>
});
</script>

<script>
//WYSIWYG
$('.add_trumbowyg_wysiwyg').trumbowyg();
</script>

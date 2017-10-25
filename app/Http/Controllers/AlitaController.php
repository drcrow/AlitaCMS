<?php 
namespace App\Http\Controllers;
use App\Type as Type;
use DB;
use App\Http\Controllers\Controller;

class AlitaController extends Controller {
    
    public function showCMS() {
        $types 		= Type::all();
        $languages 	= explode(',', env('LANGUAGES'));

        return view('CMS/index')
        	->with('languages', 	$languages)
        	->with('types', 		$types)
        	;
    }

    public function showCMStype($type) {
         $types 		= Type::all();
         $languages 	= explode(',', env('LANGUAGES'));
         $type 			= DB::table('types')->where('type', $type)->first();
         $fields 		= DB::table('types-fields')->get();
         $content 		= DB::table('content')->where('type-id', $type->id)->get();
         $contentTable 	=  $this->contentTable($type, $content, $languages);

        return view('CMS/index')
        	->with('selectMode',		 	'list')
        	->with('languages', 			$languages)
        	->with('types', 				$types)
        	->with('selectedType', 			$type)
        	->with('selectedTypeFields', 	$fields)
        	->with('content', 				$content)
        	->with('contentTable', 			$contentTable)
        	;
    }

    public function showCMStypeAdd($type) {
         $types 		= Type::all();
         $languages 	= explode(',', env('LANGUAGES'));
         $type 			= DB::table('types')->where('type', $type)->first();
         $fields 		= DB::table('types-fields')->get();
         $content 		= DB::table('content')->where('type', $type);
         $contentForm 	= $this->contentForm($type, $fields, $content, $languages);

        return view('CMS/index')
        	->with('selectMode', 			'add')
        	->with('languages', 			$languages)
        	->with('types', 				$types)
        	->with('selectedType', 			$type)
        	->with('selectedTypeFields', 	$fields)
        	->with('content', 				$content)
        	->with('contentForm', 			$contentForm)
        	;
    }


    public function contentTable($type, $fields, $content, $languages){
    	$table = '';
    	$first = true;
		$table .= '<ul class="nav nav-tabs" id="langTabs">';
		foreach($languages as $lang){
			if($first){
				$first 		= false;
				$tempClass 	= 'active';	
			}else{
				$tempClass 	= '';
			}

			$table .= '<li role="presentation" class="'.$tempClass.'"><a href="#langTab-'.$lang.'"><img src="'.env('SITE_URL').'/IMG/lang/'.$lang.'.png"> '.$lang.'</a></li>';
		}
		$table .= '</ul>';


		$first = true;
		$table .= '<form class="form-horizontal content-form" method="post">';
		$table .= '<div class="tab-content" id="langTabContent"> ';

		foreach($languages as $lang){
			if($first){
				$first 		= false;
				$tempClass 	= 'active in';	
			}else{
				$tempClass 	= '';
			}

			$table .= '<div class="tab-pane fade '.$tempClass.'" role="tabpanel" id="langTab-'.$lang.'" aria-labelledby="home-tab"> ';
			/////////////////////////////////////////////////////////////////////
			$table .= $this->contentTableGenerator($type, $fields, $content, $lang);
			////////////////////////////////////////////////////////////////////
			$table .= '</div>';
			
		}
		$table .= '</div>';
		$table .= '</form>';

		return $table;
    }




    public function contentForm($type, $fields, $languages, $edit=''){
    	$form = '';

    	$first = true;
		$form .= '<ul class="nav nav-tabs" id="langTabs">';
		foreach($languages as $lang){
			if($first){
				$first = false;
				$tempClass = 'active';	
			}else{
				$tempClass = '';
			}

			$form .= '<li role="presentation" class="'.$tempClass.'"><a href="#langTab-'.$lang.'"><img src="'.env('SITE_URL').'/IMG/lang/'.$lang.'.png"> '.$lang.'</a></li>';
		}
		$form .= '</ul>';

		$form .= '<form class="form-horizontal content-form" method="post">';
		$form .= '<div class="tab-content" id="langTabContent"> ';

    	$first = true;

    	foreach($languages as $lang){
			if($first){
				$first = false;
				$tempClass = 'active in';	
			}else{
				$tempClass = '';
			}

			$form .= '<div class="tab-pane fade '.@$tempClass.'" role="tabpanel" id="langTab-'.@$lang.'" aria-labelledby="home-tab"> ';

			if(@$isEdit){
				die('edition not working :)');
			}

			foreach($fields as $field){
				$enabled = true;
				if(@$isEdit && @$field->index==1){
					$enabled = false;
				}
				$required = false;
				/*
				if(getIndexId($ct->type) == $field->id){
					$required = true;
				}
				*/
				$form .= $this->getFormField($field, $lang, $enabled, @$data[$field->id], $required);
				

				$form .= '<pre>'.print_r($field, true).'</pre>';
			}
			$form .= '<div class="pull-right" style="overflow:auto"><button class="btn btn-primary" type="submit">Save</button></div>';
			$form .= '</div>';
		}

		$form .= '<input type="hidden" name="type" value="'.$type->type.'">';
		$form .= '<input type="hidden" name="edit" value="'.$edit.'">';
		$form .= '</div>';
		$form .= '</form>';

		return $form;
    }

    public function getTable($ct, $lang){

		$dataFilePath = CONTENT_DATA_DIR.$ct->type.'-'.$lang.'.json';
		$actualData = getArrayFromJsonFile($dataFilePath);

		$actualData = DB::table('content')->where('type', $type);

		$html = '
		<table class="table table-bordered table-striped">
			<thead><tr>';
		//table headers
		foreach($ct->fields as $field){
			//if(@$field->list==1){
			if(isFieldListable($ct, $field->id)){
				$html .= '<th>'.$field->name.'</th>';
			}
		}
		$html .= '<th>&nbsp;</th>';
		$html .= '<tbody>';
		//table data
		if($actualData==''){
			$actualData = array();
		}
		foreach($actualData as $row){
			$html .= '<tr>';
			foreach($row as $id=>$value){
				if(isFieldListable($ct, $id)){
					$html .= '<td>'.$value.'</td>';
				}
			
			}
			//table options buttons
			$html .= '<td class="tableOptionsCell">';
			$html .= '<a type="button" class="btn btn-success" href="?content='.$_GET['content'].'&edit='.$row->{getIndexId($ct->type)}.'"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>';

			$html .= '&nbsp;&nbsp;';
			$html .= '<a type="button" class="btn btn-danger" href="?content='.$_GET['content'].'&delete='.$row->{getIndexId($ct->type)}.'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>';
			$html .= '</td>';
			$html .= '</tr>';
		}
		$html .= '</tbody>';
		$html .= '</table>';
		return $html;
	}



























/*
Field functions
Define new fields types here
*/

public function getFormField($field, $lang, $enabled=true, $value='', $required=false){
	$func = 'field_'.$field->format;
	//if(function_exists($func)){
		return $this->$func($field, $lang, $enabled, $value, $required);
	//}else{
		//return 'Field type '.$field->format.' is undefined!';
	//}
}

///////////////////////////////

public function field_separator($fieldInfo, $lang, $enabled=true, $value='', $required=false){
	//field id & name
	$fId = $lang.'['.$fieldInfo->id.']';

	$html = '
		<div class="form-group">
			<label for="'.$fId.'" class="col-sm-2 control-label"><h3>'.$fieldInfo->name.'</h3></label>
			<div class="col-sm-8"><hr></div>
		</div>
	';

	return $html;
}

public function field_text($fieldInfo, $lang, $enabled=true, $value='', $required=false){
	//field id & name
	$fId = $lang.'['.$fieldInfo->id.']';
	//hint (or "help block")
	$hint = '';
	if(isset($fieldInfo->hint)){
		$hint = '<p class="help-block">'.$fieldInfo->hint.'</p>';
	}
	//reload value from POST
	if(isset($_POST[$lang][$fieldInfo->id])){
		$value = $_POST[$lang][$fieldInfo->id];
	}
	//disable field
	if($enabled){
		$disabled = '';
	}else{
		$disabled = 'readonly';
	}
	//required field
	if($required){
		$required = 'required';
	}else{
		$required = '';
	}
	$html = '
		<div class="form-group">
			<label for="'.$fId.'" class="col-sm-2 control-label">'.$fieldInfo->name.'</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="'.$fId.'" name="'.$fId.'" value="'.$value.'" '.$disabled.' '.$required.'>'.$hint.'
			</div>
		</div>
	';

	return $html;
}

public function field_number($fieldInfo, $lang, $enabled=true, $value='', $required=false){
	$fId = $lang.'['.$fieldInfo->id.']';
	$hint = '';
	if(isset($fieldInfo->hint)){
		$hint = '<p class="help-block">'.$fieldInfo->hint.'</p>';
	}
	//$value = '';
	if(isset($_POST[$lang][$fieldInfo->id])){
		$value = $_POST[$lang][$fieldInfo->id];
	}
	if($enabled){
		$disabled = '';
	}else{
		$disabled = 'readonly';
	}
	//required field
	if($required){
		$required = 'required';
	}else{
		$required = '';
	}
	$html = '
		<div class="form-group">
			<label for="'.$fId.'" class="col-sm-2 control-label">'.$fieldInfo->name.'</label>
			<div class="col-sm-2">
				<input type="number" class="form-control" id="'.$fId.'" name="'.$fId.'" value="'.$value.'" '.$disabled.' '.$required.'>'.$hint.'
			</div>
		</div>
	';

	return $html;
}

public function field_url($fieldInfo, $lang, $enabled=true, $value=''){
	$fId = $lang.'['.$fieldInfo->id.']';
	$hint = '';
	if(isset($fieldInfo->hint)){
		$hint = '<p class="help-block">'.$fieldInfo->hint.'</p>';
	}
	//$value = '';
	if(isset($_POST[$lang][$fieldInfo->id])){
		$value = $_POST[$lang][$fieldInfo->id];
	}
	if($enabled){
		$disabled = '';
	}else{
		$disabled = 'readonly';
	}
	$html = '
		<div class="form-group">
			<label for="'.$fId.'" class="col-sm-2 control-label">'.$fieldInfo->name.'</label>
			<div class="col-sm-10">
				<input type="url" class="form-control" id="'.$fId.'" name="'.$fId.'" value="'.$value.'" '.$disabled.'>'.$hint.'
			</div>
		</div>
	';

	return $html;
}

public function field_multiline($fieldInfo, $lang, $enabled=true, $value=''){
	$fId = $lang.'['.$fieldInfo->id.']';
	$hint = '';
	if(isset($fieldInfo->hint)){
		$hint = '<p class="help-block">'.$fieldInfo->hint.'</p>';
	}
	//$value = '';
	if(isset($_POST[$lang][$fieldInfo->id])){
		$value = $_POST[$lang][$fieldInfo->id];
	}
	if($enabled){
		$disabled = '';
	}else{
		$disabled = 'readonly';
	}
	if(@$fieldInfo->wysiwyg){
		$wysiwygClass = 'add_trumbowyg_wysiwyg';
	}else{
		$wysiwygClass = '';
	}
	$html = '
		<div class="form-group">
			<label for="'.$fId.'" class="col-sm-2 control-label">'.$fieldInfo->name.'</label>
			<div class="col-sm-10">
				<textarea class="form-control '.$wysiwygClass.'" rows="3" id="'.$fId.'" name="'.$fId.'" placeholder="" '.$disabled.'>'.$value.'</textarea>'.$hint.'
			</div>
		</div>
	';

	return $html;
}

}//class
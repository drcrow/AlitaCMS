<?php 
namespace App\Http\Controllers;
use App\Type as Type;
use DB;
use App\Http\Controllers\Controller;

class FormController extends Controller {

	public static create($ct, $lang, $isEdit=false){
		$html = '';
		if($isEdit){
			$dataFilePath = CONTENT_DATA_DIR.$ct->type.'-'.$lang.'.json';
			$data = getArrayFromJsonFile($dataFilePath, true);
			$data = $data[$isEdit];
			//echo '<pre>'.print_r($data, true).'</pre>';
		}
		foreach($ct->fields as $field){
			$enabled = true;
			if($isEdit && @$field->index==1){
				$enabled = false;
			}
			$required = false;
			if(getIndexId($ct->type) == $field->id){
				$required = true;
			}
			$html .= getFormField($field, $lang, $enabled, @$data[$field->id], $required);
		}
		$html .= '<div class="pull-right" style="overflow:auto"><button class="btn btn-primary" type="submit">Save</button></div>';
		return $html;
	}

}
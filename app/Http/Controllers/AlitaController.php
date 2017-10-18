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
         $types 	= Type::all();
         $languages = explode(',', env('LANGUAGES'));
         $type 		= DB::table('types')->where('type', $type)->first();
         $fields 	= DB::table('types-fields')->get();
         $content 	= DB::table('content')->where('type', $type);

        return view('CMS/index')
        	->with('selectMode',		 	'list')
        	->with('languages', 			$languages)
        	->with('types', 				$types)
        	->with('selectedType', 			$type)
        	->with('selectedTypeFields', 	$fields)
        	->with('content', 				$content)
        	;
    }

    public function showCMStypeAdd($type) {
         $types 		= Type::all();
         $languages 	= explode(',', env('LANGUAGES'));
         $type 			= DB::table('types')->where('type', $type)->first();
         $fields 		= DB::table('types-fields')->get();
         $content 		= DB::table('content')->where('type', $type);
         $contentForm 	= $this->contentForm();

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

    public function contentForm(){
    	
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

}
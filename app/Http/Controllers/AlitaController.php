<?php 
namespace App\Http\Controllers;
use App\Type as Type;

use App\Http\Controllers\Controller;

class AlitaController extends Controller {
    public function showCMS() {
        $types = Type::all();
        return view('CMS/index')->with('types', $types);
    }

}
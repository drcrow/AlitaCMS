<?php 
namespace App\Http\Controllers;
use App\Type as Type;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailsController extends Controller {

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function viewEmail($lang, $email, $market){

		return view('emails/'.$lang.'/'.$email.'/index');

	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function sendInvite(Request $request, $lang){

		return response()->json(array('status'=>'ok'));

	}

}//class
?>
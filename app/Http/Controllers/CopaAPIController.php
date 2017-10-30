<?php 
namespace App\Http\Controllers;
use App\Type as Type;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CopaAPIController extends Controller {
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function getSiteCopy($lang){
		//content type id from copy is 4
		$content = DB::table('content-fields')
			->join('types-fields', 'content-fields.field-id', '=', 'types-fields.id')
			->where('content-id', 4)
			->where('lang-code', $lang)
			->select('types-fields.label', 'types-fields.name', 'content-fields.value', 'content-fields.lang-code')
			->get();
		return $content;
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function userLogin(Request $request, $lang){
		$m1 = $request->input('email');
        $m2 = $request->input('email2');

        if($m1!=$m2){
        	return response()->json(array('status'=>'error', 'code'=>771, 'description'=>'email and email2 must be equal'));
        }

        $user = DB::table('users')
        	->where('email', $m1)
        	->first();
		


       	//user exists
        if(isset($user->email) && $user->email == $m1){
        	//return $user;
        	$user->status = 'ok';
        	return response()->json($user);
        }else{
	        return response()->json(array('status'=>'error', 'code'=>772, 'description'=>'user doesnt exists'));
        }

	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function userRegister(Request $request, $lang){
		//return response()->json($request);

		 $user = DB::table('users')
        	->where('email', $request->email)
        	->first();

        //email already exists
        if(isset($user->email) && $user->email == $request->email){
        	return response()->json(array('status'=>'error', 'code'=>773, 'description'=>'email already exists'));
        }


        //add new user
		$id = DB::table('users')->insertGetId(
			[
				'fname' 		=> $request->fname, 
				'lname' 		=> $request->lname,
				'email' 		=> $request->email, 
				'lang' 			=> $lang, 
				'birthday' 		=> $request->birthday,
				'country' 		=> $request->country,
				'city' 			=> $request->city, 
				'created_at' 	=> date('Y-m-d H:i:s')
			]
		);

		/*
		$user = DB::table('users')
	        ->where('id', $id)
	        ->first();

	    $user->status = 'ok';
	    return response()->json($user);
	    */

	    return $this->userInfo($id);
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function userInfo($id){
		$user = DB::table('users')
	        ->where('id', $id)
	        ->first();

	    $user->status = 'ok';
	    return response()->json($user);
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function gameQuestions($lang){
		$questions = DB::table('questions')
			->where('lang', $lang)
			->select('id', 'lang', 'question', 'options')
			->inRandomOrder()
			->limit(5)
			->get();

		foreach($questions as $id=>$q){
			$questions[$id]->options = explode("\r\n", $q->options);
		}


		return response()->json($questions);
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function gameValidateAswer(Request $request, $lang){
		$question = DB::table('questions')
			->select('id', 'lang', 'question', 'options', 'answer')
        	->where('id', $request->question)
        	->where('lang', $lang)
        	->first();


        if(@$question->id != $request->question){
        	return response()->json(array('status'=>'error', 'code'=>774, 'description'=>'question doesnt exists'));
        }

        if($question->answer == $request->answer){
        	$question->valid = true;
        }else{
        	$question->valid = false;
        }

        $question->status = 'ok';
        $question->options = explode("\r\n", $question->options);
        return response()->json($question);
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function gameResult(Request $request, $lang){
		

		
	}







}//class

?>
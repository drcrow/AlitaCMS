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
		

        return $this->userInfo(@$user->id);
        /*
       	//user exists
        if(isset($user->email) && $user->email == $m1){
        	//return $user;
        	
			$user->points = $this->getUserPoints($user->id);
        	$user->status = 'ok';
        	return response()->json($user);
        }else{
	        return response()->json(array('status'=>'error', 'code'=>772, 'description'=>'user doesnt exists'));
        }
        */

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
				'phone'			=> $request->phone,
				'referer' 		=> @$request->referer,
				'created_at' 	=> date('Y-m-d H:i:s')
			]
		);


		$ac = DB::table('actions')->insertGetId(
		    [
		    	'user_id' 		=> $id, 
		    	'action' 		=> 'registration',
		    	'points'		=> 10,
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

	    if(@$user->id != $id || ($id+0) <= 0){
	    	return response()->json(array('status'=>'error', 'code'=>772, 'description'=>'user doesnt exists'));
	    }


	    $previousGame = DB::table('actions')
        	->where('action', 'trivia')
        	->where('user_id', $id)
        	->whereRaw('created_at > (NOW() - INTERVAL 24 HOUR)')
        	->first();

        if(@$previousGame->user_id == $id){
        	@$user->already_played = true;
        }else{
        	@$user->already_played = false;
        }

	    $user->points = $this->getUserPoints(@$user->id);
	    $user->status = 'ok';
	    return response()->json($user);
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function gameQuestions(Request $request, $lang){

		$previousGame = DB::table('actions')
        	->where('action', 'trivia')
        	->where('user_id', $request->user)
        	->whereRaw('created_at > (NOW() - INTERVAL 24 HOUR)')
        	->first();

        	//return response()->json($previousGame);

        if(@$previousGame->user_id == $request->user){
        	return response()->json(array('status'=>'error', 'code'=>775, 'description'=>'already played in the last 24hs'));
        }else{

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
		$previousGame = DB::table('actions')
        	->where('action', 'trivia')
        	->where('user_id', $request->user)
        	->whereRaw('created_at > (NOW() - INTERVAL 24 HOUR)')
        	->first();

        	//return response()->json($previousGame);

        if(@$previousGame->user_id == $request->user){
        	return response()->json(array('status'=>'error', 'code'=>775, 'description'=>'already played in the last 24hs'));
        }else{

        	$id = DB::table('actions')->insertGetId(
			    [
			    	'user_id' 		=> $request->user, 
			    	'action' 		=> 'trivia',
			    	'points'		=> $request->answers,
			    	'trivia_time'	=> $request->time,
			    	'created_at' 	=> date('Y-m-d H:i:s')
			    ]
			);

			$action = DB::table('actions')
		        ->where('id', $id)
		        ->first();

		    $action->status = 'ok';
		    return response()->json($action);

        }
		
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function getUserPoints($id){

		$actions = DB::table('actions')
			->select(DB::raw('SUM(points) as total_points'))
        	->where('user_id', $id)
        	->first();


        return($actions->total_points);
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function userShare(Request $request, $lang){
		
		if($request->method != 'facebook' && $request->method != 'twitter' && $request->method != 'email'){
			return response()->json(array('status'=>'error', 'code'=>776, 'description'=>'unknown method'));
		}

		$previousShare = DB::table('actions')
        	->where('action', $request->method)
        	->where('user_id', $request->user)
        	->whereRaw('created_at > (NOW() - INTERVAL 24 HOUR)')
        	->first();

        	//return response()->json($previousGame);

        if(@$previousShare->user_id == $request->user){
        	return response()->json(array('status'=>'error', 'code'=>777, 'description'=>'already shared with this method in the past 24hs'));
        }else{
        	$id = DB::table('actions')->insertGetId(
			    [
			    	'user_id' 		=> $request->user, 
			    	'action' 		=> $request->method,
			    	'points'		=> 5,
			    	'created_at' 	=> date('Y-m-d H:i:s')
			    ]
			);

			$action = DB::table('actions')
		        ->where('id', $id)
		        ->first();

		    $action->status = 'ok';
		    return response()->json($action);
        }


	}



}//class

?>
<?php

	namespace App\Http\Controllers;

	use \http\Env\Response;
	use \Illuminate\Http\Request;
	use \Illuminate\Support\Facades\DB;
	use \Illuminate\Support\Facades\Hash;

	class TodoController extends Controller
	{
		/**
		 * @param  Request  $request
		 * @return Response
		 */
		public function select(Request $request){
			$validation = $this->validate($request, []);

			$user = $request->user();

			$todos = DB::connection('mysql.read')
					   ->table('todos')
					   ->where('id','=',$user->getAuthIdentifier())
					   ->get();

			$this->addResult('todos',$todos);
			$this->addMessage('success','All your Todos.');

			return $this->getResponse();
		}
	}

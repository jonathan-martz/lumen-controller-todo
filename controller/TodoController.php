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

			$todos = DB::connection('mysql.read')
					   ->table('todos')
					   ->where('UID','=',$request->user()->getAuthIdentifier())
					   ->get();

			$this->addResult('todos',$todos);
			$this->addMessage('success','All your Todos.');

			return $this->getResponse();
		}

		/**
		 * @param  Request  $request
		 * @return Response
		 */
		public function add(Request $request){
			$validation = $this->validate($request, [
				'todo' => 'bail|required|array',
				'todo.category' => 'bail|required|string',
				'todo.title' => 'bail|required|string',
				'todo.deadline' => 'integer',
				'todo.description' => 'bail|required|string',
				'todo.prio' => 'bail|required|alpha',
			]);

			$todo = $request->input('todo');

			$todos = DB::connection('mysql.read')
					   ->table('todos')
					   ->where('title','=',$todo['title'])
					   ->where('description','=',$todo['description'])
					   ->where('category','=',$todo['category'])
					   ->where('UID','=',$request->user()->getAuthIdentifier());

			$count = $todos->count();
			$existing = $todos->first();

			if($count !== 0){
				$this->addMessage('error','Todo already exists('.$existing->id.').');
			}
			else{
				$result =  DB::connection('mysql.read')
							 ->table('todos')
							 ->insert([
								 'title'=>$todo['title'],
								 'category'=>$todo['category'],
								 'deadline'=>$todo['deadline'],
								 'description'=>$todo['description'],
								 'prio'=> $todo['prio'],
								 'UID' =>$request->user()->getAuthIdentifier()
							 ]);

				if($result){
					$this->addMessage('success','All your Todos.');
				}
				else{
					$this->addMessage('warning','Upps da ist wohl was schief gelaufen.');
				}
			}

			return $this->getResponse();
		}
	}

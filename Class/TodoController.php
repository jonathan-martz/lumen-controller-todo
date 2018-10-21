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

			$this->addResult('status', 'success');
			$this->addResult('message', 'All your Todos.');

			return $this->getResponse();
		}
	}

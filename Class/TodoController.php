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
		public function user(Request $request){
			$validation = $this->validate($request, [
				'username' => 'required',
				'password' => 'required|min:8'
			]);

			$this->addResult('username',$request->input('username'));

			$user = DB::connection('mysql.read')
					  ->table('users')
					  ->where('username', '=', $request->input('username'))
					  ->where('username_hash', '=', sha1($request->input('username')))
					  ->first();

			$trys = DB::connection('mysql.read')
					  ->table('login_try')
					  ->where('username', '=', $request->input('username'))
					  ->where('username_hash', '=', sha1($request->input('username')))
					  ->whereNotIn('status' , ['success'])
					  ->where('created_at','>',time() - (60 * 60))
					  ->count();

			if($trys < 10){
				if($user !== NULL){
					if (Hash::check($request->input('password'), $user->password))
					{
						$token = bin2hex(openssl_random_pseudo_bytes(512));

						DB::connection('mysql.write')
						  ->table('login_try')
						  ->insert([
							  'username' => $request->input('username'),
							  'username_hash' => sha1($request->input('username')),
							  'status' => 'success',
							  'created_at' => time()
						  ]);

						DB::connection('mysql.write')
						  ->table('auth_tokens')
						  ->insert([
							  'token' => $token,
							  'UID' => $user->id,
							  'created_at' => time()
						  ]);

						$this->addResult('status', 'success');
						$this->addResult('message', 'User authenticated.');
						$this->addResult('auth', [
							'token'=> $token,
							'expires'=> time() + (60 * 60 * 24 * 7)
						]);
						$this->addResult('user', [
							'username' => $user->username,
							'email' => $user->email,
							'id'=> $user->id
						]);

						return $this->getResponse();
					}
					else{
						DB::connection('mysql.write')
						  ->table('login_try')
						  ->insert([
							  'username' => $request->input('username'),
							  'username_hash' => sha1($request->input('username')),
							  'status' => 'failed',
							  'created_at' => time()
						  ]);

						$this->addResult('status', 'warning');
						$this->addResult('message', 'User credentials wrong.');

						return $this->getResponse();
					}
				}
				else{
					$this->addResult('status', 'error');
					$this->addResult('message', 'User doesnt exists.');

					return $this->getResponse();
				}
			}
			else{
				DB::connection('mysql.write')
				  ->table('login_try')
				  ->insert([
					  'username' => $request->input('username'),
					  'username_hash' => sha1($request->input('username')),
					  'status' => 'blocked',
					  'created_at' => time()
				  ]);

				$this->addResult('status', 'error');
				$this->addResult('message', 'User login blocked.');
				$this->addResult('trys', $trys);

				return $this->getResponse();
			}
		}
	}

<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Users\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use App\Helpers\LogActivity;


class UserController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('isAdmin')) {
            return $this->unauthorizedResponse();
        }
        $users = User::latest()->paginate(10);
        return $this->sendResponse($users, 'Users list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Users\UserRequest  $request
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'type' => $request['type'],
            'active' => $request['active'],
        ]);

        LogActivity::addToLog('info','Novo Usuário ' . $request['email'] . ' foi criado.' );

        return $this->sendResponse($user, 'Usuário criado com sucesso!');
    }

    /**
     * Update the resource in storage
     *
     * @param  \App\Http\Requests\Users\UserRequest  $request
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if (!empty($request->password)) {
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());
        LogActivity::addToLog('info','Usuário ' . $user->name . ' foi atualizado.' );

        return $this->sendResponse($user, 'O usuário foi atualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->authorize('isAdmin');
        $user = User::findOrFail($id);
        if($id == 1){
            $response = [
                'success' => false,
                'data'    => $user,
                'message' => 'Super Admin não deve ser apagado!',
            ];
            return response()->json($response, 403);
        }else{
        // delete the user
            if($user->delete()){
                LogActivity::addToLog('warning','Usuário ' . $user->name . ' foi deletado.' );
            }

        return $this->sendResponse([$user], 'Usuário foi eliminado da base');
        }

    }
}

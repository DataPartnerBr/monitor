<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ChangePasswordRequest;
use App\Http\Requests\Users\ProfileUpdateRequest;
use App\Models\User;
use App\Models\LogActivity;
use App\Helpers\LogActivity as Logs;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Image;

class ProfileController extends BaseController
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
     * Return the user data
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $userData = auth('api')->user();
        $response = [
            'success' => true,
            'data'    => $userData,
            'message' => 'Perfil de usuário'
        ];
        return response()->json($response, 200);
    }


    /**
     * Update the profile by users
     *
     * @param  \App\Http\Requests\Users\ProfileUpdateRequest  $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateProfile(ProfileUpdateRequest $request)
    {
        $user = auth('api')->user();

        $user->update($request->all());

        Logs::addToLog('info','Perfil do Usuário ' . $user->name . ' foi atualizado.' );

        $response = [
            'success' => true,
            'data'    => $user,
            'message' => 'Perfil foi atualizado',
        ];
        return response()->json($response, 200);
    }

    public function update_avatar(Request $request){
        $data = $request->validate([
           // 'avatar'   => ['image', 'dimensions:max_width=1000,max_height=1000']
            'avatar'   => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=1000,max_height=1000',
        ]);

        $file = $request->file('avatar');
        // Resize image
        $resize = Image::make($file)->resize(128, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg');

        $name = 'avatars/' . uniqid() . '.' . $file->extension();
        $file->storePubliclyAs('public/', $name);
        $user = auth('api')->user();
        $user->update(
           [ 'avatar_url' => '/storage/'.$name]
        );
        $data['avatar_url'] = $name;


        Log::info('return request: '. json_encode($data) );
        return $this->sendResponse($data, 'Imagem carregada com sucesso!');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Users\ChangePasswordRequest  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $userData = auth('api')->user();
        User::find(auth('api')->user()->id)->update(['password' => Hash::make($request->new_password)]);
        Logs::addToLog('info','Senha do Usuário ' . $userData->name . ' foi atualizada.' );
        $response = [
            'success' => true,
            'data'    => [],
            'message' => 'Senha foi atualizada',
        ];
        return response()->json($response, 200);
    }

    public function getLogs(){
        $userData = auth('api')->user();
        $logActivity = LogActivity::where('user_id', $userData->id)->where('type', 'info')->latest()->paginate(10);
        //return response()->json($logActivity, 200);
        return $this->sendResponse($logActivity, 'Lita de atividades');

    }
}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        // return $this->success($users);
        return UserResource::collection($users);
        //这里不能用$this->success(UserResource::collection($users))
        //否则不能返回分页标签信息
    }

    //用户注册
    public function store(UserRequest $request)
    {
        $res = User::create($request->all());
        if ($res) $this->success('用户注册成功...');
        return $this->failed('注册失败...');
    }

    //用户登录
    public function login(Request $request)
    {
        $res = Auth::guard('web')->attempt(['name' => $request->name, 'password' => $request->password]);
        if ($res) return $this->setStatusCode(201)->success('用户登录成功...');

        return $this->failed('用户登录失败', 401);
    }

    //返回单一用户信息
    public function show(User $user)
    {
        
        return $this->success(new UserResource($user));
    }
}

<?php

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Service\UserService;
use App\Service\HealthService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(private UserService $userService, private HealthService $healthService){}


    public function health()
    {
        $status = $this->healthService->health();
        return response()->json(['status' => $status, 'message' => 'Service status retrieved successfully'],201);
    }

    public function user(int $id) : JsonResponse
    {
        $user = $this->userService->getUser($id);
        
        return response()->json(['data' => $user, 'status' => true, 'message' => 'user retrieved successfully' ], 200);
    }

    public function users() : JsonResponse
    {
        $users = $this->userService->getUsers();

        return response()->json(['data' => $users, 'status' => true, 'message' => 'users retrieved successfully' ], 200);
    }

    public function addUser(UserRequest $request) : JsonResponse
    {
        $user = $this->userService->create($request);
        return response()->json(['data' => $user, 'status' => true, 'message' => 'user created successfully' ], 201);
    }

    public function updateUser(int $userId, UserRequest $request) : JsonResponse
    {
        $user = $this->userService->updateUser($userId, $request);
        return response()->json(['data' => $user, 'status' => true, 'message' => 'user updated successfully' ], 201);
    }

    public function doesUserExist(int $id) : bool|JsonResponse
    {
        $user = $this->userService->existsById($id);
        
        if($user == 1){
            return response()->json(['success' => true, 'message' => "user exists",], 200);
         }
         else{
            return response()->json(['success' => false, 'message' => "user does not exists",], 404);
         }
    }
   
}

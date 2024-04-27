<?php

namespace App\Service;

use App\Exceptions\UserNotFoundException;
use App\Repository\UserRepository;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserService 
{

 public function __construct(private UserRepository $userRepository){}

 public function create(UserRequest $request){
    $data    = [
      'first_name'  => $request->first_name,
      'last_name'   => $request->last_name,
      'email'       => $request->email,
      'phone'       => $request->phone,
      'password'    => Hash::make($request->password)
    ];
    $user = $this->userRepository->save($data);
    return $user;
 }

 public function updateUser(int $UserId, UserRequest $request){
  return $this->userRepository->update($UserId, $request);
}


 public function getUsers(){
  try{
    return $this->userRepository->findAll();

  }catch (UserNotFoundException $exception) {

    return response()->json([
        'error' => 'Users Not Found',
        'message' => $exception->getMessage(),
    ], 404);

  } catch (\Exception $exception) {
    
      return response()->json([
          'error' => 'Users Not Found',
          'message' => $exception->getMessage(),
      ], 500);
  }
  
 }

 public function getUser(int $id){
    try{

      return $this->userRepository->findById($id);

    }catch (UserNotFoundException $exception) {

      return response()->json([
          'error' => 'User Not Found',
          'message' => $exception->getMessage(),
      ], 404);

    } catch (\Exception $exception) {
      
        return response()->json([
            'error' => 'User Not Found',
            'message' => $exception->getMessage(),
        ], 500);
    }
 }

 public function existsById(int $id){
  try{
      return $this->userRepository->existsById($id);
  
  }catch (UserNotFoundException $exception) {

    return response()->json([
        'error' => 'User Does not Exist',
        'message' => $exception->getMessage(),
    ], 404);

  } catch (\Exception $exception) {
    
      return response()->json([
          'error' => 'User Not Found',
          'message' => $exception->getMessage(),
      ], 500);
  }
 }
}
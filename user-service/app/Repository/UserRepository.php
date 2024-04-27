<?php

namespace App\Repository;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\UserRequest;




class UserRepository
{

 public function save(array $User) : User
 {
   return User::create($User);
 }

 public function update(int $id, UserRequest $request) : bool
 {
   $User = $this->findById($id);
   return $User->update($request->all());
 }

 public function findById(int $id) : User
 {
   return User::findOrFail($id);
 }

 public function existsById(int $id) : bool
 {
  return User::where('id', $id)->exists();
 }

 public function findAll() : User|Collection|array
 {
  return User::all();
 }
}
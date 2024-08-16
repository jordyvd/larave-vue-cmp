<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DTO\Users\StoreUserDTO;
use App\DTO\Users\GetUsersDTO;
use App\DTO\Users\UpdateUserDTO;
use App\Services\UsersServices;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    private $usersServices;

    public function __construct()
    {
        $this->usersServices = new UsersServices();
    }

    public function get(Request $request)
    {
        $getUsersDTO = new GetUsersDTO($request->all());
        $data = $this->usersServices->get($getUsersDTO);
        return $this->response($data, 'Users retrieved', 200);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $storeUserDTO = new StoreUserDTO($request->all());
            $this->usersServices->store($storeUserDTO);
            DB::commit();
            return $this->response([], 'User created', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response([], $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $updateUserDTO = new UpdateUserDTO($request->all(), $id);
            $this->usersServices->update($updateUserDTO, $id);
            DB::commit();
            return $this->response([], 'User updated', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response([], $e->getMessage(), 500);
        }
    }

    public function getRoles()
    {
        $roles = $this->usersServices->getRoles();
        return $this->response($roles, 'Roles retrieved', 200);
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $this->usersServices->delete($id);
            DB::commit();
            return $this->response([], 'User deleted', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response([], $e->getMessage(), 500);
        }
    }
}

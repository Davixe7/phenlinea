<?php

namespace App\Http\Controllers\Admin\v2;

use App\Admin;
use App\Http\Requests\v2\StoreAdmin as StoreAdminRequest;
use App\Http\Resources\Admin as AdminResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
  public function index()
  {
    $admins = Admin::orderBy('name', 'asc')->get();
    return AdminResource::collection($admins);
  }

  public function store(StoreAdminRequest $request)
  {
    $data = $request->validated();
    $data['password'] = bcrypt($request->password);
    $admin = Admin::create($data);

    return new AdminResource($admin);
  }

  public function show(Admin $admin)
  {
    return new AdminResource($admin);
  }

  public function update(Request $request, Admin $admin)
  {
    $request->validate([
      'name'     => 'required|min:4|max:192',
      'nit'      => 'required|min:10|max:10|unique:admins,nit,' . $admin->id,
      'email'    => 'required|unique:admins,email,' . $admin->id,
      'address'  => 'required',
      'phone'    => 'required|min:8|max:10',
      'password' => 'nullable|min:6|max:16',
    ]);

    $data = $request->all();
    $data['password'] = $request->password ? bcrypt($request->password) : $admin->password;
    $admin->update($data);

    return new AdminResource($admin);
  }

  public function destroy(Admin $admin)
  {
    $admin->delete();
    return response()->json([], 204);
  }
}

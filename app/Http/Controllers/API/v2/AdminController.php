<?php

namespace App\Http\Controllers\API\v2;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Admin as AdminResource;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = Admin::all();
        return AdminResource::collection($admins);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required',
            'nit'      => 'required',
            'address'  => 'required',
            'phone'    => 'required|digits:10',
            'email'    => 'required|unique:admins',
            'password' => 'required|min:8',
            'phone_2'  => 'nullable',
            'phone_3'  => 'nullable',
            'phone_4'  => 'nullable',
            'contact_email' => 'nullable',
            'device_serial_number' => 'nullable',
            'device_2_serial_number' => 'nullable',
            'device_community_id' => 'nullable',
            'device_building_id' => 'nullable',
        ]);

        $admin = Admin::create($data);

        return new AdminResource($admin);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        return new AdminResource($admin);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        $data = $request->validate([
            'name'                   => 'sometimes|string|min:6',
            'nit'                    => 'sometimes|digits:10',
            'address'                => 'sometimes|string|min:6',
            'phone'                  => 'sometimes|digits:10',
            'email'                  => "sometimes|email|unique:admins,email,{$admin->id}",
            'password'               => 'sometimes|string|min:8',
            'phone_2'                => 'nullable',
            'phone_3'                => 'nullable',
            'phone_4'                => 'nullable',
            'contact_email'          => 'nullable',
            'device_serial_number'   => 'nullable',
            'device_2_serial_number' => 'nullable',
            'device_community_id'    => 'nullable',
            'device_building_id'     => 'nullable',
        ]);

        if( $request->filled('password') ){
            $data['password'] = bcrypt($request->password);
        }

        $admin->update($data);
        return new AdminResource($admin);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return response()->json([], 204);
    }
}

<?php

namespace App\Http\Controllers\API\v1\admin;

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
        $data = Admin::all();
        return AdminResource::collection($data);
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
            'phone'    => 'required',
            'address'  => 'required',
            'email'    => 'required',
            'password' => 'required',
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
        $request->validate([
            'nit' => 'required|unique:admins,nit,' . $admin->id
        ]);

        $data = $request->all();
        $data['password'] = $request->password ? bcrypt($request->password) : $admin->password;
        $admin->update($data);

        foreach(['whatsapp_qr', 'picture'] as $key){
            if(!$request->hasFile($key)){ continue; }
            $admin->addMediaFromRequest($key)->toMediaCollection($key);
        }
        
        return new AdminResource( $admin );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }
}

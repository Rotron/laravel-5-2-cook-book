<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;
use Auth;
use Session;
use Redirect;
use Hash;
use Validator;


class AdminsController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //Get item by ID
        $admins = App\Admin::all();

        return view('admin.admins.index', ['admins' => $admins]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $save = new App\Admin;

        $save->name=$request->input('name');
        $save->email=$request->input('email');
        $save->password = Hash::make($request->password);


        if($save->save()){
            Session::flash('message', 'Admin registred successfully');
            Session::flash('message_type', 'success');
            return redirect::to('/admin/admins');
        }
        else {
            Session::flash('message', 'Error while saving admin');
            Session::flash('message_type', 'danger');
            return redirect::to('/admin/admins');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id){

        if(!$delete = App\Admin::find($id)) {
            abort(404);
        }


        if ($delete->delete()) {
            Session::flash('message', 'File deleted successfully');
            Session::flash('message_type', 'success');
            return redirect::to('/admin/admins');
        } else {
            Session::flash('message', 'Error while deleting file');
            Session::flash('message_type', 'danger');
            return redirect::to('/admin/admins');

        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id){

        if(!$admin = App\Admin::find($id)) {
            abort(404);
        }

        return view('admin.admins.edit', ['admin' => $admin]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id){

        if(!$update = App\Admin::find($id)) {
            abort(404);
        }

        $update->name=$request->input('name');
        $update->email=$request->input('email');


        if($update->save()){
            Session::flash('message', 'Profile updated successfully');
            Session::flash('message_type', 'success');
            return redirect::to('/admin/admins');
        }
        else {
            Session::flash('message', 'Error while updating profile');
            Session::flash('message_type', 'danger');
            return redirect::to('/admin/admins');


        }

    }

    public function editPassword($id){

        if(!$admin = App\Admin::find($id)) {
            abort(404);
        }

        return view('admin.admins.password', ['admin' => $admin]);

    }

    public function updatePassword(Request $request, $id){

        if(!$update = App\Admin::find(Auth::guard('admin')->user()->id)) {
            abort(404);
        }

        $update->password = Hash::make($request->password);

        if($update->save()){
            Session::flash('message', 'Password updated successfully');
            Session::flash('message_type', 'success');
            return redirect::to('/admin/admins');
        }
        else{
            Session::flash('message', 'Error while updating');
            Session::flash('message_type', 'danger');
            return redirect::to('/admin/admins');
        }

    }


}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use function redirect;
use function view;

class EmployeeController extends Controller
{
    public function list(){
        $employees = User::orderBy('id','desc')->get();
        return view('admin.pages.Employee.employee-list',compact('employees'));
    }

    public function create(){
        $roles = Role::select('id','name')->orderBy('id','desc')->get();
        return view('admin.pages.Employee.create-employee',compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email',
            'address'   => 'required',
            'phone'     => 'required|max:16|min:11',
            'role_id'   => 'required',
        ]);
       try{
           User::create([
               'name'      =>$request->name,
               'email'     =>$request->email,
               'address'   =>$request->address,
               'phone'     =>$request->phone,
               'role_id'   =>$request->role_id,
               'password'  =>bcrypt($request->password),
           ]);
           return redirect()->route('user.list')->with('success','User Created successfully');

       }catch (\Throwable $throwable){
           return redirect()->route('user.list')->with('error','something went wrong');

       }
    }


    public function edit($id){
        $roles = Role::select('id','name')->orderBy('id','desc')->get();
        $employee = User::find($id);
        return view('admin.pages.Employee.edit',compact('roles','employee'));
    }


    public function update(Request $request,$id): RedirectResponse
    {
        $employee = User::find($id);
        try {
        $employee->update([
            'name'      =>$request->name,
            'email'     =>$request->email,
            'address'   =>$request->address,
            'phone'     =>$request->phone,
            'role_id'   =>$request->role_id,
        ]);
            return redirect()->route('user.list')->with('success','User updated successfully');
        }catch (\Throwable $throwable){
            return redirect()->route('user.list')->with('error','something went wrong');
        }
    }


    public function delete($id)
    {
        $employee = User::find($id)->delete();
        return redirect()->route('user.list')->with('success','User deleted successfully');

    }
}

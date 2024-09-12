<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminDashboard(){
        return view('admin.index');
    }

    public function AdminProfile(){
        $id = Auth::User()->id;
        $profiledata = User::find($id);
        $roles = Auth::user();

        return view('admin.profile_view' , compact('profiledata'));
    }

    public function AdminProfileUpdate(Request $request) {
        $data = $request->validate([
            'name' => 'required',
            'user_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'phone' => 'required',
            // 'role' => 'required|in:admin,user,agent', // Add role validation
        ]);
    
        if($request->file('photo')) {
            $file = $request->file('photo');
            $fileName = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('image/admin_images'), $fileName);
            $data['photo'] = $fileName; 
        }
    
        // Get the authenticated user
        $user = auth()->user();
        $user->update($data);
    
        return response()->json(['status' => 'success', 'message' => 'Profile updated successfully!']);
    }

   public function AdminChangepassword(){
    return view('admin.admin_change_pass');
   }

   public function AdminUpdatePassword(Request $request){
    $request->validate([
        'old_password' => 'required',
        'new_password' => 'required|confirmed'
    ]);

    //match the old password
    if(!Hash::check($request->old_password , Auth::user()->password)){

      return back()->with('error', 'password Old Does Not Match!');
    }
    
    //Update the new password

    User::whereId(auth()->user()->id)->update([
        'password' => Hash::make($request->new_password)
    ]);

    return back()->with('success', 'password changed successfully!');
   }
    
}

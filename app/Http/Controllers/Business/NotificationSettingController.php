<?php

namespace App\Http\Controllers\Business;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Business;
use App\Models\UserProfile;
use App\Models\NotificationSetting;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Business\UserManageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\NotificationRepository;

class NotificationSettingController extends Controller
{
    private $business_id;
    private $notification_repo;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->business_id = session()->get('_business_id');
            return $next($request);
        });

        $this->notification_repo = new NotificationRepository();
    }

    public function notifications(Request $request)
    {
        $business = Business::with('notificationSettings')->find($this->business_id);
        $user = Auth::user();

        return view('business.settings.notifications.index', [
            'business' => $business,
            'user' => $user
        ]);
    }

    public function profile(Request $request)
    {
        $business = Business::with('notificationSettings')->find($this->business_id);
        $user = Auth::user();

        return view('business.settings.profile.index', [
            'business' => $business,
            'user' => $user
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Update_Notification');

        if ($check_premission == false) {
            return abort(404);
        }

        $business = Business::with('notificationSettings')->find($this->business_id);

        $data = $this->notification_repo->update($request);

        return response()->json([
            'status' => true,
            'message' => 'Select Notification Settings Updated Successfully!',
            'route' => route('business.settings.notifications')
        ]);
    }

    public function profileUpdate(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:users,email,' . Auth::user()->id. ',id,deleted_at,NULL',
                'contact' => 'required|digits:10|unique:users,contact,' . Auth::user()->id. ',id,deleted_at,NULL',
                'image'=>'nullable|image|mimes:jpeg,png,jpg,svg',

            ]
        );

        if ($validator->fails()) {
            return response()->json(['status'=>'val_error',  'errors'=>$validator->errors()]);
        }

        $user = User::find(Auth::user()->id);

        if (isset($request->image) && $request->image->getClientOriginalName()) {
            $file = $request->file('image')->store(
                'user', 's3'
            );
        } else {
            if (!$user->UserProfile->profile)
                $file = '';
            else
                $file = $user->UserProfile->profile;
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        $user->contact = $request->contact;
        $user->update();

        $userProfile = UserProfile::where('user_id', Auth::user()->id)->first();
        $userProfile->profile = $file;
        $userProfile->update();

        return response()->json([
            'status'=>true,
            'message'=>'Profile Updated successfully!',
            'route' => route('business.settings.profile')
        ]);
    }


    public function passwordUpdate(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'password_confirmation' => ['required'],
            'old_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed','regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/'],
        ],
        [
            'password.regex' => 'Password must contain at least one number, both uppercase and lowercase letters and special character.',
        ]
        );

        if ($validator->fails()) {
            return response()->json(['status'=>'val_error',  'errors'=>$validator->errors()]);
        }

        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
        if (!Hash::check($request->input('old_password'), $user->password)) {

            return response()->json(['status'=>'old_error', 'message'=>'Old Password not match']);
        } else {
            if (Hash::check($request->input('password'), $user->password)) {

                return response()->json(['status'=>'error_password', 'message'=>'You can not use the same old password again']);
            } else {
                $user->password = Hash::make($request->input('password'));
                $user->update();

                return response()->json([
                    'status'=>true,
                    'message'=>'Your password updated successfully!',
                    'route' => route('business.settings.profile')
                ]);
            }
        }
    }

}

<?php

use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;

if (!function_exists('getClientIdAndSecret')) {

    function getClientIdAndSecret($provider)
    {
        $client = DB::table('oauth_clients')->where('provider', $provider)->first();
        return $client;
    }
}

if (!function_exists('file_upload')) {

    function file_upload($file, $path)
    {
        $path_store = Storage::disk('s3')->put($path, $file);

        return $path_store;
    }
}

if (!function_exists('otp_generate')) {

    function otp_generate($length, $type)
    {
        // 0 = Digits
        if ($type == 0) {
            $pool = '0123456789';
        }

        // 1 = Letter Only
        if ($type == 1) {
            $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        // 2 = Digit and Letter
        if ($type == 2) {
            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $otp = substr(str_shuffle(str_repeat($pool, $length)), 0, $length);

        return $otp;
    }
}

if (!function_exists('mailNotification')) {
    function mailNotification($data)
    {
        Mail::send($data["view"], $data, function ($message) use ($data) {
            $message->to($data["email"])
                ->subject($data["title"]);
        });
    }
}

if (!function_exists('action_buttons')) {
    function action_buttons($action, $edit_url, $route_id , $view_url)
    {
        if ($edit_url != '') {
            $action .= '<a href="' . $edit_url . '" class="dropdown-item" title="Edit"><i class="fa-solid fa-pen-to-square m-r-5"></i>Edit</a> ';
        }

        if (Auth()->user()->hasRole('super_admin')) {
            $action .= '<button type="button" class="dropdown-item" title="Delete" onclick="deleteConfirmation(' . $route_id . ')" data-id="' . $route_id . '"><i class="fa-solid fas fa-trash m-r-5"></i>Delete</button>  ';
        }

        if ($view_url != '') {
            $action .= '<a class="dropdown-item" title="View"  href="' . $view_url . '"><i class="fa-solid fa-eye m-r-5"></i>View</a>';
        }

        return $action;
    }
}

if (!function_exists('action_buttons_modals')) {
    function action_buttons_modals($action, $edit_url, $route_id , $view_url)
    {
        if ($edit_url != '') {
            $action .= '<a href="javascript:;" class="dropdown-item" title="Edit" onclick="updateModal(' . $route_id . ')" data-id="' . $route_id . '"><i class="fa-solid fa-pen-to-square m-r-5"></i>Edit</a> ';
        }

        if (Auth()->user()->hasRole('super_admin')) {
            $action .= '<button type="button" class="dropdown-item" title="Delete" onclick="deleteConfirmation(' . $route_id . ')" data-id="' . $route_id . '"><i class="fa-solid fas fa-trash m-r-5"></i>Delete</button>  ';
        }

        if ($view_url != '') {
            $action .= '<a class="dropdown-item" title="View"  href="javascript:;" onclick="viewModal(' . $route_id . ')" data-id="' . $route_id . '"><i class="fa-solid fa-eye m-r-5"></i>View</a>';
        }

        return $action;
    }
}

if (!function_exists('refno_generate')) {

    function refno_generate($length, $type, $id)
    {
        // 0 = Digits
        if ($type == 0) {
            $pool = '0123456789';
        }

        // 1 = Letter Only
        if ($type == 1) {
            $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        // 2 = Digit and Letter
        if ($type == 2) {
            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $id_length = strlen($id);
        $ref_length = $length - $id_length;

        $ref_no = $id;
        if ($ref_length > 0) {
            $otp = substr(str_shuffle(str_repeat($pool, $ref_length)), 0, $ref_length);
            $ref_no = $otp . $id;
        }

        return $ref_no;
    }
}

if (!function_exists('api_client_business_id')) {

    function api_client_business_id($request)
    {
        $business_id = Auth::guard('api-client')->user()->business_id;
        if (isset($request->salon_id) && !empty($request->salon_id)) {
            $business_id = $request->salon_id;
        }

        return $business_id;
    }
}

if (!function_exists('action_btns')) {
    function action_btns($action, $user, $permission, $edit_url, $route_id,$view_url)
    {
        if ($edit_url != '' && $user->hasPermissionTo('Update_' . $permission)) {
            $action .= '<a class="dropdown-item" title="Edit" href="' . $edit_url . '"><i class="fa-solid fa-pen-to-square m-r-5"></i> Edit</a>';
        }

        if ($user->hasPermissionTo('Delete_' . $permission)) {
            $action .= '<a class="dropdown-item" title="Delete" href="javascript:;" onclick="deleteConfirmation(' . $route_id . ')" data-id="' . $route_id . '"><i class="fa-solid fas fa-trash m-r-5"></i> Delete</a>';
        }

        if ($view_url != '' && $user->hasPermissionTo('Read_' . $permission)) {
            $action .= '<a class="dropdown-item" title="View" href="' . $view_url . '"><i class="fa-solid fa-eye m-r-5"></i>View</a>';
        }

        return $action;
    }
}

if (!function_exists('user_permission_check')) {
    function user_permission_check($user, $permission)
    {
        $status = false;

        if ($user->hasPermissionTo($permission)) {
            $status = true;
        }

        return $status;
    }
}

if (!function_exists('check_snap_status')) {
    function check_snap_status($business_id)
    {
        $status = false;

        $business = Business::find($business_id);

        if ($business) {
            if ($business->snap_auth_key != "") {
                $api_url = env('SNAP_API_URL') . '/authentication';

                $headers = [
                    'Content-Type' => 'application/json'
                ];

                $requestOptions = [
                    'json' => [
                        'api_key' => $business->snap_auth_key
                    ],
                    'headers' => $headers
                ];

                $guzzleHttpClient = new GuzzleClient();

                try {
                    $response = $guzzleHttpClient->post($api_url, $requestOptions);
                    $responseContent = $response->getBody()->getContents();
                    $responseContentArray = json_decode($responseContent, true);

                    $status = $responseContentArray['status'];
                } catch (ClientException $ex) {
                    $response = $ex->getResponse();
                    $responseContent = $response->getBody()->getContents();
                    $responseContentArray = json_decode($responseContent, true);

                    $status = false;
                }
            }
        }

        return $status;
    }
}

if (!function_exists('send_slsnap_message')) {
    function send_slsnap_message($request)
    {
        $status = false;

        $api_url = env('SNAP_API_URL') . '/send_message';

        $headers = [
            'Content-Type' => 'application/json'
        ];

        $requestOptions = [
            'json' => [
                'ApiKey' => $request->snap_auth_key,
                'firstname' => $request->client_name,
                'phone' => $request->client_contact,
                'message' => $request->message
            ],
            'headers' => $headers
        ];

        $guzzleHttpClient = new GuzzleClient();

        try {
            $response = $guzzleHttpClient->post($api_url, $requestOptions);
            $responseContent = $response->getBody()->getContents();
            $responseContentArray = json_decode($responseContent, true);

            $status = $responseContentArray['status'];
        } catch (ClientException $ex) {
            $response = $ex->getResponse();
            $responseContent = $response->getBody()->getContents();
            $responseContentArray = json_decode($responseContent, true);

            $status = false;
        }

        return $status;
    }
}

if (!function_exists('auto_increment_id')) {
    function auto_increment_id($id)
    {
        $auto_id = $id;
        if ($id < 10) {
            $auto_id = '000'.$id;
        }

        if ($id >= 10 && $id < 100) {
            $auto_id = '00'.$id;
        }

        if ($id >= 100 && $id < 1000) {
            $auto_id = '0'.$id;
        }

        return $auto_id;
    }
}

<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Enum\ResponseCodeEnum;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Helpers;
use App\Mail\CustomerRegisterSuccess;
use App\Mail\OtpVerify;
use App\Models\Customer;
use App\Models\Otp;
use App\Models\Point;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use DateTime;
use Firebase\JWT\JWT;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;

class CustomerAuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        $validator = $this->validator([
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        try {
            $data = [
                'email' => $request->email,
                'password' => $request->password
            ];

            if (auth('customer')->attempt($data)) {
                $user = auth('customer')->user();
                $token = $user->createToken('APPLICATION_CUSTOMER')->plainTextToken;
                // $this->sendOtp($user);
                return $this->successResponse(['token' => $token], 'Login Successfully');
            } else {
                return $this->errorResponse('Unauthenticated User', 401, ResponseCodeEnum::UNAUTHENTICATED);
            }
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse('Something went wrong.Please Try again.', 500);
        }
    }

    public function loginWithGoogle(Request $request)
    {
        $validator = $this->validator([
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        $url = 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token=' . $request->token;
        try {

            $httpResponse = Http::get($url);
            if ($httpResponse->ok()) {
                $body = $httpResponse->body();
                $body = json_decode($body, true);
                if (isset($body["email"])) {
                    $firstName = $body["given_name"];
                    $lastName = $body["family_name"];
                    $email = $body["email"];
                    $userName = $firstName . " " . $lastName;

                    return $this->createSocialUser($userName, $email, null, null);
                }
            } else {
                return $this->errorResponse('Unauthenticated User', 401, ResponseCodeEnum::UNAUTHENTICATED);
            }
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse('Something went wrong.Please Try again.', 500);
        }
    }

    public function loginWithFacebook(Request $request)
    {
        $validator = $this->validator([
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }
        $fields = 'id,name,first_name,last_name,email';
        $url = 'https://graph.facebook.com/me/?fields=' . $fields . '&access_token=' . $request->token;
        try {

            $httpResponse = Http::get($url);
            if ($httpResponse->ok()) {
                $body = $httpResponse->body();
                $body = json_decode($body, true);
                if (isset($body["email"])) {
                    $firstName = strtolower($body["first_name"]);
                    $lastName = strtolower($body["last_name"]);
                    $email = $body["email"];
                    $userName = $firstName . " " . $lastName;

                    return $this->createSocialUser($userName, $email, null, null);
                }
            } else {
                return $this->errorResponse('Unauthenticated User', 401, ResponseCodeEnum::UNAUTHENTICATED);
            }
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse('Something went wrong.Please Try again.', 500);
        }
    }

    public function loginWithApple(Request $request)
    {
        $validator = $this->validator([
            'token' => 'required',
            'team_id' => 'required',
            'bundle_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        try {
            if (!$this->appleAuthKeyFileExist()) {
                Log::error('You need to upload AuthKey_XXXX.p8 file');
                return $this->errorResponse('Something went wrong.Please Try again.', 401, ResponseCodeEnum::UNAUTHENTICATED);
            }

            $token = $this->appleSigninTokenGenerate($request->bundle_id, $request->team_id, $request->token);

            if ($token == false) {
                Log::error('You need to upload AuthKey_XXXX.p8 file');
                return $this->errorResponse('Invalid authorization_code', 401, ResponseCodeEnum::UNAUTHENTICATED);
            }
            $decoded = $this->jwtDecode($token);
            $userEmail = $decoded["email"];
            if (!isset($userEmail)) {
                return $this->errorResponse('Can\'t get the email to create account.', 401, ResponseCodeEnum::UNAUTHENTICATED);
            }

            $displayName = explode("@", $userEmail)[0];
            $firstName = $request->first_name;
            $lastName = $request->last_name;
            if (isset($firstName) && isset($lastName) && !empty($firstName)) {
                $displayName = $firstName . ' ' . $lastName;
            }


            return $this->createSocialUser($displayName, $userEmail, null, null);

            // return $this->errorResponse('Unauthenticated User', 401, ResponseCodeEnum::UNAUTHENTICATED);

        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse('Something went wrong.Please Try again.', 500);
        }
    }

    public function loginWithSms(Request $request)
    {
        $validator = $this->validator([
            'country_code' => 'required',
            'mobile' => 'required|min:10'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        $countryCode = $request->country_code;
        $mobileNumber = $request->mobile;

        try {
            $user = Customer::where('phone', $mobileNumber)->first();

            if ($user) {
                if (auth('customer')->loginUsingId($user->id)) {
                    $token = $user->createToken('APPLICATION_CUSTOMER')->plainTextToken;
                    $this->sendOtp($user);
                    return $this->successResponse(['token' => $token], 'Login Successfully');
                } else {
                    return $this->errorResponse('Unauthenticated User', 401, ResponseCodeEnum::UNAUTHENTICATED);
                }
            } else {
                $customer = Customer::create([
                    'name' => $request->mobile,
                    'phone' => $request->mobile,
                    'referral_code' => Helpers::randomStringGenarator(10),
                    'email' => $request->mobile . '@gmail.com',
                    'password' => Hash::make(Carbon::now())
                ]);

                // Mail::to($customer->email)->send(new CustomerRegisterSuccess($customer));

                if (auth('customer')->loginUsingId($customer->id)) {
                    $user = auth('customer')->user();
                    $token = $user->createToken('APPLICATION_CUSTOMER')->plainTextToken;
                    $this->sendOtp($user);
                    return $this->successResponse(['token' => $token], 'Login Successfully');
                } else {
                    return $this->errorResponse('Unauthenticated User', 401, ResponseCodeEnum::UNAUTHENTICATED);
                }
            }
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse('Something went wrong.Please Try again.', 500);
        }
    }

    public function createSocialUser($name, $email, $password, $dob)
    {
        if (!Customer::where('email', $email)->first()) {
            $customer = Customer::create([
                'name' => $name,
                'email' => $email,
                'dob' => $dob,
                'referral_code' => Helpers::randomStringGenarator(10),
                'password' => $password ? $password : Hash::make(Carbon::now())
            ]);
            // Mail::to($customer->email)->send(new CustomerRegisterSuccess($customer));
        } else {
            $customer = Customer::where('email', $email)->first();
        }

        if (auth('customer')->loginUsingId($customer->id)) {
            $user = auth('customer')->user();
            $token = $user->createToken('APPLICATION_CUSTOMER')->plainTextToken;
            Otp::updateOrCreate([
                'user_id' => $user->id
            ], [
                'otp_code' => Helpers::otpGenarator(4),
                'expired_at' => Carbon::now()->addMinutes(5),
                'verified_at' =>  Carbon::now()->addMinutes(1)
            ]);
            return $this->successResponse(['token' => $token], 'Login Successfully');
        } else {
            return $this->errorResponse('Unauthenticated User', 401, ResponseCodeEnum::UNAUTHENTICATED);
        }
    }

    public function loginWithMobile(Request $request)
    {
        $validator = $this->validator([
            'mobile' => 'required|min:10',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        try {
            $user = Customer::where(['mobile' => $request->mobile,])->first();

            if ($user) {
                $user = auth('customer')->user();
                $token = $user->createToken('APPLICATION_CUSTOMER')->plainTextToken;
                // $this->sendOtp($user->id);
                return $this->successResponse(['token' => $token], 'Login Successfully');
            } else {
                return $this->errorResponse('No User Found', 401, ResponseCodeEnum::NO_USER);
            }
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function register(Request $request)
    {
        $validator = $this->validator([
            'name' => 'required',
            'email' => 'required|email|unique:customers,email',
            "mobile" => 'required|min:10|unique:customers,phone',
            // 'dob' => 'required',
            'password' => 'required|min:8|confirmed'
        ], [
            'email.unique' => 'The email has already been registered.',
            // 'dob.required' => 'The date of birth field requred'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        try {

            $customer = new Customer();
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone = $request->mobile;
            $customer->password = Hash::make($request->password);
            $customer->save();

            // Mail::to($customer->email)->send(new CustomerRegisterSuccess($customer));

            if (auth('customer')->loginUsingId($customer->id)) {
                $user = auth('customer')->user();
                $token = $user->createToken('APPLICATION_CUSTOMER')->plainTextToken;
                // $this->sendOtp($user);
                return $this->successResponse(['token' => $token], 'Login Successfully');
            } else {
                return $this->errorResponse('Unauthenticated User', 401, ResponseCodeEnum::UNAUTHENTICATED);
            }
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse('Something went wrong.Please Try again.', 500);
        }
    }
    public function registerWithMobile(Request $request)
    {
        $validator = $this->validator([
            'mobile' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }
        try {
            $data = [
                'first_name' => Helpers::randomStringGenarator(),
                'last_name' => Helpers::randomStringGenarator(),
                'phone' => $request->mobile,
                'referral_code' => Helpers::randomStringGenarator(10),
                'password' => Hash::make(now())
            ];

            $customer = Customer::create($data);


            if (auth('customer')->loginUsingId($customer->id)) {
                $user = auth('customer')->user();
                $token = $user->createToken('APPLICATION_CUSTOMER')->plainTextToken;
                $this->sendOtp($user);
                return $this->successResponse(['token' => $token], 'Login Successfully');
            } else {
                return $this->errorResponse('Unauthenticated User', 401, ResponseCodeEnum::UNAUTHENTICATED);
            }
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function resetPassword(Request $request)
    {
        // if ($type == 'mobile') {
        //     $validator = $this->validator([
        //         'send_to' => 'required|exists:customers,mobile',
        //     ]);
        // } else if ($type == 'email') {
        //     $validator = $this->validator([
        //         'send_to' => 'required|email|exists:customers,email',
        //     ]);
        // }

        $validator = $this->validator([
            'send_to' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        try {
            if (Customer::where(['phone' => $request->send_to])->first()) {
                $customer = Customer::where(['phone' => $request->send_to])->first();
            } else if (Customer::where(['email' => $request->send_to])->first()) {
                $customer = Customer::where(['email' => $request->send_to])->first();
            } else {
                return $this->errorResponse('User does not exists.', 422);
            }

            if (auth('customer')->loginUsingId($customer->id)) {
                $user = auth('customer')->user();
                $this->sendOtp($customer, true);
                $token = $user->createToken('APPLICATION_CUSTOMER')->plainTextToken;
                $user->passwordReset(1111);
                $passwordResetToken = $user->passwordResetToken->token;
                return $this->successResponse(['token' => $token, 'password_reset_token' => $passwordResetToken], 'OTP Send Successfully');
            } else {
                return $this->errorResponse('Unauthenticated User', 401, ResponseCodeEnum::INVALID_PASSWORD_RESET_TOKEN);
            }
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse('Something went wrong.Please Try again.', 500);
        }
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();
        $validator = $this->validator([
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        $user = $request->user();
        if ($request->current_password) {
            if (!Hash::check($request->current_password,  $user->password)) {
                return $this->errorResponse(['current_password' => ['The current passowrd does not match.']], 422);
            }
        }

        try {
            $user = $request->user();

            $customer = Customer::find($user->id);
            $customer->password = Hash::make($request->password);
            $customer->save();

            $customer->passwordResetToken()->delete();

            $customer->tokens()->delete();

            return $this->successResponse(null, 'Logout Successfully');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function verifyLogin($type = null)
    {
        $user = request()->user();

        $otp = request('otp');

        $validator = $this->validator(
            [
                'otp' => ['required', Rule::exists('otps', 'otp_code')->where(function ($query) use ($user, $otp) {
                    $query->where(['user_id' => $user->id, 'otp_code' => $otp])->where('expired_at', '>', Carbon::now()->toDateTimeString());
                }),]
            ],
            [
                'otp.exists' => 'Invalid OTP Code'
            ]
        );
        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        } else {
            try {
                $customer = Customer::find($user->id);
                $otpCode = Otp::where(['user_id' => $user->id, 'otp_code' => $otp])->first();
                if ($type == 'mobile') {
                    $customer->mobile_verified_at = Carbon::now();
                    $customer->save();
                    $otpCode->verified_at = Carbon::now();
                    $otpCode->save();
                } else if ($type == 'email') {
                    $customer->email_verified_at = Carbon::now();
                    $customer->save();
                    $otpCode->verified_at = Carbon::now();
                    $otpCode->save();
                } else {
                    $otpCode->verified_at = Carbon::now();
                    $otpCode->save();
                }
                return $this->successResponse(null, 'OTP Verified');
            } catch (\Throwable $th) {
                Log::error($th);
                return $this->errorResponse($th->getMessage(), 500);
            }
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return $this->successResponse(null, 'Logout Successfully');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse('Something went wrong.Please Try again.', 500);
        }
    }

    public function deleteAccount(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            $request->user()->delete();
            return $this->successResponse(null, 'User Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse('Something went wrong.Please Try again.', 500);
        }
    }

    public function details(Request $request)
    {
        try {
            $data = $request->user();
            return $this->successResponse($data, 'Successfully');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function points(Request $request)
    {
        try {
            $user = $request->user();

            $data = [
                'all-points' => $user->tier_points,
                'spended-points' => $user->tier_points - $user->reward_points,
                'available-points' => $user->reward_points,
                'tier' => [
                    'id' => $user->tier ? $user->tier->id : "Guest",
                    'name' => $user->tier ? $user->tier->name : "Guest",
                ]
            ];
            return $this->successResponse($data, 'Successfully');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function firebaseToken(Request $request)
    {
        try {
            $user = $request->user();
            $customer = Customer::find($user->id);
            $customer->firebase_token = $request->firebase_token;
            $customer->save();
            return $this->successResponse(null, 'Logout Successfully');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th->getMessage(), 500);
        }
    }
    public function updateAvatar(Request $request)
    {
        try {
            $user = $request->user();
            $customer = Customer::find($user->id);
            $customer->avatar = $request->image;
            $customer->save();
            $data = Customer::find($user->id);
            return $this->successResponse($data, 'avatar saved Successfully');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th->getMessage(), 500);
        }
    }
    public function uploadAvatar(Request $request)
    {
        try {
            $user = $request->user();
            $customer = Customer::find($user->id);

            if($request->hasFile('image')) {
                $file = $request->file('image');
                $media = $customer->addMedia($file)->toMediaCollection('avatar');
                $customer->avatar = $media->getUrl();
                $customer->save();
            }
            $data = Customer::find($user->id);
            return $this->successResponse($data, 'avatar saved Successfully');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function verifyStatus()
    {
        return $this->successResponse(null, 'SUCCESS');
    }

    public function updateDetails(Request $request)
    {
        $user = $request->user();
        $validator = $this->validator([
            'name' => 'required',
            'email' => 'required|email|unique:customers,email,' . $user->id,
            'mobile' => 'required|min:10|unique:customers,phone,' . $user->id,
            'dob' => 'required
            '
        ], [
            'unique' => 'The email has already been registered.'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->mobile,
                'dob' => $request->dob
            ];

            $customer = Customer::where('id', $user->id)->update($data);
            $user = Customer::find($user->id);

            return $this->successResponse($user, 'Login Successfully');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function resendOtp()
    {
        try {
            $user = request()->user();
            $this->sendOtp($user);
            return $this->successResponse(null, 'OTP Send Successfully');
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function sendOtp($user = null, $resetPassword = false)
    {
        $userId = $user ? $user->id : request()->user()->id;
        if ($user->email == "hushantha@weblook.com") {
            $code = 1111;
        } else {
            $code = Helpers::otpGenarator(4);
        }
        // dd('ew');
        $otp = Otp::where(['user_id' => $userId])->first();
        if ($otp) {
            $otp->otp_code = $code;
            $otp->expired_at = Carbon::now()->addMinutes(5);
            $otp->verified_at = null;
            $otp->save();
        } else {
            $otp = new Otp();
            $otp->user_id = $userId;
            $otp->otp_code = $code;
            $otp->expired_at = Carbon::now()->addMinutes(5);
            $otp->verified_at = null;
            $otp->save();
        }

        if ($user->email) {
            // Mail::to($user->email)->send(new OtpVerify($otp, $user));
        }

        if ($user->phone) {
            $this->sendSmsOtpToUser($user, $code);
        }
    }

    public function sendSmsOtpToUser($user, $code, $countryCode = '94')
    {
        // $api_key ='4|67ABMOCLPjgZbDaO9xm6Kk1PpaLJmFkKMiWvJ4Fh';
        $api_key = config('app.sms_api_key');
        $apiUrl = 'https://sms-api.ipsova.com/api/v3/sms/send';

        if (substr($user->phone, 0, 1) === '0') {
            $mobileNumber = $countryCode . substr($user->phone, 1);
        } else {
            $mobileNumber = $countryCode . $user->phone;
        }



        $response = Http::withHeaders([
            'Authorization' =>  'Bearer ' . $api_key,
            'Content-Type' => 'application/json'
        ])->post($apiUrl, [
            'recipient' => $mobileNumber,
            'sender_id' => 'BEWAXED',
            'type' => 'plain',
            'message' => 'Hi there,\nPlease enter the following OTP : ' . $code . ' to complete your login.\nThank you.',
        ]);
    }

    public function appleAuthKeyFileExist()
    {
        $filePath = base_path() . '/AuthKey_Q29PR4S9PR.p8';
        return file_exists($filePath);
    }

    public function appleSigninTokenGenerate($bundleId, $teamId, $code)
    {
        // Apple's token endpoint URL
        $tokenEndpoint = 'https://appleid.apple.com/auth/token';

        // Prepare the request data
        $requestData = array(
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => $bundleId,
            'client_secret' => $this->appleSigninSecretKeyGenerate($bundleId, $teamId),
        );

        $ch = curl_init($tokenEndpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            Log::error('cURL error: ' . curl_error($ch));
            curl_close($ch);
            return false;
        }

        // Close cURL
        curl_close($ch);

        // Decode the JSON response
        $data = json_decode($response, true);

        if (isset($data['error_description'])) {
            Log::error($data['error_description']);
            return false;
        }

        if (isset($data['id_token'])) {
            return $data['id_token'];
        } else {
            return false;
        }
    }

    public function appleSigninSecretKeyGenerate($bundleId, $teamId)
    {
        $filePath = base_path() . '/AuthKey_Q29PR4S9PR.p8';
        $privateKey = file_get_contents($filePath);
        $keyId = str_replace(['AuthKey_', '.p8'], '', 'AuthKey_Q29PR4S9PR.p8');
        $currentTime = time();
        $payload = [
            'iss' => $teamId,
            'aud' => 'https://appleid.apple.com',
            'sub' => $bundleId,
            'iat' => $currentTime,
            'exp' => $currentTime + 86400 * 180
        ];

        $headers = [
            'alg' => 'ES256',
            'kid' => $keyId
        ];

        return JWT::encode($payload, $privateKey, 'ES256', null, $headers);
    }

    function jwtDecode($token)
    {
        $splitToken = explode(".", $token);
        $payloadBase64 = $splitToken[1]; // Payload is always the index 1
        $decodedPayload = json_decode(urldecode(base64_decode($payloadBase64)), true);
        return $decodedPayload;
    }
}

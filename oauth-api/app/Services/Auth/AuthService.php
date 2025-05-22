<?php 
declare(strict_types=1);

namespace App\Services\Auth;

use App\Services\User\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Repository\{
    User\UserRepository
};

class AuthService {
    public function __construct(
        protected UserRepository $userRepository,
        protected UserService $userService
    )
    {

    }

    public function sentOtp($requestData)
    {
        $user = $this->userRepository->findOrFailByPhone($requestData['phone']);

        if (!$user) {
            throw new \Exception('User not found');
        }
        $otp = rand(100000, 999999);
        $this->userRepository->updateByID($user->id, [
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(5)
        ]);

        $mobileNo = "0" . $user->phone;
        $sendData = [
            "sms" => [
                "send" => true,
                "mobile_number" => [$mobileNo],
                "message" => $otp . " is your otp number."
            ],
            "email" => [
                "send" => false
            ]
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://bsti-api.orangebd.com/notify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($sendData),
            CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        if(!empty($response)) {
            $response = json_decode($response, true);
            if(isset($response['data']['statusCode']) && $response['data']['statusCode'] == 200) {
                $res = [
                    "status" => "success",
                    "user_id" => $user->id
                ];
                return json_encode($res);
            }
        }
        return 0;
    }

    public function userRegistration($validateData)
    {
        $otp = rand(100000, 999999);
        return $this->userRepository->create($validateData + [
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(5)
        ]);
    }

    public function otpVerification($validateData)
    {
        $user = $this->userRepository->findOrFailByEmail($validateData['email']);

        if (!$user) {
            throw new \Exception('User not found');
        }

        if ($user->otp !== $validateData['otp']) {
            throw new \Exception('Invalid OTP');
        }

        if (now()->isAfter($user->otp_expires_at)) {
            throw new \Exception('OTP expired');
        }

        $this->userRepository->updateByID($user->id, [
            'otp' => null,
            'otp_expires_at' => null,
            'is_verified' => true,
        ]);

        return $user;
    }

    public function checkIsVerified($user)
    {
        if ($user->is_verified) {
            return true;
        } else {
            return false;
        }
    }
    
    public function userInformation()
    {
        return Auth::user();
    }

    public function getUserInformationUsingAccessToken($requestData)
    {
        $accessToken = $requestData->attributes->get('access_token');
        // Find the user associated with the access token
        $user = DB::table('oauth_access_tokens as oat')
            ->where('oat.id', $accessToken)
            ->where('oat.revoked', false)
            ->join('users as u', 'oat.user_id', '=', 'u.id')
            ->select('u.*','oat.expires_at')
            ->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid Access Token'], 401);
        }

        if (now()->isAfter($user->expires_at)) {
            return response()->json(['error' => 'Access Token Expired'], 401);
        }
        return $user;
    }

    public function changePassword(array $requestData)
    {
        $user = User::find($requestData['user_id']);

        // Check current password
        if (!Hash::check($requestData['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Current password is incorrect.'],
            ]);
        }

        // Update password
        //$user->password = Hash::make($requestData['new_password']);
        $user->password = $requestData['new_password'];
        $user->save();

        return $user;
    }

    public function setPassword($requestData)
    {
        $user = $this->userRepository->findOrFailByEmail($requestData['email']);

        if (!$user) {
            throw new \Exception('User not found');
        } else {
            $this->userRepository->updateByID($user->id, [
                'password' => $requestData['password'],
            ]);
        }
        return $user;
    }

    public function setNewPassword($requestData)
    {
        $user = $this->userRepository->findOrFailByID($requestData['user_id']);

        if (!$user) {
            throw new \Exception('User not found');
        } else {
            if($user->otp == $requestData['otp'] && ($user->otp_expires_at > now())) {
                $this->userRepository->updateByID($user->id, [
                    'password' => $requestData['password'],
                ]);
                return $user;    
            }
            
        }
        
    }

    public function mobileOtpVerify($requestData)
    {
        $user = $this->userRepository->findOrFailByID($requestData['user_id']);
        
        if(!empty($user)) {
            if($user->otp == $requestData['otp'] && ($user->otp_expires_at > now())) {
                $res = [
                    "otp_verified" => "success",
                    "user_id" => $user->id,
                    "otp"=> $user->otp
                ];
                return json_encode($res);
            }
            else {
                $res = [
                    "otp_verified" => "failed",
                    "user_id" => $user->id
                ];
                return json_encode($res);
            }
        }
        return 0;    
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Auth\GenericUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SsoCallbackController extends Controller
{
    public function callback(Request $request)
    {
        try {
            if (!$request->has('token')) {
                throw new Exception('Token tidak ditemukan');
            }

            $token = $request->query('token');
            
            $decoded = JWT::decode(
                $token, 
                new Key(env('SSO_CLIENT_SECRET'), 'HS256')
            );

            if ($decoded->exp < time()) {
                throw new Exception('Token telah expired');
            }

            if (!isset($decoded->user->email) || !isset($decoded->user->name)) {
                throw new Exception('Data user tidak lengkap');
            }


            $userData = [
                'name' => $decoded->user->name,
                'email' => $decoded->user->email,
                'sso_id' => $decoded->user->id ?? null,
                'email_verified_at' => now(),
                'last_login_at' => now(),
                'sso_data' => json_encode([
                    'provider' => 'your_sso_provider',
                    'raw_data' => $decoded->user
                ])
            ];

            $user = DB::transaction(function () use ($decoded, $userData) {
                $user = User::where('email', $decoded->user->email)->first();

                if (!$user) {
                    $user = User::create($userData);
                    Log::info('New SSO user created', ['email' => $user->email]);
                } else {
                    $user->update($userData);
                    Log::info('Existing SSO user updated', ['email' => $user->email]);
                }

                return $user;
            });

            Auth::login($user);

            Log::info('SSO login successful', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return redirect()
                ->intended('/dashboard')
                ->with('success', 'Login berhasil!');

        } catch (Exception $e) {
            Log::error('SSO login failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('home')
                ->with('error', 'Gagal login: ' . $e->getMessage());
        }
    }
}

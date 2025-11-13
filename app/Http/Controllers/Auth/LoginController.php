<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            $accessToken = $facebookUser->token;

            // Get Facebook Pages
            $pagesResponse = Http::get('https://graph.facebook.com/v20.0/me/accounts', [
                'access_token' => $accessToken,
                'fields' => 'id,name,access_token,instagram_business_account{id,username}'
            ]);

            if ($pagesResponse->failed()) {
                return redirect('/')->with('error', 'Failed to fetch pages.');
            }

            $pages = $pagesResponse->json()['data'];
            $igUser = null;

            // Find page with Instagram Business Account
            foreach ($pages as $page) {
                if (isset($page['instagram_business_account'])) {
                    $igUser = $page['instagram_business_account'];
                    $pageAccessToken = $page['access_token']; // Page-specific token for API calls
                    break;
                }
            }

            if (!$igUser) {
                return redirect('/')->with('error', 'No Instagram Business Account linked to your Facebook Page.');
            }

            $igUserId = $igUser['id'];

            // Get Profile Details
            $profileResponse = Http::get("https://graph.facebook.com/v20.0/{$igUserId}", [
                'access_token' => $pageAccessToken,
                'fields' => 'id,username,name,account_type,media_count,profile_picture_url,website,bio'
            ]);

            if ($profileResponse->failed()) {
                return redirect('/')->with('error', 'Failed to fetch profile.');
            }

            $profile = $profileResponse->json();

            // Get Media (Feeds)
            $mediaResponse = Http::get("https://graph.facebook.com/v20.0/{$igUserId}/media", [
                'access_token' => $pageAccessToken,
                'fields' => 'id,caption,media_type,media_url,permalink,thumbnail_url,username,timestamp,children{id,media_type,media_url}',
                'limit' => 10 // Limit for demo
            ]);

            if ($mediaResponse->failed()) {
                return redirect('/')->with('error', 'Failed to fetch media.');
            }

            $media = $mediaResponse->json()['data'] ?? [];

            // Store in Session
            Session::put('access_token', $pageAccessToken);
            Session::put('ig_user_id', $igUserId);
            Session::put('profile', $profile);
            Session::put('media', $media);

            return redirect('/dashboard');

        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Authentication failed: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect('/')->with('success', 'Logged out successfully.');
    }
}

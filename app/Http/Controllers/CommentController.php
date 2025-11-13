<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CommentController extends Controller
{
    public function store(Request $request, $mediaId)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $accessToken = Session::get('access_token');
        $igUserId = Session::get('ig_user_id');

        if (!$accessToken || !$igUserId) {
            return redirect('/dashboard')->with('error', 'Session expired. Please login again.');
        }

        $response = Http::post("https://graph.facebook.com/v20.0/{$mediaId}/comments", [
            'message' => $request->message,
            'access_token' => $accessToken
        ]);

        if ($response->successful()) {
            return redirect('/dashboard')->with('success', 'Comment posted successfully!');
        } else {
            return redirect('/dashboard')->with('error', 'Failed to post comment: ' . $response->json()['error']['message'] ?? 'Unknown error');
        }
    }
}

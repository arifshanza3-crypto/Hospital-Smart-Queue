<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\User;
use App\Models\Notification;
use App\Traits\NotificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TokenController extends Controller
{
    use NotificationTrait;

    public function showForm()
    {
        return view('Pages.Token_form');
    }

    /**
     * ✅ Show Token Status Page (Yeh missing tha!)
     */
    public function showStatus(Request $request)
    {
        // Get token number from URL or session
        $tokenNumber = $request->query('token') ?? session('current_token');
        
        $token = null;
        $nowServing = 'N/A';

        if ($tokenNumber) {
            // ✅ Fetch token from database
            $token = Token::where('token_number', $tokenNumber)->first();

            if ($token) {
                // ✅ Dynamic Position Calculate
                $position = Token::whereIn('status', ['waiting', 'calling'])
                    ->where('created_at', '<', $token->created_at)
                    ->count() + 1;

                // ✅ Dynamic Estimated Time
                $estimatedTime = ($position - 1) * 15;

                // ✅ Attach to token object
                $token->position = $position;
                $token->estimated_time = $estimatedTime;

                Log::info('Status Page - Token Found: ' . $token->token_number . ' | Patient: ' . $token->patient_name);
            } else {
                Log::warning('Status Page - Token Not Found: ' . $tokenNumber);
            }
        }

        // ✅ Get currently serving token
        $servingToken = Token::where('status', 'serving')
            ->orderBy('created_at', 'desc')
            ->first();
            
        if ($servingToken) {
            $nowServing = $servingToken->token_number;
        }

        return view('Pages.Status', compact('token', 'nowServing'));
    }

    public function generateToken(Request $request)
    {
        try {
            // ✅ Validation - Email is nullable (optional)
            $validated = $request->validate([
                'patient_name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'required|string|max:11|regex:/^03\d{9}$/',
            ]);

            // ✅ Check if user is logged in
            $userId = null;
            if (Auth::check()) {
                $userId = Auth::id();
            }

            // ✅ Generate token number
            $lastToken = Token::orderBy('id', 'desc')->first();
            if ($lastToken && $lastToken->token_number) {
                $lastNumber = intval(substr($lastToken->token_number, 4));
                $newNumber = $lastNumber + 1;
                $tokenNumber = 'TKN-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            } else {
                $tokenNumber = 'TKN-001';
            }

            // ✅ Get last position
            $lastPosition = Token::whereIn('status', ['waiting', 'calling'])->count();

            // ✅ Create token
            $token = Token::create([
                'token_number' => $tokenNumber,
                'patient_name' => $request->patient_name,
                'patient_id' => $userId,
                'department' => 'General',
                'phone' => $request->phone,
                'email' => $request->email,
                'status' => 'waiting',
                'type' => 'online',
                'position' => $lastPosition + 1,
                'estimated_time' => 15,
                'created_at' => now()
            ]);

            // ✅ Save token in session for status page
            session(['current_token' => $tokenNumber]);

            Log::info('Token generated: ' . $tokenNumber . ' for ' . $request->patient_name);

            // ✅ Send notification to user (if logged in)
            if ($userId) {
                $this->notifyUser(
                    $userId,
                    'Token Generated',
                    'Your token ' . $tokenNumber . ' has been generated successfully',
                    'token_generated',
                    [
                        'token_number' => $tokenNumber,
                        'patient_name' => $request->patient_name,
                        'url' => route('status.page', ['token' => $tokenNumber])
                    ]
                );
            }

            // ✅ Send notification to all staff and admins
            $this->notifyAllStaffAndAdmins(
                'New Token Generated',
                'Token ' . $tokenNumber . ' generated for ' . $request->patient_name,
                'token_generated',
                [
                    'token_number' => $tokenNumber,
                    'patient_name' => $request->patient_name,
                    'url' => route('staff.dashboard')
                ]
            );

            // ✅ Redirect to status page
            return redirect()->route('status.page', ['token' => $tokenNumber])
                ->with('success', 'Token ' . $tokenNumber . ' generated successfully!');

        } catch (\Exception $e) {
            Log::error('Error generating token: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error generating token: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function getTokenStatus(Request $request)
    {
        try {
            $tokenNumber = $request->query('token');

            if (!$tokenNumber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token number is required'
                ], 400);
            }

            $token = Token::where('token_number', $tokenNumber)->first();

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token not found'
                ], 404);
            }

            // ✅ Calculate waiting time
            $waitingTime = 0;
            if ($token->status === 'waiting') {
                $waitingTokens = Token::where('status', 'waiting')
                    ->where('position', '<', $token->position)
                    ->count();
                $waitingTime = $waitingTokens * 15;
            }

            // ✅ Get currently serving token
            $servingToken = Token::where('status', 'serving')
                ->orderBy('created_at', 'desc')
                ->first();
            $nowServing = $servingToken ? $servingToken->token_number : 'N/A';

            return response()->json([
                'success' => true,
                'token' => [
                    'token_number' => $token->token_number,
                    'patient_name' => $token->patient_name,
                    'status' => $token->status,
                    'position' => $token->position,
                    'estimated_time' => $token->estimated_time,
                    'waiting_time' => $waitingTime,
                    'now_serving' => $nowServing,
                    'created_at' => $token->created_at ? $token->created_at->format('h:i A') : 'N/A'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting token status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting token status'
            ], 500);
        }
    }
}
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
     * ✅ Show Token Status Page
     */
    public function showStatus(Request $request)
    {
        $tokenNumber = $request->query('token') ?? session('current_token');
        
        $token = null;
        $nowServing = 'N/A';

        if ($tokenNumber) {
            $token = Token::where('token_number', $tokenNumber)->first();

            if ($token) {
                // ✅ Dynamic Position
                $position = Token::whereIn('status', ['waiting', 'calling'])
                    ->where('created_at', '<', $token->created_at)
                    ->count() + 1;

                $token->position = $position;
                $token->estimated_time = ($position - 1) * 15;

                Log::info('Status Page - Token: ' . $token->token_number . ' | Patient: ' . $token->patient_name);
            }
        }

        $servingToken = Token::where('status', 'serving')
            ->orderBy('created_at', 'desc')
            ->first();
            
        if ($servingToken) {
            $nowServing = $servingToken->token_number;
        }

        return view('Pages.Status', compact('token', 'nowServing'));
    }

    /**
     * ✅ Generate Token
     */
    public function generateToken(Request $request)
    {
        try {
            $validated = $request->validate([
                'patient_name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'required|string|max:11|regex:/^03\d{9}$/',
            ]);

            $userId = Auth::check() ? Auth::id() : null;

            $lastToken = Token::orderBy('id', 'desc')->first();
            if ($lastToken && $lastToken->token_number) {
                $lastNumber = intval(substr($lastToken->token_number, 4));
                $newNumber = $lastNumber + 1;
                $tokenNumber = 'TKN-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            } else {
                $tokenNumber = 'TKN-001';
            }

            $lastPosition = Token::whereIn('status', ['waiting', 'calling'])->count();

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

            session(['current_token' => $tokenNumber]);

            Log::info('Token generated: ' . $tokenNumber);

            if ($userId) {
                $this->notifyUser(
                    $userId,
                    'Token Generated',
                    'Your token ' . $tokenNumber . ' has been generated successfully',
                    'token_generated',
                    ['token_number' => $tokenNumber, 'url' => route('status.page', ['token' => $tokenNumber])]
                );
            }

            $this->notifyAllStaffAndAdmins(
                'New Token Generated',
                'Token ' . $tokenNumber . ' generated for ' . $request->patient_name,
                'token_generated',
                ['token_number' => $tokenNumber, 'url' => route('staff.dashboard')]
            );

            return redirect()->route('status.page', ['token' => $tokenNumber])
                ->with('success', 'Token ' . $tokenNumber . ' generated successfully!');

        } catch (\Exception $e) {
            Log::error('Error generating token: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error generating token: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * ✅ Get Token Status API — FIXED
     */
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

            // ✅ Recalculate DYNAMIC position (matches Blade logic)
            $dynamicPosition = Token::whereIn('status', ['waiting', 'calling'])
                ->where('created_at', '<', $token->created_at)
                ->count() + 1;

            // ✅ Calculate wait time
            $waitingTimeMinutes = 0;
            $remainingSeconds = 0;

            if ($token->status === 'waiting') {
                // Tokens ahead (waiting only)
                $aheadCount = Token::where('status', 'waiting')
                    ->where('created_at', '<', $token->created_at)
                    ->count();

                // Total wait from creation
                $totalWaitMinutes = $aheadCount * 15;

                // ✅ FIXED: Use timestamp for reliable elapsed calc
                $elapsedSeconds = now()->timestamp - $token->created_at->timestamp;
                $elapsedMinutes = max(0, floor($elapsedSeconds / 60));

                // Remaining = Total - Elapsed
                $waitingTimeMinutes = max(0, $totalWaitMinutes - $elapsedMinutes);
                $remainingSeconds = $waitingTimeMinutes * 60;

                Log::info("Wait Calc | Token: {$tokenNumber} | Ahead: {$aheadCount} | Total: {$totalWaitMinutes}min | Elapsed: {$elapsedMinutes}min | Remaining: {$waitingTimeMinutes}min");
            }

            // ✅ Currently serving
            $servingToken = Token::where('status', 'serving')
                ->orderBy('created_at', 'desc')
                ->first();
            $nowServing = $servingToken ? $servingToken->token_number : 'N/A';

            return response()->json([
                'success' => true,
                'token' => [
                    'token_number'      => $token->token_number,
                    'patient_name'      => $token->patient_name,
                    'status'            => $token->status,
                    'position'          => $dynamicPosition,   // ✅ Dynamic position
                    'estimated_time'    => $token->estimated_time,
                    'waiting_time'      => $waitingTimeMinutes,
                    'remaining_seconds' => $remainingSeconds,
                    'now_serving'       => $nowServing,
                    'created_at'        => $token->created_at ? $token->created_at->format('h:i A') : 'N/A'
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
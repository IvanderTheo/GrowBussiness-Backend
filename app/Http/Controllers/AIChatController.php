<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\GeminiService;
use App\Models\AIChatSessions;
use Illuminate\Support\Str;
use App\Models\AIChatMessages;

class AIChatController extends Controller
{
    //
    public function index(Request $request) {
        $userId = auth()->id(); //get user id
        $chats = AIChatSessions::where('user_id', $userId)->get();
        return response()->json([
            'status'=>'success',
            'message'=>'Session retrieved successfully',
            'data'=>$chats,
        ]);
    }
    public function show($id)
    {
        $session = AIChatSessions::findOrFail($id);

        $messages = $session->messages()
            ->oldest()
            ->get();

        return response()->json([
            'session' => $session,
            'messages' => $messages
        ]);
    }

    public function chat(Request $request, GeminiService $gemini)
    {
        $request->validate([
            'message' => 'required|string',
            'session_id' => 'nullable|exists:ai_chat_sessions,id'
        ]);

        $session = null;

        // kalau belum ada session
        if (!$request->session_id) {

            $title = Str::limit($request->message, 40);

            $session = AIChatSessions::create([
                'user_id' => auth()->id(),
                'title' => $title,
            ]);
        } else {
            $session = AIChatSessions::findOrFail($request->session_id);
        }

        // save user message
        $userMessage = $session->messages()->create([
            'sender' => 'user',
            'message' => $request->message,
        ]);

        // ambil history
        $messages = AIChatMessages::where('session_id', $session->id)
            ->latest()
            ->take(10)
            ->get()
            ->reverse();

        $history = [];

        foreach ($messages as $message) {

            $history[] = [
                'role' => $message->sender === 'ai'
                    ? 'assistant'
                    : 'user',

                'message' => (string) $message->message
            ];
        }

        $reply = $gemini->generate(
            auth()->user(),
            $request->message,
            $history
        );

        // save ai response
        $aiMessage = $session->messages()->create([
            'sender' => 'ai',
            'message' => $reply,
        ]);

        return response()->json([
            'session' => $session,
            'messages' => [
                $userMessage,
                $aiMessage
            ]
        ]);
    }

    public function tempChat(Request $request, GeminiService $gemini) {
        $request->validate([
            'message'=> [
                'required',
                function ($attribute, $value, $fail) {
                        if (str_word_count($value) > 255) {
                        $fail("$attribute Maksimal 255 kata");
                    }
                }
            ]
        ]);

        $reply = $gemini->tempChat($request->user(),$request->message);

        $userMessage = [
            'sender'=>'user',
            'message'=>$request->message,
        ];
        $aiMessage = [
            'sender'=>'ai',
            'message'=>$reply,
        ];
        return response()->json([
            'message'=>[$userMessage,$aiMessage],
        ]);
    }

    public function destroy($id) {
        $session = AIChatSessions::find($id);

        if (!$session) {
            return response()->json([
                'status' => 'error',
                'message' => 'Session not found'
            ], 404);
        }

        // delete semua messages
        $session->messages()->delete();

        // delete session
        $session->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Session and messages deleted successfully'
        ]);
    }
}

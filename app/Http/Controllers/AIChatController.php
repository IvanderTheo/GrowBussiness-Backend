<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\GeminiService;
use App\Models\AIChatSessions;
use App\Models\AIChatMessages;

class AIChatController extends Controller
{
    //
    public function index() {
        $result = AIChatSessions::latest()->get();
        return response()->json([
            'status'=>'success',
            'message'=>'Session retrieved successfully',
            'data'=>$result,
        ]);
    }
    public function show($id) {
        $session = AIChatSessions::with([
            'messages' => function ($query) { // latest chat
                $query->latest();
            }
        ])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Chat retrieved successfully',
            'data' => $session,
        ]);
    }

    public function new_chat(Request $request) {
        try {
            $validate = $request->validate([
                'title'=> 'string|max:255',
            ]);

            $validate['user_id'] = $request->user()->id; //simpan uuid user

            AIChatSessions::create($validate);

            return response()->json([
                'status'=>'success',
                'message'=>'session created',
                'data'=>$validate
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=>'failed',
                'message'=>'store failed',
                'error'=>$e
            ]);
        }
    }
    public function chat(Request $request, GeminiService $gemini, $id)
    {
        $session = AIChatSessions::where('id', $id)->firstOrFail();

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
        // simpan pesan user
        $userMessage = $session->messages()->create([
            'sender' => 'user',
            'message' => $request->message,
        ]);


        // create gemini context
        $messages = AIChatMessages::where('session_id', $session->id)
        ->oldest()
        ->take(5)
        ->get();

        $conversation = [];

        foreach ($messages as $message) {
            $conversation[] = [
                'role' => $message->sender,
                'message' => $message->message
            ];
        }

        $conversation[] = [
            'role' => 'user',
            'message' => $request->message
        ];

        //generate response
        $reply = $gemini->generate($request->user(), $conversation);


        // simpan pesan AI
        $aiMessage = $session->messages()->create([
            'sender' => 'ai',
            'message' => $reply,
        ]);

        return response()->json([
            'session_id' => $session->id,
            'message'=> [$userMessage,$aiMessage],
        ],201);
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
        $session = AIChatSessions::findOrFail($id);

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

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
        $session = AIChatSessions::with('messages')->findOrFail($id);

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

            $validate['user_id'] = auth()->id(); //simpan uuid user

            AIChatSessions::create($validate);

            return response()->json([
                'status'=>'success',
                'message'=>'session created'
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
            'message' => 'required|string|max:255'
        ]);
        // simpan pesan user
        $userMessage = $session->messages()->create([
            'sender' => 'user',
            'message' => $request->message,
        ]);

        //generate response
        $reply = $gemini->generate($request->message);


        // simpan pesan AI
        $aiMessage = $session->messages()->create([
            'sender' => 'assistant',
            'message' => $reply,
        ]);

        return response()->json([
            'session_id' => $session->id,
            'user_message' => $userMessage,
            'ai_reply' => $aiMessage,
        ],201);
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

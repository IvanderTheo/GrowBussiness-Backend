<?php

namespace App\Http\Controllers;

use App\Models\ScheduleDetails;
use Exception;
use Illuminate\Http\Request;
use App\Models\Schedules;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    //
    public function index() {
        try {
            $result = Schedules::all();
                return response()->json([
                    'status'=>'success',
                    'message'=>'Data retrieved successfully',
                    'data'=>$result
                ],201);
        } catch (Exceception $e) {
            return response()->json([
                'status'=>'failed',
                'message'=>$e,
            ],401);
        }
    }
    public function show($id) {
        $result = Schedules::with('detail')->findOrFail($id);
        return response()->json([
            'status'=>'success',
            'message'=>'Data retrieved successfully',
            'data'=>$result,
        ],201);
    }
    public function store(Request $request) {
        try {
            $request->validate([
                'title'=>[
                    'required',
                    function ($attribute, $value, $fail) {
                            if (str_word_count($value) > 30) {
                                $fail("$attribute Maksimal 30 kata");
                            }
                        }],
                'description'=>[
                        'required',
                        function ($attribute, $value, $fail) {
                            if (str_word_count($value) > 255) {
                                $fail("$attribute Maksimal 255 kata");
                            }
                        }
                    ],
                'start_datetime'=>'required|date_format:Y-m-d H:i:s',
                'end_datetime'=>'required|date_format:Y-m-d H:i:s|after:start_datetime',
                'location'=>'string|nullable',
                'status'=>'nullable|in:pending,ongoing,completed,cancelled'//enum,
            ]);

            DB::transaction(function () use($request) {
                $status = $request->status ?? 'pending';//set auto pending

                Schedules::create([
                    'user_id'=>$request->user()->id,
                    'title'=>$request->title,
                    'description'=>$request->description,
                    'status'=>$status,
                    ])
                    ->detail()->create([
                        'start_datetime'=>$request->start_datetime,
                        'end_datetime'=>$request->end_datetime,
                        'location'=>$request->location,
                    ]);
            });

            return response()->json([
                'status'=>'success',
                'message'=>'Data stored successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=>'failed',
                'message'=>'store failed',
                'error'=>$e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, Schedules $schedule) {
        try {

            $validated = $request->validate([
                'title' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (str_word_count($value) > 30) {
                            $fail("$attribute maksimal 30 kata");
                        }
                    }
                ],

                'description' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (str_word_count($value) > 255) {
                            $fail("$attribute maksimal 255 kata");
                        }
                    }
                ],
            ]);

            $schedule->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Schedule updated successfully',
                'data' => $schedule
            ]);

        } catch (Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => 'Update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function updateDetail(
        Request $request,
        Schedules $schedule,
        ScheduleDetails $detail
    )
    {
        try {

            // keamanan: pastikan detail milik schedule
            if ($detail->schedule_id !== $schedule->id) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Detail does not belong to this schedule'
                ], 403);
            }

            $validated = $request->validate([
                'start_datetime' => 'sometimes|date',
                'end_datetime' => 'sometimes|date|after:start_datetime',
                'location' => 'nullable|string',
                'status' => 'nullable|in:cancelled',
            ]);

            $detail->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Detail updated successfully',
                'data' => $detail
            ]);

        } catch (Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => 'Update detail failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {

            $schedule = Schedules::findOrFail($id);

            $schedule->detail()->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Schedule deleted successfully'
            ]);

        } catch (Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => 'Delete failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

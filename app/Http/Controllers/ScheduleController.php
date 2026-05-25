<?php

namespace App\Http\Controllers;

use App\Enums\ScheduleEnums;
use App\Models\ScheduleDetails;
use Exception;
use Illuminate\Http\Request;
use App\Models\Schedules;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    //
    public function index() {
        try {
            $result = Schedules::all();

            foreach($result as $schedule)
            {
                if(
                    $schedule->status !== 'cancelled'
                    &&
                    $schedule->status !== $schedule->current_status
                ){
                    $schedule->update([
                        'status'=>$schedule->current_status
                    ]);
                }
            }

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
    public function store(Request $request) {
        try {
            $request->validate([
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

                'start_datetime' => 'required|date',
                'end_datetime' => 'nullable|date|after:start_datetime',

                'status' => 'nullable|in:pending,ongoing,completed,cancelled'
            ]);
            $schedule = null;
            DB::transaction(function () use ($request, &$schedule) {
                $status = $request->status ?? 'pending';
                $schedule = Schedules::create([
                    'user_id' => auth()->id(),
                    'title' => $request->title,
                    'description' => $request->description,
                    'status' => $status,

                    'start_datetime' => Carbon::parse($request->start_datetime),

                    'end_datetime' => $request->end_datetime
                        ? Carbon::parse($request->end_datetime)
                        : null,
                ]);
            });
            return response()->json([
                'status'=>'success',
                'message'=>'Data stored successfully',
                'data'=>$schedule,
            ],201);
        } catch (Exception $e) {
            return response()->json([
                'status'=>'failed',
                'message'=>'store failed',
                'error'=>$e->getMessage(),
            ],401);
        }
    }

    public function update(Request $request, $id) {
        try {
            
            $validated = $request->validate([
                'title' => [
                    'sometimes',
                    function ($attribute, $value, $fail) {
                        if (str_word_count($value) > 30) {
                            $fail("$attribute maksimal 30 kata");
                        }
                    }
                ],

                'description' => [
                    'sometimes',
                    function ($attribute, $value, $fail) {
                        if (str_word_count($value) > 255) {
                            $fail("$attribute maksimal 255 kata");
                        }
                    }
                ],
                'start_datetime' => 'sometimes|date',
                'end_datetime' => 'sometimes|date|after:start_datetime',
            ]);
            $schedule = null;
            DB::transaction(function () use ($validated, $id, &$schedule) {
                $schedule = Schedules::findOrFail($id);
                $schedule->update($validated);
            });
            return response()->json([
                'status' => 'success',
                'message' => 'Schedule Updated Successfully',
                'data' => $schedule,
            ]);
        } catch (Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => 'Update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function cancel($id) {
        try {
            DB::transaction(function() use ($id) {
                $scheduleId = Schedules::findOrFail($id);
                $scheduleId->update([
                    $scheduleId->update([
                        'status' => ScheduleEnums::Cancelled
                    ])
                ]);
            });
            return response()->json([
                'status' => 'success',
                'message' => 'Schedule Cancelled Successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Cancel failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            DB::transaction(function() use ($id) {
                $schedule = Schedules::findOrFail($id);
                $schedule->delete();
            });
            
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

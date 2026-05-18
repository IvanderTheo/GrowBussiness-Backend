<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedules;
use Carbon\Carbon;

class UpdateScheduleStatus extends Command
{
    /**
     * Execute the console command.
     */

    protected $signature = 'schedule:update-status';

    protected $description = 'Update schedule status automatically';


    public function handle()
{
    $now = Carbon::now();

    $ongoing = Schedules::where('status', 'pending')
        ->where('start_datetime', '<=', $now)
        ->where('end_datetime', '>', $now)
        ->update([
            'status' => 'ongoing'
        ]);

    $completed = Schedules::where('status', 'ongoing')
        ->where('end_datetime', '<=', $now)
        ->update([
            'status' => 'completed'
        ]);

    $this->info("Pending → Ongoing: {$ongoing}");
    $this->info("Ongoing → Completed: {$completed}");
}
}

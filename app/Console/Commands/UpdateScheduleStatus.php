<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\ScheduleDetails;
use Carbon\Carbon;

#[Signature('app:update-schedule-status')]
#[Description('Command description')]
class UpdateScheduleStatus extends Command
{
    /**
     * Execute the console command.
     */

    protected $signature = 'schedule:update-status';

    protected $description = 'Update schedule status automatically';


    public function handle()
    {
        //
        $now = Carbon::now();

        // ubah pending -> ongoing
        ScheduleDetails::where('status', 'pending')
            ->where('start_datetime', '<=', $now)
            ->where('end_datetime', '>', $now)
            ->update([
                'status' => 'ongoing'
            ]);

        // ubah ongoing -> completed
        ScheduleDetails::where('status', 'ongoing')
            ->where('end_datetime', '<=', $now)
            ->update([
                'status' => 'completed'
            ]);

        $this->info('Schedule status updated successfully.');
    }
}

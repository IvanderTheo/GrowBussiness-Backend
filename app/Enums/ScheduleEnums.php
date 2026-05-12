<?php
namespace App\Enums;

enum ScheduleEnums:string
{
    case Pending = 'pending';
    case Ongoing = 'ongoing';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
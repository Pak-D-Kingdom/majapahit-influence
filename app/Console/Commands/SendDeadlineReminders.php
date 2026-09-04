<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:send-deadline-reminders')]
#[Description('Send deadline reminders to KOLs for upcoming endorsements')]
class SendDeadlineReminders extends Command
{
    public function handle(): int
    {
        $this->info('Checking endorsement deadlines...');

        // TODO:
        // 1. Ambil endorsement dengan status "in-progress"
        // 2. Cek deadline H-3 dan H-1
        // 3. Kirim DeadlineReminderNotification ke KOL terkait

        $this->info('Deadline reminder check completed.');

        return self::SUCCESS;
    }
}
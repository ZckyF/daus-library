<?php

namespace App\Console\Commands;

use App\Models\BorrowBook;
use Illuminate\Console\Command;

class UpdateBorrowStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'borrow:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update borrow status automatically';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        $borrowedBooks = BorrowBook::where('status', 'borrowed')
            ->where('return_date', '<=', $now)
            ->get();

        foreach ($borrowedBooks as $borrowedBook) {
            $borrowedBook->status = 'due';
            $borrowedBook->save();
        }

        $this->info('Borrow status updated successfully');
    }
}

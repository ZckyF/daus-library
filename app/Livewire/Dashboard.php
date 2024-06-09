<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\BorrowBook;
use App\Models\Member;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Dashboard extends Component
{
    public $totalUsers;
    public $totalMembers;
    public $totalBooks;
    public $totalBookCategories;

    public $selectedYear;
    public $years;
    public $borrowData;

    
    
    public function mount()
    {
        $this->totalUsers = User::count();
        $this->totalMembers = Member::count();
        $this->totalBooks = Book::count();
        $this->totalBookCategories = BookCategory::count();

        $this->selectedYear = date('Y');
        $this->years = range(date('Y'), date('Y') - 10); 
        $this->fetchData();

    }
    public function fetchData()
    {
        // Fetch all borrow records for the selected year
        $borrowRecords = BorrowBook::whereYear('borrow_date', $this->selectedYear)->get();

        // Group records by month and count the number of borrows per month
        $borrowData = $borrowRecords->groupBy(function ($item) {
            return Carbon::parse($item->borrow_date)->format('m');
        })->map(function ($month) {
            return $month->count();
        });

        // Ensure all months are represented in the result
        $monthlyCount = [];
        for ($i = 1; $i <= 12; $i++) {
            $month = str_pad($i, 2, '0', STR_PAD_LEFT);
            $monthlyCount[$month] = $borrowData->get($month, 0);
        }
        $this->borrowData = $monthlyCount;
    }


    public function updatedSelectedYear()
    {
        
        $this->fetchData();
        $this->dispatch('updateChart', $this->borrowData);
    }
    public function render()
    {
        return view('livewire.dashboard');
    }
}

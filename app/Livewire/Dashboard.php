<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\BorrowBook;
use App\Models\BorrowBookPivot;
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

    public $topMembers;
    public $topBooks;

    
    
    public function mount()
    {
        $this->totalUsers = User::count();
        $this->totalMembers = Member::count();
        $this->totalBooks = Book::count();
        $this->totalBookCategories = BookCategory::count();

        $this->selectedYear = date('Y');
        $this->years = range(date('Y'), date('Y') - 10); 

        $this->fetchTopMembers();
        $this->fetchTopBooks();
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

    public function fetchTopMembers()
    {
        $this->topMembers = BorrowBook::select('member_id', DB::raw('COUNT(*) as borrow_count'))
            ->groupBy('member_id')
            ->orderBy('borrow_count', 'desc')
            ->take(10)
            ->with('member')
            ->get();
    }

    public function fetchTopBooks()
    {
        $this->topBooks = Book::select('books.id', 'books.title', DB::raw('COUNT(borrow_book_pivot.book_id) as borrow_count'))
            ->join('borrow_book_pivot', 'books.id', '=', 'borrow_book_pivot.book_id')
            ->groupBy('books.id', 'books.title')
            ->orderBy('borrow_count', 'desc')
            ->take(10)
            ->get();
    }

    public function updatedSelectedYear()
    {
        
        $this->fetchData();
        $this->fetchTopMembers(); // Pencegah error
        $this->dispatch('updateChart', $this->borrowData);
    }
    public function render()
    {
        return view('livewire.dashboard');
    }
}

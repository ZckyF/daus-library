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
    /**
     * The selected year for filtering borrow data.
     * 
     * @var int
     */
    public int $selectedYear;

    /**
     * Array of borrow data grouped by month.
     * 
     * @var array
     */
    public array $borrowData = [];

    /**
     * Initialize the component with default values.
     * 
     * @return void
     */
    public function mount(): void
    {
        $this->selectedYear = date('Y');
        $this->borrowData = $this->fetchBorrowBook();
    }

    /**
     * Fetch borrow data for the selected year, grouped by month.
     * 
     * @return array
     */
    public function fetchBorrowBook(): array
    {
        $borrowRecords = BorrowBook::whereYear('borrow_date', $this->selectedYear)->get();

        $borrowData = $borrowRecords->groupBy(function ($item) {
            return Carbon::parse($item->borrow_date)->format('m');
        })->map(function ($month) {
            return $month->count();
        });

        $monthlyCount = [];
        for ($i = 1; $i <= 12; $i++) {
            $month = str_pad($i, 2, '0', STR_PAD_LEFT);
            $monthlyCount[$month] = $borrowData->get($month, 0);
        }

        return $monthlyCount;
    }

    /**
     * Fetch the top 10 members based on borrow count.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function fetchTopMembers(): \Illuminate\Database\Eloquent\Collection
    {
        return BorrowBook::select('member_id', DB::raw('COUNT(*) as borrow_count'))
            ->groupBy('member_id')
            ->orderBy('borrow_count', 'desc')
            ->take(10)
            ->with('member')
            ->get();
    }

    /**
     * Fetch the top 10 books based on borrow count.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function fetchTopBooks(): \Illuminate\Database\Eloquent\Collection
    {
        return Book::select('books.id', 'books.title', DB::raw('COUNT(borrow_book_pivot.book_id) as borrow_count'))
            ->join('borrow_book_pivot', 'books.id', '=', 'borrow_book_pivot.book_id')
            ->groupBy('books.id', 'books.title')
            ->orderBy('borrow_count', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Update the borrow data when the selected year is changed.
     * 
     * @return void
     */
    public function updatedSelectedYear(): void
    {
        $this->borrowData = $this->fetchBorrowBook();
        $this->dispatch('updateChart', $this->borrowData);
    }

    /**
     * Render the component view.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        $totalUsers = User::count();
        $totalMembers = Member::count();
        $totalBooks = Book::count();
        $totalBookCategories = BookCategory::count();

        $years = range(date('Y'), date('Y') - 10);

        $topMembers = $this->fetchTopMembers();
        $topBooks = $this->fetchTopBooks();

        return view('livewire.dashboard', compact(
            'totalUsers', 'totalMembers', 'totalBooks', 'totalBookCategories', 'years', 'topMembers', 'topBooks'
        ));
    }
}


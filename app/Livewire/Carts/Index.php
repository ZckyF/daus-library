<?php

namespace App\Livewire\Carts;

use App\Models\Book;
use App\Models\BorrowBook;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    /**
     * Cart quantities for each book.
     * 
     * @var array
     */
    public array $cartQuantities = [];
    /**
     * The search term for members.
     * 
     * @var string
     */
    public string $searchMember = '';
    /**
     * The search term for books.
     * 
     * @var string
     */
    public string $searchBooks = '';
    /**
     * The error message when adding more than 3 books to the cart.
     * 
     * @var string
     */
    public string $messageError = 'You can only add up to 3 books to the cart.';
    /**
     * The number card of the member.
     * 
     * @var string
     */
    public $number_card;
    /**
     * The full name of the member.
     * 
     * @var string
     */
    public $full_name;
    /**
     * The email of the member.
     * 
     * @var string
     */
    public $email;
    /**
     * The phone number of the member.
     * 
     * @var string
     */
    public $phone_number;
    /**
     * The return date of the borrow.
     * 
     * @var string
     */
    public $return_date;

    /**
     * Fetch the cart from session and return the books.
     * 
     * @return Illuminate\Support\Collection
     */
    public function fetchCarts(): \Illuminate\Support\Collection
    {
        $cart = session()->get('cart', []);
        $this->cartQuantities = $cart;
        return Book::whereIn('id', array_keys($cart))->get();
    }
    /**
     * Increment the quantity of the book in the cart.
     * 
     * @param  int  $bookId
     * @return void
     */
    public function incrementQuantity(int $bookId): void
    {
        $cart = session()->get('cart', []);
        $totalQuantity = array_sum(array_column($cart, 'quantity'));
        if (isset($this->cartQuantities[$bookId]['quantity']) && $totalQuantity < 3) {
            
            $cart[$bookId]['quantity']++;
            session()->put('cart', $cart);
        } else {
            session()->flash('error', $this->messageError);
        }
    }
    /**
     * Decrement the quantity of the book in the cart.
     * 
     * @param  int  $bookId
     * @return void
     */
    public function decrementQuantity(int $bookId): void
    {
        $cart = session()->get('cart', []);
        if (isset($this->cartQuantities[$bookId])) {
            
            $cart[$bookId]['quantity']--;
            session()->put('cart', $cart);
        }
    }
    /**
     * Add the book to the cart.
     * 
     * @param  int  $bookId
     * @return void
     */
    public function addToCart(int $bookId): void
    {
        $cart = session()->get('cart', []);
        $quantity = 1;
    
        // Hitung jumlah buku dalam keranjang
        $totalBooksInCart = array_sum(array_column($cart, 'quantity'));
    
        // Jika total buku sudah mencapai atau melebihi batas
        if ($totalBooksInCart >= 3) {
            session()->flash('error', $this->messageError);
            return;
        }
    
        // Jika buku sudah ada dalam keranjang dan tidak akan melebihi batas
        if (isset($cart[$bookId])) {
            $newTotalBooksInCart = $totalBooksInCart + 1; // jumlah setelah penambahan
            if ($newTotalBooksInCart > 3) {
                session()->flash('error', $this->messageError);
                return;
            }
            $cart[$bookId]['quantity'] += $quantity;
        } else {
            // Jika buku belum ada dalam keranjang
            $cart[$bookId] = [
                'quantity' => $quantity,
            ];
        }
    
        session()->put('cart', $cart);
        session()->flash('success', 'Book added to cart successfully.');
    }
    
    /**
     * Delete the book from the cart.
     * 
     * @param  int  $bookId
     * @return void
     */
    public function deleteFromCart(int $bookId)
    {
        $cart = session()->get('cart', []);
        unset($cart[$bookId]);
        session()->put('cart', $cart);
    }
    /**
     * Fetch the search member and return the result.
     * 
     * @return Illuminate\Support\Collection
     */
    public function fetchSearchMember(): \Illuminate\Support\Collection
    {
        $result = collect();
        if($this->searchMember) {
            $result = Member::where('full_name', 'like', '%' . $this->searchMember . '%')
                        ->orWhere('number_card', 'like', '%' . $this->searchMember . '%')
                        ->orWhere('email', 'like', '%' . $this->searchMember . '%')
                        ->get();
        }
        return $result;
    }
    /**
     * Fetch the search book and return the result.
     * 
     * @return Illuminate\Support\Collection
     */
    public function fetchSearchBooks(): \Illuminate\Support\Collection
    {
        $result = collect();
        if($this->searchBooks) {
            $result = Book::where('title', 'like', '%' . $this->searchBooks . '%')
                        ->orWhere('author', 'like', '%' . $this->searchBooks . '%')
                        ->orWhere('isbn', 'like', '%' . $this->searchBooks . '%')
                        ->get();
        }
        return $result;
    }
    /**
     * Choose the member for the borrow book.
     * 
     * @param  int  $memberId
     * @return void
     */
    public function chooseMember(int $memberId): void
    {
        $member = Member::find($memberId);

        $this->number_card = $member->number_card;
        $this->full_name = $member->full_name;
        $this->email = $member->email;
        $this->phone_number = $member->phone_number;

        $this->searchMember = '';
    }
    /**
     * Add the borrow book.
     * 
     * @return void
     */
    public function addBorrow(): void
    {
        $member = Member::where('number_card', $this->number_card)->first();
        $cart = session()->get('cart', []);
        $totalBooksInCart = array_sum(array_column($cart, 'quantity'));
        if(!$member) {
            session()->flash('error', 'Member not found.');
            return;
        }
        if (empty($cart)) {
            session()->flash('error', 'No books selected.');
            return;
        }

        $borrowNumber = time() . substr($member->number_card, -4);

        $borrowBook = BorrowBook::create([
            'borrow_number' => $borrowNumber,
            'member_id' => $member->id,
            'borrow_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'return_date' => $this->return_date . ' 23:59:59',
            'quantity' => $totalBooksInCart,
            'user_id' => Auth::user()->id,
        ]);
    
        foreach ($cart as $bookId => $bookData) {
            $book = Book::find($bookId);
            if ($book) {
                $book->update(['quantity_now' => $book->quantity_now - $bookData['quantity']]);
                for ($i = 0; $i < $bookData['quantity']; $i++) {
                    $borrowBook->books()->attach($bookId);
                }
            }
        }

        session()->flash('success', 'Book borrowed successfully.');

        session()->put('cart', []);

        $this->reset('number_card', 'full_name', 'email', 'phone_number');

    }

    /**
     * Render the livewire component.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        $carts = $this->fetchCarts();
        $members = $this->fetchSearchMember();
        $books = $this->fetchSearchBooks();
        return view('livewire.carts.index', compact('carts', 'members', 'books'));
    }
}

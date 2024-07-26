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
    public array $cartQuantities = [];
    public string $searchMember = '';
    public string $searchBooks = '';

    public string $messageError = 'You can only add up to 3 books to the cart.';

    public $number_card;
    public $full_name;
    public $email;
    public $phone_number;
    public $return_date;


    public function fetchCarts(): \Illuminate\Database\Eloquent\Collection
    {
        $cart = session()->get('cart', []);
        $this->cartQuantities = $cart;
        return Book::whereIn('id', array_keys($cart))->get();
    }
    public function incrementQuantity(int $bookId)
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

    public function decrementQuantity(int $bookId)
    {
        $cart = session()->get('cart', []);
        if (isset($this->cartQuantities[$bookId])) {
            
            $cart[$bookId]['quantity']--;
            session()->put('cart', $cart);
        }
    }

    public function addToCart(int $bookId)
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
    

    public function deleteFromCart(int $bookId)
    {
        $cart = session()->get('cart', []);
        unset($cart[$bookId]);
        session()->put('cart', $cart);
    }

    public function fetchSearchMember()
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

    public function fetchSearchBooks()
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

    public function chooseMember(int $memberId)
    {
        $member = Member::find($memberId);

        $this->number_card = $member->number_card;
        $this->full_name = $member->full_name;
        $this->email = $member->email;
        $this->phone_number = $member->phone_number;

        $this->searchMember = '';
    }

    public function addBorrow()
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



    public function render()
    {
        $carts = $this->fetchCarts();
        $members = $this->fetchSearchMember();
        $books = $this->fetchSearchBooks();
        return view('livewire.carts.index', compact('carts', 'members', 'books'));
    }
}

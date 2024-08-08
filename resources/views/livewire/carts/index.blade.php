@push('styles')
    <style>
        .cover_image {
            height: 150px;
            object-fit: cover;
            object-position: center;
            border-radius: 10px;
        }
        .cover-list-group{
            height: 90px;
            width: 70px;
            object-fit: cover;
            object-position: center;
            border-radius: 5px;
        }
        .img-member-list-group{
            height: 110px;
            width: 90px;
            object-fit: cover;
            object-position: center;
            border-radius: 5px;
        }
    </style>
@endpush

<div>
    @if(session()->has('error'))
        <x-notifications.alert class="alert-danger" :message="session('error')" />
    @endif
    @if(session()->has('success'))
        <x-notifications.alert class="alert-success" :message="session('success')" />
    @endif
    <table class="table table-auto w-full align-middle mb-4">
        <thead>
            <tr>
                <th class="px-4 py-2">Cover</th>
                <th class="px-4 py-2">Title,Author and ISBN</th>
                <th class="px-4 py-2">Quantity</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($carts->count() == 0)
                <tr>
                    <td colspan="4" class="text-center">No data found.</td>
                </tr>
                
            @endif
            @foreach($carts as $cart)
                <tr>
                    <td class="">
                        <img src="{{  asset('storage/covers/' . $cart->cover_image_name)}}" alt="{{ $cart->title }}" class="cover_image object-cover">
                    </td>
                    <td class="">
                        <div>
                            <strong>{{ $cart->title }}</strong><br>
                            <span class="text-sm">{{ $cart->author }}</span>
                            <div class="text-sm">{{ $cart->isbn }}</div>
                        </div>
                    </td>
                    <td class="">
                        <div class="d-flex align-items-center">
                            @if($cartQuantities[$cart->id]['quantity'] > 1)
                                <button wire:click="decrementQuantity({{ $cart->id }})" class="btn btn-sm btn-primary text-white"
                                    wire:key="decrement-{{ $cart->id }}">
                                    <span wire:loading wire:target="decrementQuantity({{ $cart->id }})" class="spinner-border spinner-border-sm"></span>
                                    <span wire:loading.remove wire:target="decrementQuantity({{ $cart->id }})"><i class="bi bi-dash"></i></span>
                                </button>
                            @endif
                            <input wire:key="cart-{{ $cart->id }}" readonly min="1" wire:model="cartQuantities.{{ $cart->id }}.quantity" class="border-0 text-center" style="width: 40px">
                            @if($cartQuantities[$cart->id]['quantity'] < 3)
                                <button wire:click="incrementQuantity({{ $cart->id }})" class="btn btn-sm btn-primary text-white"
                                    wire:key="increment-{{ $cart->id }}">
                                    <span wire:loading wire:target="incrementQuantity({{ $cart->id }})" class="spinner-border spinner-border-sm"></span>
                                    <span wire:loading.remove wire:target="incrementQuantity({{ $cart->id }})"><i class="bi bi-plus"></i></span>
                                </button>
                            @endif
                        </div>
                    </td>
                    <td class="">
                        <button  wire:click="deleteFromCart({{ $cart->id }})" class="btn btn-danger text-white px-2 py-1 rounded" wire:key="delete-{{ $cart->id }}">
                            <span class="spinner-border spinner-border-sm" wire:loading wire:target="deleteFromCart({{ $cart->id }})"></span>
                            <span wire:loading.remove wire:target="deleteFromCart({{ $cart->id }})"><i class="bi bi-trash"></i></span>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row">         
        <div class="col-md-8 order-2 order-md-1 mb-3">
            <form class="row" wire:submit.prevent>
                <div class="col-md-6 mb-3 position-relative">
                    <input type="text" wire:model.live="searchMember" class="form-control" placeholder="Search member...">
                    @if($members->count() > 0)
                        <ul class="list-group position-absolute w-100" style="max-height: 200px; overflow-y: auto; z-index: 1000;">
                            @foreach($members as $member)
                                <li class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <img src="{{ Storage::url('members/'. $member->image_name ) }}" alt="" class="img-member-list-group">
                                        </div>
                                        <div>
                                            <strong>{{ Str::limit($member->full_name, 20) }}</strong><br>
                                            <span class="text-sm text-gray-600">{{ $member->number_card }}</span>
                                        </div>
                                        <div>
                                            <button wire:click="chooseMember({{ $member->id }})" class="btn btn-sm btn-primary text-white rounded-2">
                                                <span class="spinner-border spinner-border-sm" wire:loading wire:target="chooseMember({{ $member->id }})"></span>
                                                <span wire:loading.remove wire:target="chooseMember({{ $member->id }})">Choose</span>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="col-md-6 mb-3">
                    <input type="text" wire:model="number_card" class="form-control" placeholder="Number Card" disabled>
                </div>
                <div class="col-md-4 mb-3">
                    <input type="text" wire:model="full_name" class="form-control" placeholder="Full Name" disabled>
                </div>
                <div class="col-md-4 mb-3">
                    <input type="text" wire:model="email" class="form-control" placeholder="Email" disabled>
                </div>
                <div class="col-md-4 mb-3">
                    <input type="text" wire:model="phone_number" class="form-control" placeholder="Phone Number" disabled>
                </div>
                <div class="col-12 mb-3">
                    <label for="return_date">Return Date</label>
                    <input type="date" id="return_date" wire:model="return_date" class="form-control @error('return_date') is-invalid @enderror">
                    @error('return_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="d-grid">
                    <button wire:click.prevent="addBorrow" class="btn btn-primary text-white" type="submit" @if(!$full_name && !$number_card && !$phone_number && !$email && !$return_date ) disabled @endif>
                        <span class="spinner-border spinner-border-sm" wire:loading wire:target="addBorrow"></span>
                        <span class="me-1" wire:loading.remove wire:target="addBorrow"><i class="bi bi-plus"></i></span>
                        <span>Add Borrow</span>
                    </button>
                </div>
               
                
            </form>
        </div>
        
        <div class="col-md-4 order-1 order-md-2 mb-3">
            <div class="row">
                <div class="col-md-12">
                    <input type="text" wire:model.live="searchBooks" class="form-control" placeholder="Search books..." >
                    @if($books->count() > 0)
                        <ul class="list-group position-absolute w-25" style="max-height: 200px; overflow-y: auto; z-index: 1000;">
                            @foreach($books as $book)
                                <li class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <img src="{{ Storage::url('covers/'. $book->cover_image_name ) }}" alt="" class="cover-list-group">
                                        </div>
                                        <div>
                                            <strong>{{ Str::limit($book->title, 20) }}</strong><br>
                                            <span class="text-sm">{{ $book->isbn }}</span>
                                            <div class="text-sm">{{ Str::limit($book->author,20) }}</div>
                                        </div>
                                        <div>
                                            <button wire:click="addToCart({{ $book->id }})" class="btn btn-sm btn-primary text-white rounded-2" wire:key="add-to-cart-{{ $book->id }}">
                                                <span class="spinner-border spinner-border-sm" wire:loading wire:target="addToCart({{ $book->id }})"></span>
                                                <span wire:loading.remove wire:target="addToCart({{ $book->id }})">Add</span>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    
</div>

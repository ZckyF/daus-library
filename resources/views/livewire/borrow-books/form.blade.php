
<div class="row mt-3">
    <div class="mb-3 col-md-6">
        <label for="borrow_number" class="form-label">Borrow Number</label>
        <input disabled type="text" class="form-control @error('form.borrow_number') is-invalid @enderror" id="borrow_number" wire:model="form.borrow_number">
        @error('form.borrow_number') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-6">
        <label for="member_name" class="form-label">Member Name</label>
        <input disabled type="text" class="form-control @error('form.member_name') is-invalid @enderror" id="member_name" wire:model="form.member_name">
        @error('form.member_name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-4">
        <label for="borrow_date" class="form-label">Borrow Date</label>
        <input disabled type="date" class="form-control @error('form.borrow_date') is-invalid @enderror" id="borrow_date" wire:model="form.borrow_date">
        @error('form.borrow_date') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-4">
        <label for="return_date" class="form-label">Return Date</label>
        <input disabled type="date" class="form-control @error('form.return_date') is-invalid @enderror" id="return_date" wire:model="form.return_date">
        @error('form.return_date') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-4">
        <label for="returned_date" class="form-label">Returned Date</label>
        <input {{ $returnedDateIsDisabled ? 'disabled' : '' }} type="date" class="form-control @error('form.returned_date') is-invalid @enderror" id="returned_date" wire:model="form.returned_date">
        @error('form.returned_date') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-4">
        <label for="quantity" class="form-label">Quantity</label>
        <input disabled type="number" class="form-control @error('form.quantity') is-invalid @enderror" id="quantity" wire:model="form.quantity">
        @error('form.quantity') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-4">
        <label for="status" class="form-label">Status</label>
        <select class="form-select @error('form.status') is-invalid @enderror" id="status" wire:model="form.status" >
            @foreach ($statuses as $status => $value)
            @php
                $isBorrowed = $status == 'borrowed';
                $isReturnDatePassed = $form->return_date && $form->return_date < now();
                $isDisabled = $isBorrowed && $isReturnDatePassed;
            @endphp
            <option wire:click="updateReturnedDate" value="{{ $status }}" {{ $isDisabled ? 'disabled' : '' }}>{{ $value }}</option>
        @endforeach
        </select>
        @error('form.status') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-4 col-md-4">
        <label for="user" class="form-label">Added or Edited By</label>
        <input disabled id="user" type="text" value="{{ $user }}" class="form-control">
    </div>
    <div class="mb-3 col-md-12">
        <div class="card">
            <div class="card-header bg-transparent">
                <h5 class="card-title">Borrowed Books</h5>
            </div>
            <div class="card-body">
                @if($form->borrowBook->books->count() > 0)
                    <ul class="list-group">
                        @foreach($form->borrowBook->books->unique() as $book)
                            @php
                                $quantity = \App\Models\BorrowBookPivot::where('borrow_book_id', $form->borrowBook->id)
                                            ->where('book_id', $book->id)
                                            ->count();
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="text-base">{{ $book->title }}</strong><br>
                                    <span class="text-sm">{{ $book->isbn }}</span><br>
                                    <span class="text-sm">Quantity: {{ $quantity }}</span>
                                </div>
                                <div>
                                    <a wire:navigate href="{{ route('books.edit', ['isbn' => $book->isbn]) }}" class="btn btn-sm btn-primary rounded-3 text-white">
                                        <i class="bi bi-pencil"></i> Detail
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="alert alert-warning" role="alert">
                        No books have been borrowed.
                    </div>
                @endif
            
            
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end gap-3 px-5">
        <a wire:navigate href="{{ route('borrow-books') }}" class="btn btn-outline-secondary shadow-sm">
            <span class="me-1"><i class="bi bi-arrow-left"></i></span>
            <span>Back</span>
        </a>
        @if (Auth::user()->can('delete', $form->borrowBook))
            <button type="button" class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <span class="me-1"><i class="bi bi-trash"></i></span>
                <span>Delete</span>
            </button> 
        @endif
        
        @if(Auth::user()->can('update', $form->borrowBook))
            <button type="submit" class="btn btn-primary text-white  shadow-sm">
                <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                <span wire:loading.remove wire:target="save" class="me-1"><i class="bi bi-floppy"></i></span>
                <span>Save</span>
            </button>   
        @endcan
    </div>
</div>
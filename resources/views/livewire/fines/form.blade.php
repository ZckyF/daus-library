@php
    if (isset($isEditPage) && $isEditPage) {
        $readonlyForm = Auth::user()->cannot('update', $form->fine) ? 'readonly' : '';
    } else {
        $readonlyForm = Auth::user()->cannot('create', App\Models\Fine::class) ? 'readonly' : '';
    }
@endphp

<div class="row mt-3">
    @if(isset($isEditPage) && $isEditPage)
        <div class="mb-3 col-md-4">
            <label for="fine_number" class="form-label">Fine Number</label>
            <input type="text" id="fine_number" class="form-control @error('form.fine_number') is-invalid @enderror" wire:model="form.fine_number" disabled>
            @error('form.fine_number') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    @endif
    <div class="mb-3 {{ isset($isEditPage) && $isEditPage ? 'col-md-4' : 'col-md-6' }}">
        <label for="selected_member">Select Member</label>
        <div class="d-flex align-item-cente gap-4">
            <input type="text" class="form-control @error('form.selectedMember') is-invalid @enderror" id="selected_member" wire:model="form.selectedMember" readonly/>
            
            <div class="dropdown">
                <button class="btn bg-white shadow-sm rounded-3 dropdown-toggle" type="button" id="memberDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Select Member
                </button>
                <ul class="dropdown-menu p-3 overflow-y-auto" aria-labelledby="memberDropdown" style="min-width: 350px; max-height: 300px;" wire:ignore.self>
                    <input type="text" class="form-control mb-3" placeholder="Search member..." id="searchMember" wire:model.live="searchMember">
                    <div id="memberList">
                        @foreach ($members as $member)
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <span>{{ Str::limit($member->full_name, 20) }}</span>
                                <button type="button" class="btn btn-sm btn-primary choose-member text-white rounded-3" data-member-id="{{ $member->id }}" wire:click="chooseMember({{ $member->id }})" data-member-name="{{ $member->full_name }}">
                                    <span wire:loading wire:target="chooseMember({{ $member->id }})" class="spinner-border spinner-border-sm"></span>
                                    <span class="text-white" wire:loading.remove wire:target="chooseMember({{ $member->id }})">Choose</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </ul>
            </div>
        </div>
        @error('form.selectedMember') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 {{ isset($isEditPage) && $isEditPage ? 'col-md-4' : 'col-md-6' }}">
        <label for="non_member_name" class="form-label">Non Member Name</label>
        <input type="text" class="form-control @error('form.non_member_name') is-invalid @enderror" id="non_member_name" wire:model.live="nonMemberName">
        @error('form.non_member_name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3 col-md-4">
        <label for="amount">Amount</label>
        <input type="number" min="0" class="form-control @error('form.amount') is-invalid @enderror" id="amount" wire:model="form.amount">
        @error('form.amount') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-4">
        <label for="amount_paid">Amount Paid</label>
        <input type="number" min="0" class="form-control @error('form.amount_paid') is-invalid @enderror" id="amount_paid" wire:model="form.amount_paid">
        @error('form.amount_paid') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-4">
        <label for="change_amount">Change Amount</label>
        <input type="number" min="0" class="form-control @error('form.change_amount') is-invalid @enderror" id="change_amount" wire:model="form.change_amount">
        @error('form.change_amount') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-8">
        <label for="charged_at" class="form-label">Charget At</label>
        <input type="date" class="form-control @error('form.charged_at') is-invalid @enderror" id="charged_at" wire:model="form.charged_at">
        @error('form.charged_at') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-4">
        <label for="is_paid" class="form-label">Is Paid</label>
        <div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="is_paid" id="is_paid_yes" value="1" wire:model="form.is_paid">
                <label class="form-check-label" for="is_paid_yes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="is_paid" id="is_paid_no" value="0" wire:model="form.is_paid">
                <label class="form-check-label" for="is_paid_no">No</label>
            </div>
        </div>
        
        @error('form.is_paid') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    @if(isset($isEditPage) && $isEditPage)
        <div class="mb-3 col-12">
            <label for="user" class="form-label">Added Or Edited By</label>
            <input type="text" class="form-control" value="{{ $user }}" disabled>
        </div>
    @endif

    <div class="mb-3 col-12">
        <label for="reason" class="form-label">Reason</label>
        <textarea class="form-control @error('form.reason') is-invalid @enderror" id="reason" rows="5" wire:model="form.reason"></textarea>
        @error('form.reason') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="d-flex justify-content-end gap-3 px-5">
        <a wire:navigate href="{{ route('fines') }}" class="btn btn-outline-secondary shadow-sm">
            <span class="me-1"><i class="bi bi-arrow-left"></i></span>
            <span>Back</span>
        </a>
        @if ((isset($isEditPage) && $isEditPage) && Auth::user()->can('delete', $form->fine))
            <button type="button" class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <span class="me-1"><i class="bi bi-trash"></i></span>
                <span>Delete</span>
            </button> 
        @endif
        @if (Auth::user()->can('update', $form->fine) || Auth::user()->can('create', App\Models\Fine::class))
            <button type="submit" class="btn btn-primary text-white shadow-sm">
                <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                <span wire:loading.remove wire:target="save" class="me-1"><i class="bi bi-floppy"></i></span>
                <span>Save</span>
            </button> 
        @endif
    </div>
    <x-notifications.modal title="Delete Confirmation" action="delete" targetModal="deleteModal">Are you sure you want to delete this fine ? </x-notifications.modal>
</div>
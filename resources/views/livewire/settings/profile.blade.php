@push('styles')
    <style>
        .file-img {
            display: none;
        }

        .image-avatar {
            position: relative;
            overflow: hidden;
            transition: background-color 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e9ecef; /* Background color for placeholder */
            width: 100%;
            height: 400px;
            border-radius: 10px;
        }

        .image-avatar img {
            object-fit: contain;
            border-radius: 10px;
            background-position: center;
            max-height: 400px;
            /* width: 40%; */
            
        }

        .image-avatar .icon-image {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            background-color: rgba(0, 0, 0, 0.7);
            width: 100%;
            height: 100%;
            bottom: 100%;
            transition: all 0.3s ease;
            opacity: 0;
        }

        .image-avatar:hover .icon-image {
            bottom: 0;
            opacity: 1;
        }
        .image-avatar .add-image-icon {
            width: 50px;
            color: white
        }

    </style>
@endpush
<div>
    <h3 class="mb-4">Profile</h3>
    @if (session()->has('success'))
        <x-notifications.alert class="alert-success" :message="session('success')" />
    @endif
    <form wire:submit.prevent="save">
        @csrf
        <div class="row">
            <div class="mb-3 col-md-12">
                <label for="image" class="form-label py-5 image-avatar">
        
                    <img id="image-preview" src="{{ $form->avatar_name ? (is_string($form->avatar_name) ? Storage::url('avatars/' . $form->avatar_name) : $form->avatar_name->temporaryUrl()) : "" }}" alt="Image" />
        
                    <span class="icon-image">
                        <i class="bi bi-plus add-image-icon"></i>
                    </span>
                    
                </label>
                <input type="file" class="form-control file-img" id="image"  accept="image/png, image/gif, image/jpeg" wire:model="form.avatar_name" style="display: none">
                @error('form.avatar_name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-md-6">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control @error('form.username') is-invalid @enderror" id="username" wire:model="form.username">
                @error('form.username') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-md-6">
                <label for="full_name" class="form-label">Full Name</label>
                <input disabled type="text" class="form-control" id="full_name" wire:model="form.full_name">
            </div>
            <div class="mb-3 col-md-6">
                <label for="email" class="form-label">Email</label>
                <input disabled type="text" class="form-control" id="email" wire:model="form.email">
            </div>
            <div class="mb-3 col-md-6">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input disabled type="text" class="form-control" id="phone_number" wire:model="form.phone_number">
            </div>
            <div class="mb-3 col-md-12">
                <label for="address" class="form-label">Address</label>
                <textarea disabled type="address" class="form-control" id="address" wire:model="form.address" rows="5"></textarea>
                
            </div>
            <div class="d-flex justify-content-end gap-3 px-5">
                <a wire:navigate href="{{ route('settings') }}" class="btn btn-outline-secondary shadow-sm">
                    <span class="me-1"><i class="bi bi-arrow-left"></i></span>
                    <span>Back</span>
                </a> 
                <button type="submit" class="btn btn-primary text-white  shadow-sm">
                    <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                    <span wire:loading.remove wire:target="save" class="me-1"><i class="bi bi-floppy"></i></span>
                    <span>Save</span>
                </button>
            </div>
        </div>
    </form>

</div>

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#image').on('change', function(event) {
                const input = event.target;
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image-preview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            });
        });
    </script>
@endpush

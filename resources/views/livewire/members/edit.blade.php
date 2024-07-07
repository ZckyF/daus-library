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
<div class="mt-5">
    <h3 class="mb-4">Edit Member</h3>
    @if (session()->has('success'))
        <x-notifications.alert class="alert-success" :message="session('success')" />
    @endif
    <form wire:submit.prevent="save">
        @csrf
        @include('livewire.members.form')
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

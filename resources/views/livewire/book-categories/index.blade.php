@push('styles')
    <style>
        .button-add:hover a {
            color: white;
        }
        .button-add:active a{
            color: white !important;
        }
    </style>
@endpush

<div class="mt-5">
    <div class="row mb-3">
        <div class="col-md-4 col-sm-5 col-12 mb-3">
            <div class="input-group">
                <input type="text" class="form-control rounded-4 shadow-sm" placeholder="Search categories..." wire:model.live.debounce.300ms="search">
            </div>
        </div>
        <div class="col-md-8 col-sm-7 col-12">
            <div class="d-flex justify-content-sm-end justify-content-center align-items-md-end gap-3">
                <div class="dropdown-sort">
                    <select class="form-control rounded-4 shadow-sm" wire:model.live="sortBy" style="cursor: pointer;">
                        <option value="newest">Newest</option>
                        <option value="oldest">Oldest</option>
                        <option value="category-asc">Categories A-Z</option>
                        <option value="category-desc">Categories Z-A</option>
                    </select>
                </div>
                <div class="dropdown-per-page">
                    <select class="form-control rounded-4 shadow-sm" wire:model.live="perPage" style="cursor: pointer;">
                        @foreach ($optionPages as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="button-add">
                    <a wire:navigate href="{{ route('book-categories.create') }}" class="btn btn-outline-primary fw-bold shadow-sm text-center">
                        <i class="bi bi-plus-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <x-tables.table tableClass="table-striped shadow-sm" :columns="['#','Category Name','Added or Edited','Actions']"> 
            @if($categories->isEmpty())
                <x-tables.not-found />    
            @else
               @foreach ($categories as $index => $category)
                       <tr>
                           <td>{{ $categories->firstItem() + $index }}</td>
                           <td>{{ $category->category_name }}</td>
                           <td>{{ $category->user->username }}</td>
                           <td>
                                <a class="btn btn-info btn-sm rounded-3 text-white">
                                    <span><i class="bi bi-pencil"></i></span>
                                </a>
                               <button class="btn btn-danger btn-sm rounded-3">
                                    <span><i class="bi bi-trash"></i></span>
                               </button>
                               
                           </td>
                       </tr>
               @endforeach
           @endif
        </x-tables.table>
        {{ $categories->links() }}
    </div>
    
</div>


@push('script')
    <script></script>
@endpush

<!-- Modal -->
<div class="modal fade" id="addCartModal" tabindex="-1" aria-labelledby="addCartModalLabel" aria-hidden="true" wire:ignore.self>
    <form wire:submit.prevent="addToCart">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCartModalLabel">Add to Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                        @csrf
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" min="1" class="form-control" id="quantity" wire:model="quantity">
                        </div>
                        <input type="hidden" wire:model="bookModalId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary text-white">
                        <span class="spinner-border spinner-border-sm me-1" wire:loading wire:target="addToCart"></span>
                        <span wire:loading.remove wire:target="addToCart">Add</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

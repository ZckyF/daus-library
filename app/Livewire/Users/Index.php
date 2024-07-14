<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Users')]
class Index extends Component
{
    use WithPagination;

    /**
     * Search term for users.
     * 
     * @var string
     */
    public string $search = '';

    /**
     * Sort order.
     * 
     * @var string
     */
    public string $sortBy = 'newest';

    /**
     * Number of items per page.
     * 
     * @var int
     */
    public int $perPage = 12;

    /**
     * ID of the selected user.
     * 
     * @var int
     */
    public int $userId;

    /**
     * List of selected users.
     * 
     * @var array
     */
    public array $selectedUsers = [];

    /**
     * Flag to show or hide the delete selected button.
     * 
     * @var bool
     */
    public bool $showDeleteSelected = false;

    /**
     * Reset pagination on search update.
     * 
     * @return void
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination on sort order update.
     * 
     * @return void
     */
    public function updatedSortBy(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination on per page update.
     * 
     * @return void
     */
    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    /**
     * Toggle the show delete selected button.
     * 
     * @return void
     */
    public function updatedSelectedUsers(): void
    {
        $this->showDeleteSelected = !empty($this->selectedUsers);
    }

    /**
     * Set the user ID.
     * 
     * @param int $userId
     * @return void
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * Delete a user by its ID.
     * 
     * @return void
     */
    public function delete(): void
    {
        // User::destroy($this->userId);
        session()->flash('success', 'User successfully deleted.');
        $this->dispatch('closeModal');
    }

    /**
     * Delete selected users.
     * 
     * @return void
     */
    public function deleteSelected(): void
    {
        // User::whereIn('id', $this->selectedUsers)->delete();
        session()->flash('success', 'Selected users successfully deleted.');
        $this->selectedUsers = [];
        $this->showDeleteSelected = false;
        $this->dispatch('closeModal');
    }

    /**
     * Fetch users based on search and sort criteria.
     * 
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function fetchUsers()
    {
        $query = User::query();

        if ($this->search) {
            $query->where('username', 'like', '%' . $this->search . '%');
        }

        switch ($this->sortBy) {
            case 'username-asc':
                $query->orderBy('username', 'asc');
                break;
            case 'username-desc':
                $query->orderBy('username', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
        }

        return $query->paginate($this->perPage);
    }

    /**
     * Render the component.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        $users = $this->fetchUsers();
        $optionPages = [12, 24, 48, 84, 108];
        $optionSorts = [
            'newest' => 'Newest',
            'oldest' => 'Oldest',
            'username-asc' => 'Username A-Z',
            'username-desc' => 'Username Z-A'
        ];
        return view('livewire.users.index', compact('users', 'optionPages','optionSorts'));
    }
}

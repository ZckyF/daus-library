<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

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
     * Search term for roles.
     * 
     * @var string
     */
    public string $searchRole = '';

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
     * The role ID for filtering users.
     * 
     * @var int|null
     */
    public ?int $roleId = null;

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
     * Selects the bookshelf to filter books by.
     * 
     * @param int $roleId
     * @return void
     */
    public function selectRole(int $roleId): void 
    {
        $this->roleId = $roleId;
        $this->resetPage();
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
        $user = User::find($this->userId);
        if (Gate::denies('delete', $user)) {
            abort(403);
        }
        $user->delete();
        $user->update(['user_id' => Auth::user()->id]);  
        session()->flash('success', 'User successfully deleted');
    
        
        $this->dispatch('closeModal');
    }

    /**
     * Toggle active status of a user by its ID.
     * 
     * @return void
     */
    public function toggleActive(): void
    {
        $user = User::find($this->userId);
        if (Gate::denies('inactive', $user)) {
            abort(403);
        }
        $user->is_active = !$user->is_active;
        $user->save();
        session()->flash('success', $user->is_active ? 'User activated successfully' : 'User deactivated successfully');
        
        $this->dispatch('closeModal');
    }

    /**
     * Delete selected users.
     * 
     * @return void
     */
    public function deleteSelected(): void
    {
        $users = User::find($this->selectedUsers);
        if (Gate::denies('delete', $users[0])) {
            abort(403);
        }
        foreach ($users as $user) {
            $user->delete();
            $user->update(['user_id' => Auth::user()->id]);
        }

        $this->selectedUsers = [];
        session()->flash('success', 'Users successfully deleted');
        
        $this->dispatch('closeModal');
    }

    /**
     * Fetch users based on search and sort criteria.
     * 
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function fetchUsers(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = User::query();

        if ($this->search) {
            $query->where('username', 'like', '%' . $this->search . '%');
        }

        if ($this->roleId) {
            $query->whereHas('roles', fn($query) => $query->where('id', $this->roleId));
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

        return $query->where('id','!=', Auth::user()->id)
                ->whereDoesntHave('roles',fn($query)=>$query->where('name','super_admin'))
                ->paginate($this->perPage);
    }
    /**
     * Fetch roles based on search criteria.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function fetchRoles(): \Illuminate\Database\Eloquent\Collection
    {
        $query = Role::query();
        if($this->searchRole){
            $query->where('name', 'like', '%' . $this->searchRole . '%');
        }
        return $query->where('name','!=','super_admin')->get();
    }

    /**
     * Render the component.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        $users = $this->fetchUsers();
        $roles = $this->fetchRoles();
        $optionPages = [12, 24, 48, 84, 108];
        $optionSorts = [
            'newest' => 'Newest',
            'oldest' => 'Oldest',
            'username-asc' => 'Username A-Z',
            'username-desc' => 'Username Z-A'
        ];
        return view('livewire.users.index', compact('users', 'optionPages','optionSorts','roles'));
    }
}

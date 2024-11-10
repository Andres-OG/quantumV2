<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class UserTable extends Component
{
    use WithPagination;

    public $search = '';

    protected $updatesQueryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updateStatus(User $user, $status)
    {
        if ($user->id_role != 1) {
            $user->update(['status' => $status]);
        }
    }

    public function deleteUser(User $user)
    {
        if ($user->id_role != 1) {
            $user->delete();
        }
    }

    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
                     ->orWhere('email', 'like', '%' . $this->search . '%')
                     ->paginate(10);

        return view('livewire.user-table', ['users' => $users]);
    }
}

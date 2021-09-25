<?php

namespace App\Http\Livewire\Admin\Profile;

use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateProfile extends Component
{
    use WithFileUploads;

    public $state;

    public $image;

    public function mount()
    {
        $this->state = auth()->user()->only(['name', 'email']);

    }

    public function updatedImage()
    {
        $previousPath = auth()->user()->avatar;

        $path = $this->image->store('/', 'avatars');

        auth()->user()->update(['avatar' => $path]);

        Storage::disk('avatars')->delete($previousPath);

        $this->dispatchBrowserEvent('updated', ['message' => 'Profile changed successfully!']);
    }

    public function updateProfile(UpdatesUserProfileInformation $updater)
    {
        $updater->update(auth()->user(), [
            'name' => $this->state['name'],
            'email' => $this->state['email'],
        ]);

        $this->emit('nameChanged', auth()->user()->name);

        $this->dispatchBrowserEvent('updated', ['message' => 'Profile changed successfully!']);
    }


    public function render()
    {
        return view('livewire.admin.profile.update-profile');
    }
}

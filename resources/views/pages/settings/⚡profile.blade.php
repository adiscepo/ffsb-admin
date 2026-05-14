<?php

use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Profile settings')] class extends Component {
    use ProfileValidationRules;

    public string $name = '';
    public string $email = '';
    public ?string $profile_picture = '';
    protected string $storage_folder = 'avatars';

    protected $listeners = [
        'file-uploaded' => 'handleFileUpload',
    ];

    public function handleFileUpload($data) {
        $this->profile_picture = $this->storage_folder . "/" . $data;
    }

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->profile_picture = Auth::user()->profile_picture;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);
        $user->fill(['profile_picture' => $this->profile_picture]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        redirect(request()->header('Referer'));

        Flux::toast(variant: 'success', text: __('Profil mis à jour !'));
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Flux::toast(text: __('Un email de confirmation a été envoyé à votre adresse mail.'));
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        // return ! Auth::user() instanceof MustVerifyEmail
        //     || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
        // Je préfère que l'utilisateur puisse pas supprimer son compte
        return false;
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only text-lg">{{ __('Profil') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Profil')" :subheading="__('Mettre à jour votre nom ou email')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <div class="flex gap-3">
                <flux:avatar
                    size="xl"
                    :initials="auth()->user()->initials()" 
                    :src="Storage::url($this->profile_picture)"
                />
                <livewire:file-upload size="sm" :folder_storage="$this->storage_folder" />
            </div>
            <flux:input wire:model="name" :label="__('Nom')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if ($this->hasUnverifiedEmail)
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Votre email n\'est pas vérifié.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Cliquez ici pour renvoyer un mail de confirmation.') }}
                            </flux:link>
                        </flux:text>

                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit" data-test="update-profile-button">
                    {{ __('Sauvegarder') }}
                </flux:button>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <livewire:pages::settings.delete-user-form />
        @endif
    </x-pages::settings.layout>
</section>

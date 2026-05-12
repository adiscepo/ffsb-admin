<x-layouts::auth :title="__('Confirmer mot de passe')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Confirmer mot de passe')"
            :description="__('Pour être sûr que c\'est bien toi, entre ton mot de passe.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="password"
                :label="__('Mot de passe')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Mot de passe')"
                viewable
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="confirm-password-button">
                {{ __('Confirmer') }}
            </flux:button>
        </form>
    </div>
</x-layouts::auth>

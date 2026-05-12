<x-layouts::auth :title="__('Réinitialiser mot de passe')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Réinitialiser mot de passe')" :description="__('Entrez votre nouveau mot de passe')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Email Address -->
            <flux:input
                name="email"
                value="{{ request('email') }}"
                :label="__('Email')"
                type="email"
                required
                autocomplete="email"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Mot de passe')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Mot de passe')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirmer mot de passe')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirmer mot de passe')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="reset-password-button">
                    {{ __('Réinitialiser le mot de passe') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>

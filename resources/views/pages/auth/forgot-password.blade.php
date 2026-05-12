<x-layouts::auth :title="__('Mot de passe oublié')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Mot de passe oublié')" :description="__('Entre ton adresse email pour recevoir un lien de réinitialisation de mot de passe')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Adresse email')"
                type="email"
                required
                autofocus
                placeholder="email@example.com"
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
                {{ __('Lien de réinitialisation de mot de passe') }}
            </flux:button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
            <span>{{ __('Ou retourner à l\'écran de ') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('connexion') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>

@extends('layouts.app')

@section('title', 'Récupération de compte | CMKS Travel')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-950 relative overflow-hidden">
    {{-- Éléments décoratifs d'arrière-plan --}}
    <div class="absolute top-0 -left-20 w-96 h-96 bg-blue-600/20 rounded-full filter blur-[120px] animate-pulse"></div>
    <div class="absolute bottom-0 -right-20 w-96 h-96 bg-indigo-600/10 rounded-full filter blur-[120px]"></div>

    <div class="relative z-10 w-full max-w-lg p-6">
        {{-- Lien retour --}}
        <a href="{{ route('login') }}" class="inline-flex items-center text-gray-400 hover:text-white mb-8 transition-colors group">
            <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Retour à la connexion
        </a>

        <div class="bg-gray-900/50 backdrop-blur-2xl border border-white/10 rounded-3xl p-10 shadow-3xl">
            <div class="mb-8">
                <div class="w-16 h-16 bg-blue-600/20 rounded-2xl flex items-center justify-center text-blue-500 mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-black text-white mb-3">Mot de passe oublié ?</h1>
                <p class="text-gray-400 leading-relaxed">
                    Pas de panique. Indiquez-nous votre adresse email et nous vous enverrons un lien pour choisir un nouveau mot de passe.
                </p>
            </div>

            {{-- Statut de la session (Succès) --}}
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-green-400 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Votre adresse email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-5 py-4 bg-white/5 border @error('email') border-red-500 @else border-white/10 @enderror rounded-2xl text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:text-gray-600"
                           placeholder="nom@exemple.com">
                    @error('email')
                        <span class="text-red-400 text-xs mt-2 block">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full py-4 bg-white text-gray-900 font-extrabold rounded-2xl hover:bg-blue-600 hover:text-white transition-all transform active:scale-[0.98] shadow-2xl">
                    Envoyer le lien de récupération
                </button>
            </form>
        </div>
        
        <div class="mt-10 text-center">
            <p class="text-gray-500 text-sm">
                Besoin d'aide supplémentaire ? <a href="#" class="text-gray-300 hover:text-blue-400 underline decoration-blue-500/30">Contactez le support</a>
            </p>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Rejoindre l\'aventure | CMKS Travel')

@section('content')
<div class="min-h-screen flex bg-gray-950">
    {{-- Colonne gauche : Visuel (Caché sur mobile) --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
        <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&q=80" 
             class="absolute inset-0 w-full h-full object-cover" alt="Plage paradisiaque">
        <div class="absolute inset-0 bg-blue-900/30 backdrop-grayscale-[0.2]"></div>
        <div class="relative z-10 m-auto text-center px-12">
            <h2 class="text-6xl font-black text-white mb-6 leading-tight">Explorez le monde <br> avec nous.</h2>
            <p class="text-xl text-white/80 font-light max-w-md mx-auto">Créez un compte pour accéder à des offres exclusives et gérer vos réservations en un clic.</p>
        </div>
    </div>

    {{-- Colonne droite : Formulaire --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 md:p-16 bg-gray-900/50">
        <div class="w-full max-w-md">
            <div class="mb-10 text-center lg:text-left">
                <h1 class="text-4xl font-black text-white mb-2">Inscription</h1>
                <p class="text-gray-400">Commencez votre voyage aujourd'hui.</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           class="w-full px-4 py-3 bg-white/5 border @error('name') border-red-500 @else border-white/10 @enderror rounded-xl text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                           placeholder="Ex: Ephraïm Kizenga">
                    @error('name')
                        <span class="text-red-400 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Adresse Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                           class="w-full px-4 py-3 bg-white/5 border @error('email') border-red-500 @else border-white/10 @enderror rounded-xl text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                           placeholder="voyageur@world.com">
                    @error('email')
                        <span class="text-red-400 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Mot de passe</label>
                        <input type="password" name="password" required 
                               class="w-full px-4 py-3 bg-white/5 border @error('password') border-red-500 @else border-white/10 @enderror rounded-xl text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Confirmation</label>
                        <input type="password" name="password_confirmation" required 
                               class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" 
                            class="w-full py-4 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all transform active:scale-95 shadow-xl shadow-blue-500/20">
                        Créer mon compte
                    </button>
                </div>
            </form>

            <p class="mt-8 text-center text-gray-400">
                Déjà membre ? 
                <a href="{{ route('login') }}" class="text-blue-400 font-bold hover:underline ml-1">Connectez-vous</a>
            </p>
        </div>
    </div>
</div>
@endsection
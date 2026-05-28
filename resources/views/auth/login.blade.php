@extends('layouts.app')

@section('title', 'Connexion | CMKS Travel')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-900 relative overflow-hidden">
    {{-- Background avec overlay --}}
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1488085061387-422e29b40080?auto=format&fit=crop&q=80" 
             class="w-full h-full object-cover opacity-40" alt="Fond Voyage">
        <div class="absolute inset-0 bg-gradient-to-tr from-blue-900/60 to-black/60"></div>
    </div>

    {{-- Login Card --}}
    <div class="relative z-10 w-full max-w-md p-6">
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black text-white mb-2">Bon retour !</h1>
                <p class="text-gray-300">Prêt pour votre prochaine aventure ?</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-2">Adresse Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-5 py-3 bg-white/5 border @error('email') border-red-500 @else border-white/10 @enderror rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none placeholder:text-gray-500"
                           placeholder="nom@exemple.com">
                    @error('email')
                        <span class="text-red-400 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <div class="flex justify-between mb-2">
                        <label class="text-sm font-medium text-gray-200">Mot de passe</label>
                        <a href="{{ route('password.request') }}" class="text-xs text-blue-400 hover:underline">Oublié ?</a>
                    </div>
                    <input type="password" name="password" required 
                           class="w-full px-5 py-3 bg-white/5 border @error('password') border-red-500 @else border-white/10 @enderror rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                           placeholder="••••••••">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="rounded border-white/10 bg-white/5 text-blue-600 focus:ring-offset-gray-900">
                    <label for="remember" class="ml-2 text-sm text-gray-300">Se souvenir de moi</label>
                </div>

                <button type="submit" 
                        class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-1 active:scale-95">
                    Se connecter
                </button>
            </form>

            <div class="mt-8 text-center border-t border-white/10 pt-6">
                <p class="text-gray-400">
                    Pas encore de compte ? 
                    <a href="{{ route('register') }}" class="text-blue-400 font-bold hover:underline ml-1">S'inscrire</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
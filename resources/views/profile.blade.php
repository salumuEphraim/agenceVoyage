@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<section class="pt-32 pb-20">
    <div class="mx-auto max-w-3xl px-6 lg:px-8">
        <div class="panel p-8">
            <p class="text-sm uppercase tracking-[0.22em] text-cyan-300">Profil client</p>
            <h1 class="mt-4 text-4xl font-black text-white">{{ auth()->user()->name }}</h1>
            <p class="mt-2 text-slate-300">{{ auth()->user()->email }}</p>
            <p class="mt-6 text-slate-400">Votre dashboard contient deja vos itineraires, prix payes, durees de vol et statuts de reservation.</p>
        </div>
    </div>
</section>
@endsection

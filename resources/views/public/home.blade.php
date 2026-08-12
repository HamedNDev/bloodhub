@extends('layouts.public')
@section('title', 'Accueil')

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-red-700 via-red-600 to-red-800 text-white">
    <div class="max-w-6xl mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1">
            <span class="bg-red-800 text-red-200 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wide">Cameroun</span>
            <h1 class="text-4xl md:text-5xl font-bold mt-4 leading-tight">
                Accès rapide au sang,<br><span class="text-red-200">quand c'est urgent.</span>
            </h1>
            <p class="text-red-100 mt-4 text-lg leading-relaxed max-w-lg">
                VitaFlow connecte les patients, les hôpitaux, les banques de sang et les donneurs pour faciliter l'accès aux produits sanguins en temps réel.
            </p>
            <div class="flex flex-wrap gap-3 mt-8">
                <a href="{{ route('register') }}" class="bg-white text-red-700 font-bold px-6 py-3 rounded-xl hover:bg-red-50 transition shadow">
                    Rejoindre VitaFlow
                </a>
                <a href="{{ route('stocks.public') }}" class="border border-white text-white font-semibold px-6 py-3 rounded-xl hover:bg-red-800 transition">
                    Voir les disponibilités
                </a>
            </div>
        </div>
        <div class="flex-1 hidden md:block">
            <img src="{{ asset('images/blood1.jpg') }}" alt="Don de sang"
                class="rounded-2xl shadow-2xl w-full object-cover max-h-80">
        </div>
    </div>
</section>

{{-- Stats --}}
<section class="bg-white border-b">
    <div class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
        <div class="p-4">
            <p class="text-4xl font-bold text-red-700">{{ $stats['donneurs'] }}</p>
            <p class="text-gray-500 mt-1">Donneurs enregistrés</p>
        </div>
        <div class="p-4 border-x">
            <p class="text-4xl font-bold text-red-700">{{ $stats['banques'] }}</p>
            <p class="text-gray-500 mt-1">Banques de sang partenaires</p>
        </div>
        <div class="p-4">
            <p class="text-4xl font-bold text-red-700">{{ $stats['demandes'] }}</p>
            <p class="text-gray-500 mt-1">Demandes satisfaites</p>
        </div>
    </div>
</section>

{{-- Disponibilités rapides --}}
@if($banques->isNotEmpty())
<section class="bg-gray-50 py-16">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Disponibilités en temps réel</h2>
                <p class="text-gray-500 mt-1">État actuel des stocks dans les banques de sang partenaires</p>
            </div>
            <a href="{{ route('stocks.public') }}" class="text-red-700 text-sm font-medium hover:underline">Voir tout →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($banques as $banque)
            <div class="bg-white rounded-2xl shadow-sm border p-5">
                <div class="mb-4">
                    <h3 class="font-bold text-gray-800">{{ $banque->nom }}</h3>
                    <p class="text-sm text-gray-400">{{ $banque->ville }}</p>
                </div>
                <div class="grid grid-cols-4 gap-2">
                    @foreach($groupes as $groupe)
                        @php $s = $banque->stocks->where('groupe_sanguin', $groupe)->first(); @endphp
                        <div class="text-center py-2 rounded-lg {{ $s && $s->quantite > 0 ? ($s->estCritique() ? 'bg-orange-50' : 'bg-green-50') : 'bg-gray-50' }}">
                            <p class="text-xs font-bold text-gray-700">{{ $groupe }}</p>
                            <p class="text-xs {{ $s && $s->quantite > 0 ? ($s->estCritique() ? 'text-orange-500' : 'text-green-500') : 'text-gray-300' }}">
                                {{ $s ? $s->quantite : '—' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Comment ça marche --}}
<section class="py-16 bg-white" id="comment-ca-marche">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl font-bold text-gray-800">Comment ça marche ?</h2>
            <p class="text-gray-500 mt-2">En quelques étapes simples</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach([
                ['step'=>'1','icon'=>'📝','title'=>'Créez votre compte','desc'=>'Inscrivez-vous en tant que patient, donneur, hôpital ou banque de sang.'],
                ['step'=>'2','icon'=>'🩸','title'=>'Soumettez une demande','desc'=>'Indiquez le groupe sanguin et la quantité dont vous avez besoin.'],
                ['step'=>'3','icon'=>'🔔','title'=>'Les donneurs sont alertés','desc'=>'Les donneurs compatibles reçoivent une notification automatique.'],
                ['step'=>'4','icon'=>'✅','title'=>'La demande est satisfaite','desc'=>'La banque de sang confirme la disponibilité et le produit est remis.'],
            ] as $step)
            <div class="text-center p-6 rounded-2xl border hover:shadow-md transition">
                <div class="w-12 h-12 bg-red-100 text-red-700 rounded-2xl flex items-center justify-center text-xl mx-auto mb-4">{{ $step['icon'] }}</div>
                <span class="text-xs font-bold text-red-400 uppercase tracking-wider">Étape {{ $step['step'] }}</span>
                <h3 class="font-bold text-gray-800 mt-1 mb-2">{{ $step['title'] }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Qui peut utiliser VitaFlow --}}
<section class="bg-red-50 py-16">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-2xl font-bold text-gray-800">Pour qui est VitaFlow ?</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon'=>'🤒','role'=>'Patient','desc'=>'Soumettez une demande urgente et suivez son traitement en temps réel.','color'=>'bg-red-700'],
                ['icon'=>'❤️','role'=>'Donneur','desc'=>'Recevez des alertes compatibles avec votre groupe sanguin et sauvez des vies.','color'=>'bg-pink-600'],
                ['icon'=>'🏥','role'=>'Hôpital','desc'=>'Gérez vos demandes de produits sanguins et localisez les banques disponibles.','color'=>'bg-blue-600'],
                ['icon'=>'🏦','role'=>'Banque de sang','desc'=>'Gérez vos stocks, validez les dons et répondez aux demandes urgentes.','color'=>'bg-green-600'],
            ] as $actor)
            <div class="bg-white rounded-2xl shadow-sm border p-6 text-center">
                <div class="w-14 h-14 {{ $actor['color'] }} text-white rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4">{{ $actor['icon'] }}</div>
                <h3 class="font-bold text-gray-800 mb-2">{{ $actor['role'] }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $actor['desc'] }}</p>
                <a href="{{ route('register') }}" class="mt-4 inline-block text-red-700 text-sm font-medium hover:underline">S'inscrire →</a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Image section --}}
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <img src="{{ asset('images/blood2.jpg') }}" alt="Don de sang" class="rounded-2xl object-cover w-full h-56 shadow">
            <img src="{{ asset('images/blood3.jpg') }}" alt="Poche de sang" class="rounded-2xl object-cover w-full h-56 shadow">
            <img src="{{ asset('images/blood4.jpg') }}" alt="Sang lyophilisé" class="rounded-2xl object-cover w-full h-56 shadow">
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="bg-red-700 text-white py-16">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold mb-4">Ensemble, sauvons des vies.</h2>
        <p class="text-red-200 text-lg mb-8">Rejoignez VitaFlow et contribuez à améliorer l'accès aux produits sanguins au Cameroun.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('register') }}" class="bg-white text-red-700 font-bold px-8 py-3 rounded-xl hover:bg-red-50 transition shadow">
                Créer un compte gratuit
            </a>
            <a href="{{ route('stocks.public') }}" class="border border-white text-white font-semibold px-8 py-3 rounded-xl hover:bg-red-800 transition">
                Voir les disponibilités
            </a>
        </div>
    </div>
</section>

@endsection




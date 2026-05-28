<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Services\DestinationMapService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function __construct(protected DestinationMapService $destinationMapService)
    {
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateDestination($request);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated = $this->destinationMapService->enrich($validated);

        Destination::create($validated);

        return back()->with('success', 'Nouvelle destination ajoutee avec succes et repertoriee sur la carte.');
    }

    public function update(Request $request, Destination $destination): RedirectResponse
    {
        $validated = $this->validateDestination($request);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated = $this->destinationMapService->enrich($validated);

        $destination->update($validated);

        return back()->with('success', "Destination {$destination->display_name} mise a jour et synchronisee avec la carte.");
    }

    protected function validateDestination(Request $request): array
    {
        return $request->validate([
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'continent' => ['nullable', 'string', 'max:255'],
            'airport_name' => ['required', 'string', 'max:255'],
            'airport_code' => ['required', 'string', 'max:8'],
            'timezone' => ['required', 'string', 'max:120'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'hero_image' => ['nullable', 'string', 'max:2048'],
            'description' => ['nullable', 'string'],
        ]);
    }
}

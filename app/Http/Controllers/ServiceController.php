<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Controler\UserController;
class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);
        $validated ['salon_id'] = auth()->user()->salon_id;

        Service::create($validated);

        $route = auth()->user()->role === 'manager' ? 'manager.services.index': 'receptionist.services.index';

        return redirect()->route($route)->with('success', 'Service created successfully.');
    }

    public function edit(Service $service){

        return view('services.edit', compact('service'));
    }


    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $service->update($validated);

        return redirect()->route('manager.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('manager.services.index')->with('success', 'Service deleted successfully.');
    }

}

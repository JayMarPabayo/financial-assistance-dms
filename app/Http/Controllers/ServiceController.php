<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::all();
        return view('services.index', ['services' => $services]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceRequest $request)
    {
        $validatedData = $request->validated();

        // -- Check if there are no requirements
        if (!$request->has('requirements') || count($request->requirements) === 0) {
            // -- Return back with an error message
            return redirect()->back()->withInput()->withErrors([
                'requirements' => 'Must have at least one requirement',
            ]);
        }


        $service = Service::create($validatedData);

        if ($request->has('requirements')) {
            foreach ($request->requirements as $requirement) {
                $service->requirements()->create($requirement);
            }
        } else {
        }



        return redirect()->route('admin.services')->with('success', 'Service created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return view('services.show', ['service' => $service]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $validatedData = $request->validated();

        if (!$request->has('requirements') || count($request->requirements) === 0) {
            return redirect()->back()->withInput()->withErrors([
                'requirements' => 'Must have at least one requirement',
            ]);
        }

        $service->update($validatedData);

        if ($request->has('requirements')) {
            $service->requirements()->delete();

            foreach ($request->requirements as $requirement) {
                $service->requirements()->create($requirement);
            }
        }

        return redirect()->route('admin.services')->with('success', 'Service updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services')->with('success', 'Service set to unavailable.');
    }
}

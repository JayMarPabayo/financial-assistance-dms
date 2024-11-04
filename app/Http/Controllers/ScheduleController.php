<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Service;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchKey = $request->input('search');
        $service = $request->input('filter');
        $sort = $request->input('sort') ?? 'id';

        $serviceId = Service::where('name', $service)->first()?->id;

        $schedules = Schedule::where('user_id', Auth::id())
            ->when($searchKey, fn($query) => $query->search($searchKey))
            ->when(
                $serviceId,
                fn($query) =>
                $query->whereHas('request', fn($q) => $q->where('service_id', $serviceId))
            )
            ->orderBy($sort, 'asc')
            ->paginate(15);

        return view('admin.schedules', ['schedules' => $schedules, 'services' => Service::all()]);
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::user()->id;

        $requestModel = RequestModel::findOrFail($validated['request_id']);
        $requestModel->update(['status' => 'Approved']);

        Schedule::create($validated);



        return redirect()->route('schedules.index')->with('success', 'New request has been approved.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

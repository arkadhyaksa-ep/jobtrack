<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $statistics = [
            'total' => JobApplication::count(),
            'interview' => JobApplication::where('status', 'Interview')->count(),
            'offer' => JobApplication::where('status', 'Offer')->count(),
            'rejected' => JobApplication::where('status', 'Rejected')->count(),
        ];

        $allowedStatuses = ['Wishlist', 'Applied', 'Interview', 'Offer', 'Rejected', 'Withdrawn'];
        $search = $request->query('search');
        $search = is_string($search) ? trim($search) : '';
        $status = $request->query('status');
        $status = is_string($status) && in_array($status, $allowedStatuses, true) ? $status : '';

        $query = JobApplication::query();

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->where('company_name', 'like', "%{$search}%")
                    ->orWhere('job_title', 'like', "%{$search}%");
            });
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        $applications = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('job_applications.index', [
            'applications' => $applications,
            'search' => $search,
            'selectedStatus' => $status,
            'hasFilters' => $search !== '' || $status !== '',
            'statistics' => $statistics,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('job_applications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'job_url' => ['nullable', 'url', 'max:255'],
            'status' => ['required', 'in:Wishlist,Applied,Interview,Offer,Rejected,Withdrawn'],
            'applied_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        JobApplication::create($validated);

        return redirect()
            ->route('applications.index')
            ->with('success', 'Job application added successfully.');
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
    public function edit(JobApplication $jobApplication)
    {
        return view('job_applications.edit', compact('jobApplication'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobApplication $jobApplication)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'job_url' => ['nullable', 'url', 'max:255'],
            'status' => ['required', 'in:Wishlist,Applied,Interview,Offer,Rejected,Withdrawn'],
            'applied_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $jobApplication->update($validated);

        return redirect()
            ->route('applications.index')
            ->with('success', 'Job application updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobApplication $jobApplication)
    {
        $jobApplication->delete();

        return redirect()
            ->route('applications.index')
            ->with('success', 'Job application deleted successfully.');
    }
}

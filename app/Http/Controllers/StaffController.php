<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display a list of all staff members.
     */
    public function index()
    {
        // Fetch all staff members that belong to the same salon as the logged-in user
        $staffs = Staff::where('salon_id', auth()->user()->salon_id)->get();

        // Return the staff index view with the fetched data
        return view('staffs.index', compact('staffs'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        // Return a form to add a new staff member
        return view('staffs.create');
    }

    /**
     * Store a newly created staff member in the database.
     */
    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'role' => 'nullable|string|max:100',
        ]);

        // Create a new staff record linked to the current salon
        Staff::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'role' => $validated['role'] ?? null,
            'salon_id' => auth()->user()->salon_id,
        ]);

        // Redirect back with a success message
        return redirect()->route('manager.staffs.index')->with('success', 'Staff added successfully!');
    }

    /**
     * Show the form for editing an existing staff member.
     */
    public function edit(Staff $staff)
    {
        // Ensure the user can only edit staff from their own salon
        if ($staff->salon_id !== auth()->user()->salon_id) {
            abort(403, 'Unauthorized action.');
        }

        // Return the edit form view
        return view('staffs.edit', compact('staff'));
    }

    /**
     * Update an existing staff member in the database.
     */
    public function update(Request $request, Staff $staff)
    {
        // Prevent editing staff from another salon
        if ($staff->salon_id !== auth()->user()->salon_id) {
            abort(403, 'Unauthorized action.');
        }

        // Validate updated data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'role' => 'nullable|string|max:100',
        ]);

        // Update the staff record
        $staff->update($validated);

        // Redirect with a success message
        return redirect()->route('manager.staffs.index')->with('success', 'Staff updated successfully!');
    }

    /**
     * Remove a staff member from the database.
     */
    public function destroy(Staff $staff)
    {
        // Prevent deleting staff from another salon
        if ($staff->salon_id !== auth()->user()->salon_id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the record
        $staff->delete();

        // Redirect with a success message
        return redirect()->route('manager.staffs.index')->with('success', 'Staff deleted successfully!');
    }
}

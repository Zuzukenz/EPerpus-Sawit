<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of members
     */
    public function index()
    {
        $members = Member::paginate(10);
        return view('members.index', compact('members'));
    }

    /**
     * Show the form for creating a new member
     */
    public function create()
    {
        return view('members.create');
    }

    /**
     * Store a newly created member in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'membership_number' => 'required|string|unique:members',
            'join_date' => 'required|date',
        ]);

        Member::create($validated);

        return redirect()->route('members.index')
            ->with('success', 'Anggota berhasil ditambahkan!');
    }

    /**
     * Display the specified member
     */
    public function show(Member $member)
    {
        $member->load('borrowings');
        return view('members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified member
     */
    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    /**
     * Update the specified member in database
     */
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'membership_number' => 'required|string|unique:members,membership_number,' . $member->id,
            'join_date' => 'required|date',
        ]);

        $member->update($validated);

        return redirect()->route('members.index')
            ->with('success', 'Anggota berhasil diperbarui!');
    }

    /**
     * Remove the specified member from database
     */
    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Anggota berhasil dihapus!');
    }
}

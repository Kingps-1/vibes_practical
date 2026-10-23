<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of students with search.
     */
    public function index(Request $request)
    {
        $query = Student::with(['activeAllocation.room']);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('student_id_number', 'like', "%{$search}%")
                  ->orWhere('course', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('course')) {
            $query->where('course', $request->input('course'));
        }

        $students = $query->latest()->paginate(10)->withQueryString();
        $courses = Student::distinct()->pluck('course')->sort();

        return view('students.index', compact('students', 'courses'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id_number' => ['required', 'string', 'max:50', 'unique:students,student_id_number'],
            'full_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:Male,Female,Other'],
            'email' => ['required', 'email', 'max:255', 'unique:students,email'],
            'phone' => ['required', 'string', 'max:20'],
            'course' => ['required', 'string', 'max:100'],
            'semester' => ['required', 'integer', 'min:1', 'max:12'],
            'guardian_name' => ['required', 'string', 'max:255'],
            'guardian_phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'blood_group' => ['nullable', 'string', 'max:10'],
        ]);

        $student = Student::create($validated);

        return redirect()->route('students.show', $student)
            ->with('success', "Student profile for {$student->full_name} created successfully!");
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        $student->load(['allocations.room', 'maintenanceRequests.room']);
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_id_number' => ['required', 'string', 'max:50', Rule::unique('students')->ignore($student->id)],
            'full_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:Male,Female,Other'],
            'email' => ['required', 'email', 'max:255', Rule::unique('students')->ignore($student->id)],
            'phone' => ['required', 'string', 'max:20'],
            'course' => ['required', 'string', 'max:100'],
            'semester' => ['required', 'integer', 'min:1', 'max:12'],
            'guardian_name' => ['required', 'string', 'max:255'],
            'guardian_phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'blood_group' => ['nullable', 'string', 'max:10'],
        ]);

        $student->update($validated);

        return redirect()->route('students.show', $student)
            ->with('success', "Student details updated successfully!");
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        if ($student->activeAllocation) {
            return back()->with('error', "Cannot delete {$student->full_name} while they have an active room allocation. Please vacate the student first.");
        }

        $name = $student->full_name;
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', "Student record for {$name} deleted.");
    }
}

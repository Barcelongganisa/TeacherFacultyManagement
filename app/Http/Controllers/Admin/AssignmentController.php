<?php

namespace App\Http\Controllers\Admin;

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom;
use App\Models\TimeSlot;
use App\Http\Controllers\Teacher\StudentSubjectController;

class AssignmentController extends AdminBaseController
{
    public function index(Request $request)
    {
        $user = $request->user();

        $search = $request->get('search', '');
        $year = $request->get('year', '');
        $semester= $request->get('semester', '');
        $status= $request->get('status', '');
        $department = $request->get('department', '');

        $query = DB::table('teacher_subjects')
            ->join('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->join('courses', 'subjects.course_id', '=', 'courses.id')
            ->join('departments', 'courses.department_id', '=', 'departments.id')
            ->select(
                'teacher_subjects.*',
                'teachers.name',
                'teachers.department',
                'subjects.subject_name',
                'subjects.subject_code'
            )
            ->where('departments.campus_id', $user->campus_id)
            ->orderBy('teacher_subjects.id', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('teachers.name', 'like', "%{$search}%")
                    ->orWhere('subjects.subject_name', 'like', "%{$search}%")
                    ->orWhere('subjects.subject_code', 'like', "%{$search}%");
            });
        }
        if ($year) $query->where('teacher_subjects.academic_year', $year);
        if ($semester) $query->where('teacher_subjects.semester', $semester);
        if ($status) $query->where('teacher_subjects.status', $status);
        if ($department) $query->where('departments.id', $department);

        $assignments = $query->paginate(15);
        $years= DB::table('teacher_subjects')->distinct()->pluck('academic_year');
        $departments = DB::table('departments')
            ->where('campus_id', $user->campus_id)
            ->orderBy('name')
            ->get();

        return view('admin.assignments.index', compact(
            'assignments', 'search', 'year', 'semester',
            'status', 'department', 'years', 'departments'
        ));
    }

    // public function create(Request $request){
    //     $user = $request->user();
    //     $teachers = Teacher::where('status', 'active')->orderBy('name')->get();
    //     $subjects = DB::table('subjects')->where('course_id', $user->course_id)->get();
    //     $classrooms = Classroom::orderBy('room_number')->get();

    //     return view('admin.assignments.create', compact('teachers', 'subjects', 'classrooms'));
    // }
    public function create(){
    $teachers = Teacher::where('status', 'active')->orderBy('name')->get();
    $classrooms = DB::table('classrooms')->orderBy('room_number')->get();
    $subjects = collect(); // empty, loaded via AJAX
    return view('admin.assignments.create', compact('teachers', 'subjects', 'classrooms'));
}

    public function store(Request $request){
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_year' => 'nullable|string|max:20',
            'semester' => 'nullable|string|max:20',
            'section' => 'nullable|string|max:10',
            'classroom_id'=> 'nullable|exists:classrooms,id',
            'day_of_week' => 'nullable|string|max:20',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $academicYear = $validated['academic_year'] ?? date('Y');
        $semester     = $validated['semester'] ?? '1';
        $exists = TeacherSubject::where('teacher_id',$validated['teacher_id'])
            ->where('subject_id',$validated['subject_id'])
            ->where('academic_year',$academicYear)
            ->where('semester',$semester)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'This professor is already assigned to this subject for the selected academic year and semester.');
        }

        try {
            DB::beginTransaction();

            TeacherSubject::create([
                'teacher_id'=> $validated['teacher_id'],
                'subject_id' => $validated['subject_id'],
                'academic_year'=> $academicYear,
                'semester'=> $semester,
                'status' => 'active',
            ]);

            DB::table('schedules')->insert([
                'teacher_id' => $validated['teacher_id'],
                'subject_id' => $validated['subject_id'],
                'classroom_id' => $validated['classroom_id'] ?? null,
                'section' => $validated['section'] ?? null,
                'day_of_week'  => $validated['day_of_week'] ?? null,
                'day' => $validated['day_of_week'] ?? null,
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::commit();
            app(StudentSubjectController::class)->autoEnrollStudentsByYearLevel(
                $validated['subject_id'],
                $validated['teacher_id']
            );
            return redirect()->route('admin.assignments.index')->with('success', 'Assignment created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error creating assignment: ' . $e->getMessage());
        }
    }
public function availableSubjects(Request $request){
    $request->validate([
        'teacher_id'=> 'required|exists:teachers,id',
        'academic_year' => 'nullable|string',
        'semester' => 'nullable|string',
    ]);

    $academicYear = $request->academic_year ?? date('Y');
    $semester = $request->semester ?? '1';

    // Get ALL subjects already assigned for this term
    $assignedIds = DB::table('teacher_subjects')
        ->where('academic_year', $academicYear)
        ->where('semester', $semester)
        ->pluck('subject_id')
        ->toArray();

    // Only return subjects not yet assigned
    $subjects = DB::table('subjects')
        ->whereNotIn('id', $assignedIds)
        ->orderBy('subject_name')
        ->get();

    return response()->json($subjects);
}
}
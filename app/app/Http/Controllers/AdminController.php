<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\User;
use App\Models\Score;
use App\Models\Subject;
use App\Models\SchoolYear;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function read()
    {
        return view('u/setting');
    }

    public function akun()
    {
        return view('u/setting/akun');
    }

    public function tahun()
    {
        return view('u/setting/year');
    }

    public function addTahun(Request $request){

        $data = $request->validate([
            'school_year' => 'required',
        ]);

        SchoolYear::create($data);

        return redirect()->route('tahun')->with('success', 'Tahun ajaran berhasil ditambahkan');
    }

    public function kelas()
    {
        $grades = Classes::distinct()->pluck('grade');
        $school_years = SchoolYear::distinct()->pluck('school_year');
        return view('u/setting/kelas', compact('grades','school_years'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_number' => 'required',
            'grade' => 'required',
            'school_year' => 'required',

        ]);

        $schoolYear = SchoolYear::where('school_year', $data['school_year'])->first();

        $class = Classes::where('grade', $data['grade'])
            ->first();

        $user = User::where('user_number', $data['user_number']) ->first();

        if (!$class || !$user) {
            return redirect()->back()->with('error', 'Data not found. Please check your selections.');
        }

        if ($user->role === 'student') {

            $subjects = Subject::all();

            foreach ($subjects as $subject) {
                $scoreData = [
                    'classess_id' => $class->id,
                    'user_id' => $user->id,
                    'subjects_id' => $subject->id,
                    'school_years_id' => $schoolYear->id
                ];

                Score::create($scoreData);
            }

            return redirect()->route('kelas')->with('success', 'Scores created successfully');

        }elseif ($user->role === 'teacher') {

            $teacherData = [
                'classess_id'=> $class->id,
                'user_id' => $user->id,
                'school_years_id' => $schoolYear->id
            ];

                Teacher::create($teacherData);
                return redirect()->route('kelas')->with('success', 'Scores created successfully');
        }

    }

    public function addAkun(Request $request){

        $data = $request->validate([
            'name' => 'required',
            'password' => 'required',
            'user_number' => 'required',
            'role' => 'required',
        ]);

        User::create($data);

        return redirect()->route('akun')->with('success', 'Akun berhasil ditambahkan');
    }

    public function murid()
    {
        $grades = Classes::distinct()->pluck('grade');
        $schoolYears = SchoolYear::distinct()->pluck('school_year');
        return view('u/setting/murid', compact('grades','schoolYears'));
    }

    public function indexMurid(Request $request){

        $request->validate([
            'school_year' => 'required|string',
            'grade' => 'required|string',
        ]);

        $schoolYear = $request->input('school_year');
        $grade = $request->input('grade');

        $request->session()->put('selectedFilterData', [
            'school_year' => $schoolYear,
            'grade' => $grade,
        ]);

        return $this->showDataMurid($request);
    }

    public function showDataMurid(Request $request){
        $selectedFilteredData = $request->session()->get('selectedFilterData');
        $schoolYear = SchoolYear::where('school_year', $selectedFilteredData['school_year'])->first();
        $grade = Classes::where('grade', $selectedFilteredData['grade'])->first();

       $filteredData = DB::table('scores')
        ->select(DB::raw('DISTINCT users.user_number, users.name, classess.grade, school_years.school_year'))
        ->join('users', 'scores.user_id', '=', 'users.id')
        ->join('school_years', 'scores.school_years_id', '=', 'school_years.id')
        ->join('classess', 'scores.classess_id', '=', 'classess.id')
        ->where('school_years.school_year', '=',  $schoolYear->school_year)
        ->where('classess.grade', '=', $grade->grade)
        ->get();

        return view('u/setting/datamurid',compact('filteredData', 'schoolYear', 'grade'));
    }

    public function guru()
    {
        $grades = Classes::distinct()->pluck('grade');
        $schoolYears = SchoolYear::distinct()->pluck('school_year');
        return view('u/setting/guru', compact('grades','schoolYears'));
    }

    public function indexGuru(Request $request){

        $request->validate([
            'school_year' => 'required|string',
            'grade' => 'required|string',
        ]);

        $schoolYear = $request->input('school_year');
        $grade = $request->input('grade');

        $request->session()->put('selectedFilterData', [
            'school_year' => $schoolYear,
            'grade' => $grade,
        ]);

        return $this->showDataGuru($request);
    }

    public function showDataGuru(Request $request){
        $selectedFilteredData = $request->session()->get('selectedFilterData');
        $schoolYear = SchoolYear::where('school_year', $selectedFilteredData['school_year'])->first();
        $grade = Classes::where('grade', $selectedFilteredData['grade'])->first();

        $filteredData = DB::table('teachers')
        ->select(DB::raw(' users.user_number, users.name, classess.grade, school_years.school_year'))
        ->join('users', 'teachers.user_id', '=', 'users.id')
        ->join('school_years', 'teachers.school_years_id', '=', 'school_years.id')
        ->join('classess', 'teachers.classess_id', '=', 'classess.id')
        ->where('school_years.school_year', '=',  $schoolYear->school_year)
        ->where('classess.grade', '=', $grade->grade)
        ->get();

        return view('u/setting/dataguru',compact('filteredData', 'schoolYear', 'grade'));
    }

    public function deleteStudent(Request $request)
    {
        $selectedFilteredData = $request->session()->get('selectedFilterData');
        $schoolYear = SchoolYear::where('school_year', $selectedFilteredData['school_year'])->first();
        $grade = Classes::where('grade', $selectedFilteredData['grade'])->first();
        $userNumber = $request->input('user_number');

        try {
            $userId = User::where('user_number', $userNumber)->value('id');

            Score::where('user_id', $userId)
                ->where('classess_id', $grade->id)
                ->where('school_years_id', $schoolYear->id)
                ->delete();

                return redirect()->route('murid')->with('success', 'Students deleted successfully');

        } catch (\Exception $e) {
            return redirect()->route('murid')->with('error', 'Failed to delete students. Error: ' . $e->getMessage());
        }
    }

    public function deleteTeacher(Request $request){
    $selectedFilteredData = $request->session()->get('selectedFilterData');
    $schoolYear = SchoolYear::where('school_year', $selectedFilteredData['school_year'])->first();
    $grade = Classes::where('grade', $selectedFilteredData['grade'])->first();
    $userNumber = $request->input('user_number');

        try {
            $userId = User::where('user_number', $userNumber)->value('id');
            Teacher::where('user_id', $userId)
            ->where('classess_id', $grade->id)
            ->where('school_years_id', $schoolYear->id)
            ->delete();

            return redirect()->route('guru')->with('success', 'Teacher deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('guru')->with('error', 'Failed to delete teacher');
        }
    }

}

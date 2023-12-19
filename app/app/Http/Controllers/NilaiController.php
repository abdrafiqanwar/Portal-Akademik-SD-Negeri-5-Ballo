<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Score;
use Illuminate\Support\Facades\DB;
use App\Jobs\GraduateJob;
use Session;

class NilaiController extends Controller
{
    public function readNilai(){

        if (auth()->user()->role === 'teacher'){
            $grades = Teacher::join('classess', 'teachers.classess_id', '=', 'classess.id')
            ->where('teachers.user_id', auth()->user()->id)
            ->pluck('classess.grade');
            $schoolYears = SchoolYear::distinct()->pluck('school_year');
            return view("u/nilai",compact('grades','schoolYears'));
        }else{
            $grades = Classes::distinct()->pluck('grade');
            $schoolYears = SchoolYear::distinct()->pluck('school_year');
            return view("u/nilai",compact('grades','schoolYears'));
        }

    }

    public function index(Request $request){

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

        return $this->showData($request);
    }

    public function showData(Request $request){

        $selectedFilteredData = $request->session()->get('selectedFilterData');
        $schoolYear = SchoolYear::where('school_year', $selectedFilteredData['school_year'])->first();
        $grade = Classes::where('grade', $selectedFilteredData['grade'])->first();

        $query = "
    SELECT id, nama, user_number, ROUND(((task_score + UH + UAS)/3), 2) AS nilai_akhir, case
    when ROUND(((task_score + UH + UAS)/3),2) > 80 then 'Sangat Baik'
    when 71 <= ROUND(((task_score + UH + UAS)/3),2) >= 80 then 'Baik'
    when 61 <= ROUND(((task_score + UH + UAS)/3),2) >= 70 then 'Cukup'
    ELSE 'Perlu Bimbingan'
    END AS status
    FROM (
        SELECT id, nama, user_number, AVG(COALESCE(task_score, 0)) AS task_score, AVG(COALESCE(UH, 0)) AS UH, AVG(COALESCE(UAS, 0)) AS UAS
        FROM (
            SELECT users.id as id, users.name AS nama, users.user_number AS user_number, scores.task_score AS task_score, scores.UH AS UH, scores.UAS AS UAS
            FROM scores
            JOIN users ON scores.user_id = users.id
            JOIN school_years ON scores.school_years_id = school_years.id
            JOIN classess ON scores.classess_id = classess.id
            WHERE school_years.school_year = '$schoolYear->school_year' AND classess.grade = '$grade->grade'
        ) AS f
        GROUP BY user_number
    ) AS t
    ORDER BY nama
";

$results = DB::select($query);

    return view('u/datanilai',compact( 'results','schoolYear','grade'));
}

    public function graduate(Request $request) {
        $session = Session::get('selectedFilterData');

        $school_year = preg_match('/\b(Ganjil|Genap)\b/', $session['school_year'], $matches);
        $asd = SchoolYear::where('school_year', $session['school_year'])->first();
        $grade = Classes::where('grade', $session['grade'])->first();

        foreach ($request->std_id as $student_id) {
            for ($i = 1; $i <= 8; $i++) {
            GraduateJob::dispatch($student_id, $i, $grade->id, $asd->id, $matches[0]);
             }
        }
        if (auth()->user()->role === 'teacher'){
            $grades = Teacher::join('classess', 'teachers.classess_id', '=', 'classess.id')
            ->where('teachers.user_id', auth()->user()->id)
            ->pluck('classess.grade');
            $schoolYears = SchoolYear::distinct()->pluck('school_year');
            return view("u/nilai",compact('grades','schoolYears'));
        }else{
            $grades = Classes::distinct()->pluck('grade');
            $schoolYears = SchoolYear::distinct()->pluck('school_year');
            return view("u/nilai",compact('grades','schoolYears'));
        }

    }

}

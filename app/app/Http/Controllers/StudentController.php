<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Score;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    public function rekap(Request $request){
        $dataClass = Score::where('user_id', auth()->user()->id)->distinct()->pluck('classess_id');

        if ($request->isMethod('post')) {

            $request->validate([
                'grade' => 'required|string',
                'semester' =>'required|string'
            ]);

            $grade = $request->input('grade');

            $semesters = Score::select('school_years_id')->distinct('school_years_id')->get();

            $semester_even = [];
            $semester_odd = [];

            foreach ($semesters as $semester) {
                if ($semester->school_years_id % 2){
                    $semester_odd[] = $semester->school_years_id;
                } else {
                    $semester_even[] = $semester->school_years_id;
                }
            }

            if($request->semester == "Ganjil"){
                $dataScore = Score::where('user_id', auth()->user()->id)->where('classess_id', function ($query) use($grade) {

                    $query->select('classess_id')
                        ->from('scores')
                        ->where('user_id', auth()->user()->id)
                        ->where('classess_id', $grade)->first();
                })
                ->whereIn('school_years_id', $semester_odd)
                ->get();
            } else {
                $dataScore = Score::where('user_id', auth()->user()->id)->where('classess_id', function ($query) use($grade) {
                    $query->select('classess_id')
                        ->from('scores')
                        ->where('user_id', auth()->user()->id)
                        ->where('classess_id', $grade)->first();
                })
                ->whereIn('school_years_id', $semester_even)
                ->get();
            }


                return view('rekap', compact('dataClass', 'dataScore'));
        }else if ($request->isMethod('get')) {
                    $dataScore = Score::where('user_id', auth()->user()->id)->where('classess_id', function ($query) {
                        $query->select('classess_id')
                            ->from('scores')
                            ->where('user_id', auth()->user()->id)
                            ->orderBy('classess_id', 'desc')
                            ->limit(1);
                    })->where('school_years_id',function ($query1){
                        $query1->select('school_years_id')
                        ->from('scores')
                        ->where('user_id', auth()->user()->id)
                        ->orderBy('school_years_id', 'desc')
                        ->limit(1);
                    })
                    ->get();

                return view('rekap', compact('dataClass', 'dataScore'));
        }
    }

    public function tugas(Request $request){
        $dataClass = Score::where('user_id', auth()->user()->id)->distinct()->pluck('classess_id');

        if ($request->isMethod('post')) {

            $request->validate([
                'grade' => 'required|string',
                'semester' =>'required|string'
            ]);

            $grade = $request->input('grade');
            $semesters = Score::select('school_years_id')->distinct('school_years_id')->get();

            $semester_even = [];
            $semester_odd = [];

            foreach ($semesters as $semester) {
                if ($semester->school_years_id % 2){
                    $semester_odd[] = $semester->school_years_id;
                } else {
                    $semester_even[] = $semester->school_years_id;
                }
            }

            if($request->semester == "Ganjil"){
                $dataScore = Task::where('classess_id', function ($query) use($grade) {
                    $query->select('classess_id')
                        ->from('scores')
                        ->where('user_id', auth()->user()->id)
                        ->where('classess_id', $grade)->first();

                })->whereIn('school_years_id', $semester_odd)
                ->get();

                    return view('tugas', compact('dataClass', 'dataScore'));
            }elseif($request->semester == 'Genap'){

            $dataScore = Task::where('classess_id', function ($query) use($grade) {
                $query->select('classess_id')
                    ->from('scores')
                    ->where('user_id', auth()->user()->id)
                    ->where('classess_id', $grade)->first();

            }) ->whereIn('school_years_id', $semester_even)
            ->get();
            }

                return view('tugas', compact('dataClass', 'dataScore'));
       }else if ($request->isMethod('get')) {
                $dataScore = Task::where('classess_id', function($query){
                    $query->select('classess_id')
                        ->from('tasks')
                        ->orderBy('classess_id', 'desc')
                        ->limit(1);
                })->where('school_years_id',function ($query1){
                    $query1->select('school_years_id')
                    ->from('tasks')
                    ->orderBy('school_years_id', 'desc')
                    ->limit(1);
                })
                ->orderBy('deadline', 'asc')
                ->get();

                return view('tugas', compact('dataClass', 'dataScore'));
            }
    }
}

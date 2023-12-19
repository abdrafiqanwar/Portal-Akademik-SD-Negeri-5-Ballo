<?php

namespace App\Jobs;

use App\Models\Score;
use App\Models\SchoolYear;
use App\Models\Classes;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GraduateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $studentId;
    protected $subjectId;
    protected $classId;
    protected $schoolYearId;
    protected $schoolYear;

    public function __construct($studentId, $subjectId, $classId, $schoolYearId, $schoolYear)
    {
        $this->studentId = $studentId;
        $this->subjectId = $subjectId;
        $this->classId = $classId;
        $this->schoolYearId = $schoolYearId;
        $this->schoolYear = $schoolYear;
    }

    public function handle()
    {
        $nextSchoolYearId = $this->schoolYearId + 1;
        $nextSchoolYear = SchoolYear::find($nextSchoolYearId);

        $nextClassId = $this->classId + 1;
        $nextClass = Classes::find($nextClassId);

        if ($nextSchoolYear && $nextClass) {
            $existingScore = Score::where([
                'user_id' => $this->studentId,
                'subjects_id' => $this->subjectId,
                'classess_id' => $this->classId + ($this->schoolYear == "Ganjil" ? 0 : 1),
                'school_years_id' => $nextSchoolYearId,
            ])->first();

            if (!$existingScore) {
                $score = new Score();
                $score->user_id = $this->studentId;
                $score->subjects_id = $this->subjectId;
                $score->classess_id = $this->classId + ($this->schoolYear == "Ganjil" ? 0 : 1);
                $score->school_years_id = $nextSchoolYearId;
                $score->save();
            }
        } else {
            return redirect()->back()->with('error', 'Tidak dapat menemukan tahun ajaran berikutnya.');
        }
    }

}

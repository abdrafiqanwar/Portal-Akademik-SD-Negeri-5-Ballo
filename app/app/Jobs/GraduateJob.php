<?php

namespace App\Jobs;

use App\Models\Score;
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
        $existingScore = Score::where([
            'user_id' => $this->studentId,
            'subjects_id' => $this->subjectId,
            'classess_id' => $this->classId + ($this->schoolYear == "Ganjil" ? 0 : 1),
            'school_years_id' => $this->schoolYearId + 1,
        ])->first();

        if (!$existingScore) {
            $score = new Score();
            $score->user_id = $this->studentId;
            $score->subjects_id = $this->subjectId;
            $score->classess_id = $this->classId + ($this->schoolYear == "Ganjil" ? 0 : 1);
            $score->school_years_id = $this->schoolYearId + 1;
            $score->save();
        }
    }
}

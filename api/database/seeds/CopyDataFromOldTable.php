<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class CopyDataFromOldTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    const DIFFICULTIES = [
        'Easy' => 0,
        'Medium' => 1,
        'Hard' => 2
    ];

    const STATES = [
        'Finished' => 2,
        'Not Finished' => 1,
        'Not finished' => 1,
        'Not confirmed' => 0
    ];

    public function run()
    {
        $erp_db = env("DB_ERP", "admin_erp");
        $tutorials = DB::table($erp_db.'.TRAINING_TUTORIALS')->get();
        foreach ($tutorials as $t) {
            $t = (array)$t;
            $tutorial = \App\Tutorial::create([
                'id' => $t['ID'],
                'system' => $t['SYSTEM'],
                'title' => $t['TITLE'],
                'author' => $t['AUTHOR_ID'],
            ]);

            $tutorial->created_at = $t['CREATE_DATE'];
            $tutorial->updated_at = $t['EDIT_DATE'];
            $tutorial->deleted_at = $t['DELETED'] ? date('y-m-d') : null;
            $tutorial->save();
            $questions = DB::table($erp_db.'.TRAINING_TUT_QUESTIONS')
                ->where('TUT_ID', $t['ID'])->get();
            foreach ($questions as $q) {
                $q = (array)$q;
                $question = $tutorial->questions()->create([
                    'id' => $q['ID'],
                    'question' => $q['QUESTION'],
                    'answer1' => $q['OPTION_1'],
                    'answer2' => $q['OPTION_2'],
                    'answer3' => $q['OPTION_3'],
                    'answer4' => $q['OPTION_4'],
                    'correct_answer' => $q['CORRECT'],
                    'difficulty' => self::DIFFICULTIES[$q['DIFFICULT']],
                    'author_id' => $q['AUTHOR_ID'],
                    'verified' => $q['VERIFIED']
                ]);
                $question->created_at = $q['EDIT_DATE'];
                $question->updated_at = $q['EDIT_DATE'];
                $question->deleted_at = $q['DELETED'] ? date('y-m-d') : null;
                $question->save();
            }

            $moderators = DB::table($erp_db.'.TRAINING_TUT_MODERATOR')
                ->where('TUT_ID', $t['ID'])->get();
            foreach ($moderators as $m) {
                $m = (array)$m;
                $moderator = $tutorial->moderators()->create([
                    'moderator_id' => $m['MODERATOR_ID']
                ]);
                $moderator->created_at = $m['EDIT_DATE'];
                $moderator->updated_at = $m['EDIT_DATE'];
                $moderator->deleted_at = $m['DELETED'] ? date('y-m-d') : null;
                $moderator->save();
            }

            $observers = DB::table($erp_db.'.TRAINING_TUT_OBSERVER')
                ->where('TUT_ID', $t['ID'])->get();

            foreach ($observers as $o) {
                $o = (array)$o;
                $observer = $tutorial->observers()->create([
                    'observer_id' => $o['OBSERVER_ID']
                ]);
                $observer->created_at = $o['EDIT_DATE'];
                $observer->updated_at = $o['EDIT_DATE'];
                $observer->deleted_at = $o['DELETED'] ? date('y-m-d') : null;
                $observer->save();
            }
        }

        $trainings = \Illuminate\Support\Facades\DB::table($erp_db.'.TRAINING')->get();
        foreach ($trainings as $t) {
            $t = (array)$t;
            $training = \App\Training::create([
                'id' => $t['ID'],
                'originator_id' => $t['ORIGINATOR_ID'],
                'title' => $t['TITLE'],
                'description' => $t['DESCRIPTION'],
                'reference' => $t['REFERENCE'],
                'is_test_exam' => $t['TEST_EXAM'],
                'question_number' => $t['QUESTION_NUM'],
                'status' => self::STATES[$t['STATE']],
                'pass_score' => $t['THRESHOLD'],
                'exam_time' => $t['ON_TIME'],
                'tutorial_id' => $t['TUTORIAL']
            ]);

            $training->created_at = $t['CREATE_DATE'];
            $training->updated_at = $t['EDIT_DATE'];
            $training->deleted_at = $t['DELETED'] ? date('y-m-d') : null;
            $training->save();

            $observers = \Illuminate\Support\Facades\DB::table($erp_db.'.TRAINING_OBSERVER')
                ->where('TRAINING_ID', $t['ID'])->get();

            foreach ($observers as $o) {
                $o = (array)$o;
                $observer = $training->observers()->create([
                    'id' => $o['ID'],
                    'observer_id' => $o['OBSERVER_ID']
                ]);

                $observer->created_at = $o['EDIT_DATE'];
                $observer->updated_at = $o['EDIT_DATE'];
                $observer->deleted_at = $o['DELETED'] ? date('y-m-d') : null;
                $observer->save();
            }

            $participants = \Illuminate\Support\Facades\DB::table($erp_db.'.TRAINING_PARTICIPANT')
                ->where('TRAINING_ID', $t['ID'])->get();

            foreach ($participants as $p) {
                $p = (array)$p;
                $participant = $training->participants()->create([
                    'id' => $p['ID'],
                    'start_date' => $p['DATE'],
                    'end_date' => $p['END_DATE'],
                    'participant_id' => $p['PARTICIPANT_ID'],
                    'score' => $p['SCORE'],
                    'attempt' => $p['ATTEMPT'],
                    'status' => self::STATES[$p['STATE']],
                    'examiner_id' => $p['CERTIFIER_ID'],
                ]);

                $participant->created_at = $p['EDIT_DATE'];
                $participant->updated_at = $p['EDIT_DATE'];
                $participant->deleted_at = $p['DELETED'] ? date('y-m-d') : null;
                $participant->save();
            }

        }
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObserverTrainingView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            DROP VIEW IF EXISTS observer_training_view
        ');
        DB::statement('
            CREATE VIEW observer_training_view AS
                SELECT 
                    o.id, 
                    o.training_id, 
                    o.observer_id, 
                    o.created_at, 
                    o.deleted_at, 
                    o.updated_at 
                FROM observer_training o
            UNION ALL
                SELECT 
                    NULL, 
                    t.id as training_id, 
                    t.originator_id as observer_id, 
                    t.created_at, 
                    t.deleted_at, 
                    t.updated_at 
                FROM trainings t
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS observer_training_view');
    }
}

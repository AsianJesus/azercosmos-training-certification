<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Mail;

class ExampleJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $name;
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send('mail', ['name' => $this->name, 'Web dev blog'], function ($message) {
            $message->to('fruiteatearth@gmail.com', 'To Elvin')->subject('Test mail');
            $message->from('fruiteatearth@gmail.com', 'Elvin');
        });
    }
}

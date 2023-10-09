<?php

namespace App\Jobs;


use App\Models\Useraccess;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class StudentUserAccess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chunk;

    public function __construct($chunk)
    {
        $this->chunk = $chunk;
    }

    public function handle()
    {
        Useraccess::insert($this->chunk);
    }
}

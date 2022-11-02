<?php

namespace App\Queue\Middleware;

use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\Jobs\Job;

class RateLimited 
{
  /** 
   * @param Job $job
   */
  public function handle($job, $next) {
    $jobGroup = $job->getJobGroup();

    $lock = Cache::lock($jobGroup, 30);

    if ($lock->get()) {
      return $next($job);
    }
    return $job->release(30);
  }
}
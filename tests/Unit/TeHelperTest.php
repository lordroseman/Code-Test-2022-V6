<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use DTApi\Helpers\TeHelper;


class TeHelperTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_will_expired_at_less_than_90()
    {

        $dueTime = Carbon::parse('2021-01-07 08:00:00');
        $created_at = Carbon::parse('2021-01-05 08:00:00');
        //difference is 72 hours

        $willExpiredAt = TeHelper::willExpireAt($dueTime, $created_at);
        $this->assertEquals($dueTime->format('Y-m-d H:i:s'), $willExpiredAt);
    }


    public function test_will_expired_at_less_than_24()
    {

        $dueTime = Carbon::parse('2021-01-05 12:00:00');
        $created_at = Carbon::parse('2021-01-05 08:00:00');
        //difference is 4 hours

        $willExpiredAt = TeHelper::willExpireAt($dueTime, $created_at);
        $this->assertEquals($created_at->addMinutes(90)->format('Y-m-d H:i:s'), $willExpiredAt);
    }
}

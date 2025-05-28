<?php

use PHPUnit\Framework\TestCase;
use Doughmii\DevUtils\Time\TimeHumanizer;

class TimeHumanizerTest extends TestCase
{
    public function testJustNow()
    {
        $now = new DateTimeImmutable();
        $aFewSecondsAgo = $now->modify('-5 seconds');
        $this->assertSame('just now', TimeHumanizer::diffForHumans($aFewSecondsAgo, $now, 'en'));
        $this->assertSame('刚刚', TimeHumanizer::diffForHumans($aFewSecondsAgo, $now, 'zh'));
    }

    public function testSecondsAgo()
    {
        $now = new DateTimeImmutable();
        $secondsAgo = $now->modify('-30 seconds');
        $this->assertSame('30 seconds ago', TimeHumanizer::diffForHumans($secondsAgo, $now, 'en'));
        $this->assertSame('30 秒前', TimeHumanizer::diffForHumans($secondsAgo, $now, 'zh'));
    }

    public function testMinutesAgo()
    {
        $now = new DateTimeImmutable();
        $minutesAgo = $now->modify('-5 minutes');
        $this->assertSame('5 minutes ago', TimeHumanizer::diffForHumans($minutesAgo, $now, 'en'));
        $this->assertSame('5 分钟前', TimeHumanizer::diffForHumans($minutesAgo, $now, 'zh'));
    }

    public function testHoursAgo()
    {
        $now = new DateTimeImmutable();
        $hoursAgo = $now->modify('-3 hours');
        $this->assertSame('3 hours ago', TimeHumanizer::diffForHumans($hoursAgo, $now, 'en'));
        $this->assertSame('3 小时前', TimeHumanizer::diffForHumans($hoursAgo, $now, 'zh'));
    }

    public function testDaysAgo()
    {
        $now = new DateTimeImmutable();
        $daysAgo = $now->modify('-2 days');
        $this->assertSame('2 days ago', TimeHumanizer::diffForHumans($daysAgo, $now, 'en'));
        $this->assertSame('2 天前', TimeHumanizer::diffForHumans($daysAgo, $now, 'zh'));
    }

    public function testWeeksAgo()
    {
        $now = new DateTimeImmutable();
        $weeksAgo = $now->modify('-3 weeks');
        $this->assertSame('3 weeks ago', TimeHumanizer::diffForHumans($weeksAgo, $now, 'en'));
        $this->assertSame('3 周前', TimeHumanizer::diffForHumans($weeksAgo, $now, 'zh'));
    }

    public function testMonthsAgo()
    {
        $now = new DateTimeImmutable();
        $monthsAgo = $now->modify('-2 months');
        $this->assertSame('2 months ago', TimeHumanizer::diffForHumans($monthsAgo, $now, 'en'));
        $this->assertSame('2 个月前', TimeHumanizer::diffForHumans($monthsAgo, $now, 'zh'));
    }

    public function testYearsAgo()
    {
        $now = new DateTimeImmutable();
        $yearsAgo = $now->modify('-5 years');
        $this->assertSame('5 years ago', TimeHumanizer::diffForHumans($yearsAgo, $now, 'en'));
        $this->assertSame('5 年前', TimeHumanizer::diffForHumans($yearsAgo, $now, 'zh'));
    }

    public function testFromNow()
    {
        $now = new DateTimeImmutable();

        $in30Seconds = $now->modify('+30 seconds');
        $this->assertSame('in 30 seconds', TimeHumanizer::diffForHumans($in30Seconds, $now, 'en'));
        $this->assertSame('30 秒后', TimeHumanizer::diffForHumans($in30Seconds, $now, 'zh'));

        $in2Days = $now->modify('+2 days');
        $this->assertSame('in 2 days', TimeHumanizer::diffForHumans($in2Days, $now, 'en'));
        $this->assertSame('2 天后', TimeHumanizer::diffForHumans($in2Days, $now, 'zh'));

        $in1Year = $now->modify('+1 year');
        $this->assertSame('in 1 year', TimeHumanizer::diffForHumans($in1Year, $now, 'en'));
        $this->assertSame('1 年后', TimeHumanizer::diffForHumans($in1Year, $now, 'zh'));
    }

    public function testFromNowShortcut()
    {
        $future = (new DateTimeImmutable())->modify('+1 day');
        $this->assertSame('in 1 day', TimeHumanizer::fromNow($future, 'en'));
    }
}

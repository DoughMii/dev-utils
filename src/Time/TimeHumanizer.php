<?php

namespace Doughmii\DevUtils\Time;

class TimeHumanizer
{
    protected static array $phrases = [];

    protected static array $units = [
        'week'   => 604800,
        'day'    => 86400,
        'hour'   => 3600,
        'minute' => 60,
        'second' => 1,
    ];

    public static function diffForHumans(\DateTimeInterface $from, ?\DateTimeInterface $to = null, string $locale = 'en'): string
    {
        $to = $to ?? new \DateTimeImmutable();
        $diffInSeconds = $to->getTimestamp() - $from->getTimestamp();
        $absDiff = abs($diffInSeconds);
        $isPast = $diffInSeconds >= 0;

        $phrases = self::getPhrases($locale);

        if ($absDiff < 10) {
            return $phrases['just_now'] ?? 'just now';
        }

        // 判断是否是跨月或跨年（更精确）
        $interval = $from->diff($to);

        if ($interval->y >= 1) {
            $count = $interval->y;
            return self::format($phrases, 'year', $count, $isPast);
        }

        if ($interval->m >= 1) {
            $count = $interval->m;
            return self::format($phrases, 'month', $count, $isPast);
        }

        // 对于 < 1 月内的差异，用秒单位来判断
        foreach (self::$units as $unit => $secondsPerUnit) {
            if ($absDiff >= $secondsPerUnit) {
                $count = floor($absDiff / $secondsPerUnit);
                return self::format($phrases, $unit, $count, $isPast);
            }
        }

        return $phrases['just_now'] ?? 'just now';
    }

    public static function fromNow(\DateTimeInterface $dateTime, string $locale = 'en'): string
    {
        return self::diffForHumans($dateTime, new \DateTimeImmutable(), $locale);
    }

    protected static function format(array $phrases, string $unit, int $count, bool $isPast): string
    {
        $key = ($count === 1) ? $unit : $unit . 's'; 
        $key .= $isPast ? '_ago' : '_from_now';

        // $template = $phrases[$key] ?? "{count} $unit" . ($count > 1 ? 's' : '');
        $template = $phrases[$key] ?? "{count} $unit" . ($count === 1 ? '' : 's') . ($isPast ? ' ago' : ' from now');
        return str_replace('{count}', $count, $template);
    }

    protected static function getPhrases(string $locale): array
    {
        if (!isset(self::$phrases[$locale])) {
            $path = __DIR__ . "/lang/$locale.php";
            self::$phrases[$locale] = is_file($path) ? require $path : require __DIR__ . "/lang/en.php";
        }
        return self::$phrases[$locale];
    }
}

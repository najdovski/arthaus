<?php

namespace App\Helpers;
use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class AppHelper
{
    /**
     * Random lorem ipsum text
     */
    public static function randomLoremIpsum(): string
    {
        $loremText = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque delectus nobis veritatis, alias suscipit deleniti praesentium et libero odio earum blanditiis. Error vel quo ipsum hic earum! Eos itaque magni reiciendis nulla deserunt cumque distinctio unde aut! Sint veniam animi cum aperiam illo fugiat! Voluptate quasi dicta eaque odio provident?';
        
        $randomEnd = rand(35, strlen($loremText));
        return substr($loremText, 0, $randomEnd);
    }

    /**
     * Trim a given string
     */
    public static function trimString(string $string, int $charLength = 100): string
    {
        $trimmed = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $charLength));

        if (strlen($string) >= $charLength) {
            $trimmed .= '...';
        }

        return $trimmed;
    }

    /**
     * Format date
     */
    public static function formatDate(string $date = null): string
    {
        if (!$date) {
            return '-';
        }

        $dateObj = new DateTime($date);
        return $dateObj->format('d-m-Y');
    }

    /**
     * Format date and time for displaying
     */
    public static function formatDateTime(string|null $dateTime): string
    {
        if (!$dateTime) {
            return '-';
        }

        $formated = (new DateTime($dateTime))->format('d-m-Y H:i');

        return $formated;
    }

    /**
     * Format date and time for a datetime-timezone input field
     */
    public static function formatDateTimeInput(string|null $dateTime, bool $setTimeZone = false): string
    {
        if (!$dateTime) {
            return '-';
        }

        if ($setTimeZone) {
            $formated = (self::currentTimeWithTimezone())->format('Y-m-d H:i');
        } else {
            $formated = (new DateTime($dateTime))->format('Y-m-d H:i');
        }

        return str_replace(' ', 'T', $formated);
    }

    /**
     * Time difference between two timestamps
     */
    public static function timeDiff(string $firstTimestamp, string $secondTimestamp): string
    {
        $carbonFirstTimestamp = new Carbon($firstTimestamp);
        $carbonSecondTimestamp = new Carbon($secondTimestamp);

        $diffMinutes = $carbonFirstTimestamp->diffInMinutes($carbonSecondTimestamp);

        // Prepend zeros if the values are single integers
        $hours = str_pad(intdiv($diffMinutes, 60), 2, '0', STR_PAD_LEFT);
        $minutes = str_pad(($diffMinutes % 60), 2, '0', STR_PAD_LEFT);
        return $hours.':'. $minutes;
    }

    /**
     * Get current time with timezone added
     */
    private static function currentTimeWithTimezone(): DateTime
    {
        if (Session::get('timezone')) {
            $timezone = Session::get('timezone');
            $now = new DateTime(now());
            $now->setTimezone(new DateTimeZone($timezone));
            return $now;
        }

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    
        // For local testing purposes only, for example when the clients IP is "::1"
        if (!filter_var($ip, FILTER_VALIDATE_IP) || $ip === '::1') {
            $ip = file_get_contents('https://api.my-ip.io/ip');
        }
    
        $url = 'http://worldtimeapi.org/api/ip/'.$ip;
        $apiResponse = file_get_contents($url);
        $timezone = json_decode($apiResponse, true)['timezone'];
        Session::put('timezone', $timezone);
        $now = new DateTime(now());
        $now->setTimezone(new DateTimeZone($timezone));

        return $now;
    }
}

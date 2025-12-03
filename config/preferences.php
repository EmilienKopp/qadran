<?php

  define('P_TIMEZONE', 'timezone');
  define('P_FORMATS', 'formats');
  define('P_DATE_FORMAT', 'date');
  define('P_TIME_FORMAT', 'time');
  define('P_DATETIME_FORMAT', 'datetime');
  define('P_WORKDAY', 'workday');
  define('P_WORKDAY_START', 'start');
  define('P_WORKDAY_END', 'end');
  define('P_AUTO_CLOCK_OUT_TIME', 'auto_clock_out_time');


  return [
    'timezone' => env('APP_TIMEZONE', 'UTC'),
    'timezones' => [],
    'formats' => [
        'date' => 'Y-m-d',
        'time' => 'H:i:s',
        'datetime' => 'Y-m-d H:i:s',
    ],
    'workday' => [
        'start' => '09:00:00',
        'end' => '18:00:00',
    ],
    'auto_clock_out_time' => '20:30:00',
  ];
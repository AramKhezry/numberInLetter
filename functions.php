<?php

function numberInLetters($number) {
  
  $separator   =  $hyphen      = $conjunction = ' و ';
  $negative    = 'منفی ';
  $decimal     = ' ممیز ';
  $dictionary  = array(
   0                   => 'صفر',
   1                   => 'یک',
   2                   => 'دو',
   3                   => 'سه',
   4                   => 'چهار',
   5                   => 'پنج',
   6                   => 'شش',
   7                   => 'هفت',
   8                   => 'هشت',
   9                   => 'نه',
   10                  => 'ده',
   11                  => 'یازده',
   12                  => 'دوازده',
   13                  => 'سیزده',
   14                  => 'چهارده',
   15                  => 'پانزده',
   16                  => 'شانزده',
   17                  => 'هفده',
   18                  => 'هجده',
   19                  => 'نوزده',
   20                  => 'بیست',
   30                  => 'سی',
   40                  => 'چهل',
   50                  => 'پنجاه',
   60                  => 'شصت',
   70                  => 'هفتاد',
   80                  => 'هشتاد',
   90                  => 'نود',
   100                 => 'صد',
   200                 => 'دویست',
   300                 => 'سیصد',
   400                 => 'چهارصد',
   500                 => 'پانصد',
   600                 => 'ششصد',
   700                 => 'هفتصد',
   800                 => 'هشتصد',
   900                 => 'نهصد',
   1000                => 'هزار',
   1000000             => 'میلیون',
   1000000000          => 'میلیارد',
   1000000000000       => 'بیلیون',
   1000000000000000    => 'بیلیارد',
   1000000000000000000 => 'تریلیون'
  );
  
  if (!is_numeric($number)) {
    return false;
  }
  
  if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
    // overflow
    trigger_error(
     'فقط اعداد بین ' . PHP_INT_MAX . ' و ' . PHP_INT_MAX . ' مجاز میباشد',
     E_USER_WARNING
    );
    return false;
  }
  
  if ($number < 0) {
    return $negative . numberToWords(abs($number));
  }
  
  $string = $fraction = null;
  
  if (strpos($number, '.') !== false) {
    list($number, $fraction) = explode('.', $number);
  }
  
  switch (true) {
    case $number < 21:
      $string = $dictionary[$number];
      break;
    case $number < 100:
      $tens   = ((int) ($number / 10)) * 10;
      $units  = $number % 10;
      $string = $dictionary[$tens];
      if ($units) {
        $string .= $hyphen . $dictionary[$units];
      }
      break;
    case $number < 1000:
      $hundreds  = $number / 100;
      $remainder = $number % 100;
      $string = $dictionary[(int) $hundreds * 100];
      if ($remainder) {
        $string .= $conjunction . numberToWords($remainder);
      }
      break;
    default:
      $baseUnit = pow(1000, floor(log($number, 1000)));
      $numBaseUnits = (int) ($number / $baseUnit);
      $remainder = $number % $baseUnit;
      $string = numberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
      if ($remainder) {
        $string .= $remainder < 100 ? $conjunction : $separator;
        $string .= numberToWords($remainder);
      }
      break;
  }
  
  if (null !== $fraction && is_numeric($fraction)) {
    $string .= $decimal;
    $words = array();
    foreach (str_split((string) $fraction) as $number) {
      $words[] = $dictionary[$number];
    }
    $string .= implode(' ', $words);
  }
  
  return $string;
}

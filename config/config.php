<?php
@session_start();
@ini_set('session.gc_maxlifetime', 365 * 24 * 60 * 60);
@ini_set('session.cookie_lifetime', 365 * 24 * 60 * 60);

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('UTC');

//LOCAL
// define("APP_TITLE", "Gotap");
// define('PRE_FIX', APP_TITLE);
// define("BASE_URL", 'http://localhost:8000/');
// define("IMAGE_BASE_URL", BASE_URL);
// define("DB_HOST", "localhost");
// define("DB_USER", "root");
// define("DB_PASS", "");
// define("DB_NAME", "gotaps");

//LIVE
define("APP_TITLE", "Gotap");
define('PRE_FIX', APP_TITLE);
define("BASE_URL", 'http://gocoompany.com/');
define("IMAGE_BASE_URL", BASE_URL);
define("DB_HOST", "localhost");
define("DB_USER", "u986011478_gotap");
define("DB_PASS", "Gotap123!");
define("DB_NAME", "u986011478_gotap");

//SMTP (EMAIL) 
// define("SMTP_HOST", "smtp.hostinger.com");
// define("SMTP_PORT", "587");
// define("SMTP_EMAIL", "noreply@gocoompany.com");
// define("SMTP_PASS", "Gotaps2022!");

// LOCAL
define("SMTP_HOST", "smtp.gmail.com");
define("SMTP_PORT", "587");
define("SMTP_EMAIL", "webnerds777@gmail.com");
define("SMTP_PASS", 'dilitsjrzaitughl');

//FIREBASE

//google map javascript api key
define('Google_Map_Key', 'AIzaSyB8ggkggvWX33gOBeDtm1FV3MXH4t7Dsp8');


//google map javascript api key
define("MAPS_API_KEY", "AIzaSyB8ggkggvWX33gOBeDtm1FV3MXH4t7Dsp8");

define("STRIPE_PUBLISHABLE", "pk_test_51HzmwfHwsZrhyWYnfqubH8HWv0T37nZ6ckOGnqyXqU5jZzHq5rOJTdAWEavPwMzxws4TW2hSj9TaSSLAc4lzH0dg00LkQASGtY");

define("STRIPE_SECRET", 'sk_test_51HzmwfHwsZrhyWYnzVsaZjFTc4pAISWP87LemBiHY4QYrVO1nDQO2rqI5IURbeUTsMWWfUxvgO6Jk25dHXPJjYNa009B2tbWg0');


define('JWT_SECRET', 'talha!ReT423*&^%OWL#talha');

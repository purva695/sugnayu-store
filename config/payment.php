<?php
// Add Razorpay test credentials first. Never expose the Key Secret in browser JavaScript.
if (!defined('SITE_URL')) define('SITE_URL', 'https://YOUR-LIVE-DOMAIN.example'); // Replace with your actual live site URL, without trailing slash.
if (!defined('RAZORPAY_KEY_ID')) define('RAZORPAY_KEY_ID', 'rzp_test_REPLACE_WITH_KEY_ID');
if (!defined('RAZORPAY_KEY_SECRET')) define('RAZORPAY_KEY_SECRET', 'REPLACE_WITH_KEY_SECRET');

<?php
/**
 * NR TRADING - Logout
 */

require_once 'includes/init.php';

// Destroy user session
if (isset($_SESSION[USER_SESSION])) {
    unset($_SESSION[USER_SESSION]);
}

// Redirect to homepage
redirect(SITE_URL);

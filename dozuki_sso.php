<?php

/**
 * Base implementation of dozuki authentication
 *
 * from here: https://github.com/iFixit/dozuki-single-sign-on
 */
class DozukiAuthentication {
   public static function authenticate() {
      if (!static::isLoggedIn()) {
         static::sendToLogin();
      } else {
         header("Location: " . self::generateSignedUrl('login'));
      }

      exit();
   }

   /**
    * Call this function to have the currently logged in user (as returned by 
    * getUserInfo() logged out of Dozuki.
    */
   public static function logout() {
      if (!static::isLoggedIn()) {
         return;
      }

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,  self::generateSignedUrl('logout'));
      curl_setopt($ch, CURLOPT_POST, true);
      curl_exec($ch);
      curl_close($ch);
   }

   protected static function generateSignedUrl($type = 'login') {
      $userInfo = static::getUserInfo();
      $params = array_merge($userInfo, array(
         't' => time()
      ));

      $testMode = static::$forceTestMode;
      $destinationURL = 'http://' . static::$dozukiSite .
       "/Guide/User/remote_$type" . ($testMode ? '/test' : '') . '?';

      $query = http_build_query($params);
      $hash = sha1($query . static::$secret);
      $query .= "&hash=" . $hash;

      return $destinationURL . $query;
   }

   // ========================================================
   // Only Make Changes below this line
   // ========================================================
   protected static $dozukiSite = 'yoursitename.dozuki.com';
   protected static $secret = 'abcdefghijklmnopqrstuvwxyz01234567890';
   protected static $forceTestMode = false;

   /**
    * Change this function to return the specified info for the currently
    * logged in user. This function will only be called if is LoggedIn()
    * returns true.
    */
   protected static function getUserInfo() {
      return array(
         // A string that uniquely identifies the logged in user
         // i.e. anything that doesn't change: (id, email, username)
         'userid' => '123456',
         'email' => 'user@example.com',
         // A users's real name, if available
         'name' => 'First Last'
      );
   }

   /**
    * Change this function to return true if a user is logged into your site
    * and false if not.
    */
   protected static function isLoggedIn() {
      // something like:
      // return isset($_SESSION['userid']);
   }

   /**
    * This function should redirect the user to your login page, or just render
    * it directly.
    */
   protected static function sendToLogin() {
      // Redirect the user to your login page.
      // Or, just render the login page
   }
}

DozukiAuthentication::authenticate();

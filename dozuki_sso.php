<?php
DozukiAuthentication::authenticate();

class DozukiAuthentication {
   public static function authenticate() {
      $userInfo = $this->getUserInfo();
      if (!$userInfo)
         self::sendToLogin();

      $params = array_merge($userInfo, array(
         't' = time()
      ));

      $destinationURL = self::$dozukiSite . '/Guide/remote_login?';

      $query = http_build_query($params);
      $hash = sha1($query . self::$secret);
      $query .= "&hash=" . $hash;

      http_redirect($destinationURL . $query);
   }

   // ========================================================
   // Only Make Changes below this line
   // ========================================================
   protected static $dozukiSite = 'yoursitename.dozuki.com';
   protected static $secret = 'abcdefghijklmnopqrstuvwxyz01234567890';

   /**
    * Change this function to return the specified info for the currently 
    * logged in user. If the current user is anonymous, this should return
    * null.
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
    * This function should redirect the user to your login page, or just render
    * it directly.
    */
   protected static function sendToLogin() {
      // Redirect the user to your login page.
      // Or, just render the login page
   }
}

DozukiAuthentication::authenticate();


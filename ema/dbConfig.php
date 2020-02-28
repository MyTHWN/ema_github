<?php


/* 
------------------------------------------------------------------------
Copyright (C) 2014 Bart Orriens, Albert Weerman  USC CESR

This library/program is free software; you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation; either version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License along with this library; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
------------------------------------------------------------------------
*/

class dbConfig {



  static function dbName() { return 'ema'; }

  static function dbSurvey() { return 'ema'; }  

  static function dbUser() { return 'root'; }

  static function dbPassword() { return ''; } //change  test


  static function dbServer() { return 'localhost'; }

  static function dbType() { return DB_MYSQL; }

  static function defaultStartup() { return USCIC_SURVEY; }//default survey mode!
  
  static function defaultPanel() { return PANEL_RESPONDENT; }
  static function defaultTracking() { return false; }

  static function defaultCommunicationServer() { return '128.125.142.97/haalsi/surveys/communication/index.php'; }
  static function defaultSeparateInterviewAddress() { return false; }
  
  static function defaultDevice() { return DEVICE_ALL; }
  static function defaultProxyCode() { return false; }
  static function defaultAllowProxyContact() { return true; }
}
?>

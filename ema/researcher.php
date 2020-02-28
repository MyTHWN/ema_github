<?php

/*
  ------------------------------------------------------------------------
  Copyright (C) 2014 Bart Orriens, Albert Weerman

  This library/program is free software; you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation; either version 2.1 of the License, or (at your option) any later version.

  This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.

  You should have received a copy of the GNU Lesser General Public License along with this library; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
  ------------------------------------------------------------------------
 */

class Researcher {

    var $user;

    function Researcher($user) {
        $this->user = $user;
    }

    function getPage() {
        if (getFromSessionParams('page') != null) {
            //echo '<br/><br/><br/>' . getFromSessionParams('page');
            switch (getFromSessionParams('page')) {
                 case 'researcher.data': return $this->showData(); break;
                 
                 case 'researcher.data.other': return $this->showOtherData(); break;
             
                 case 'researcher.reports': return $this->showReports(); break;
                 case 'researcher.reports.responseoverview': return $this->showReportsResponse(); break;
                 
                 case 'researcher.documentation': return $this->showDocumentation(); break;
                 
                 case 'sysadmin.output.datasingleres': return $this->downloadData(); break;
             
                 case 'researcher.sample': return $this->showSample(); break;
                 case 'researcher.sample.assign': return $this->showAssignSample(); break;
                 case 'researcher.sample.download': return $this->showSampleDownload(); break;
                 case 'researcher.sample.download.gps': return $this->showSampleDownloadGPS(); break;
             
             
                 default: return $this->mainPage();
            }
        } else {
            return $this->mainPage();
        }
    }

    function mainPage(){
        $displayResearcher = new DisplayResearcher();
        return $displayResearcher->showMain();
    }
    
    function showData(){
        $displayResearcher = new DisplayResearcher();
        return $displayResearcher->showData();
    }
    
    function showOtherData(){
      global $db;
      $type = getFromSessionParams('type');
      if ($type != ''){
        $filename = '_' . date('YmdHis');
        $query = '';
        switch($type){
            case 1:
              $filename = 'households' . $filename;
              $query = 'select primkey,urid,puid,status,ts from ' . dbConfig::dbSurvey() . '_households where test = 0 order by primkey';
               
            break;
             
            case 2:
              $filename = 'respondents' . $filename;
              $query = 'select primkey,hhid,urid,status,selected,present,hhhead,finr,famr,permanent,validation,ts from ' . dbConfig::dbSurvey() . '_respondents where test = 0 order by primkey';
            break;
        
            case 3:
              $filename = 'contacts' . $filename;
              $query = 'select primkey,code,contactts,proxy,urid, aes_decrypt(remark, "' . Config::smsContactRemarkKey() . '") as remark, ts from ' . dbConfig::dbSurvey() . '_contacts where primkey not like "999%"';
            break;

            case 4:
              $filename = 'remarks' . $filename;
              $query = 'select primkey,urid, aes_decrypt(remark, "' . Config::smsRemarkKey() . '") as remark, ts from ' . dbConfig::dbSurvey() . '_remarks where primkey not like "999%"';
            break;

        
        
        }  
        if ($query != ''){
          $result = $db->selectQuery($query);
          createCSV($result, $filename);
        }
          
      }      
    }
    
    function showReports(){
        $displayResearcher = new DisplayResearcher();
        return $displayResearcher->showReports();
    }
    
    function showReportsResponse(){
        $displayResearcher = new DisplayResearcher();
        return $displayResearcher->showReportsResponse();
    }
            
    function showDocumentation(){
        $displayResearcher = new DisplayResearcher();
        return $displayResearcher->showDocumenation();
    }
    
    function showSample($message = ''){
        $displayResearcher = new DisplayResearcher();
        return $displayResearcher->showSample($message);
    }

    
    function downloadData(){
        $SysAdmin = new SysAdmin(); //download data directly
        $SysAdmin->getPage();
    }
    
    function showAssignSample(){
        $SysAdmin = new SysAdmin();
        $message = $SysAdmin->assignSample(loadvar('assignid'), loadvar('selurid'));
        $display = new Display();
        return $this->showSample($display->displayInfo($message));
    }
   
    function showSampleDownload(){
        $SysAdmin = new SysAdmin(); //download unassigned sample data directly
        $SysAdmin->showSampleDownload();        
    }

    function showSampleDownloadGPS(){
        $SysAdmin = new SysAdmin(); //download unassigned sample data directly
        $SysAdmin->showSampleDownloadGPS();        
    }
    
}


?>

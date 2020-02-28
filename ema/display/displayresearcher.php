<?php

/*
  ------------------------------------------------------------------------
  Copyright (C) 2014 Bart Orriens, Albert Weerman

  This library/program is free software; you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation; either version 2.1 of the License, or (at your option) any later version.

  This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.

  You should have received a copy of the GNU Lesser General Public License along with this library; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
  ------------------------------------------------------------------------
 */

class DisplayResearcher extends Display {

    public function __construct() {
        parent::__construct();
    }

    public function showMain() {
        $returnStr = $this->showResearchHeader(Language::messageSMSTitle());
        $returnStr .= '<div id="wrap">';

        $returnStr .= $this->showNavBar();
        $returnStr .= '<div class="container"><p>';


        //respondents mode!

        $returnStr .= '<div class="list-group">';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'researcher.reports')) . '" class="list-group-item">' . 'Reports' . '</a>';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'researcher.documentation')) . '" class="list-group-item">' . 'Documentation' . '</a>';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'researcher.data')) . '" class="list-group-item">' . 'Data' . '</a>';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'researcher.households')) . '" class="list-group-item">' . 'Households' . '</a>';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'researcher.sample')) . '" class="list-group-item">' . 'Unassigned Sample' . '</a>';

        $returnStr .= '</div>';




        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();
        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function showDocumenation() {
        $returnStr = $this->showResearchHeader(Language::messageSMSTitle());
        $returnStr .= '<div id="wrap">';

        $returnStr .= $this->showNavBar();
        $returnStr .= '<div class="container"><p>';

//CONTENT
        $returnStr .= '<ol class="breadcrumb">';
        $returnStr .= '<li class="active">' . Language::labelOutputDocumentation() . '</li>';
        $returnStr .= '</ol>';

        $communication = new Communication();
        $files = array();
        $communication->getScriptFiles($files, 'documentation');
        $oldDirStr = '';
        if (sizeof($files) > 0) {
            foreach ($files as $file) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if ($ext == 'html' || $ext == 'doc' || $ext == 'docx') {
                    $dirStr = '';
                    $dir = preg_replace('#/+#', '', dirname($file));
                    if ($dir != '') {
                        $dirStr = $dir . ': ';
                    }
                    if ($oldDirStr != $dirStr && $oldDirStr != '') {
                        $returnStr .= '<hr>';
                    }
                    $oldDirStr = $dirStr;

                    $returnStr .= $dirStr . '<a href="documentation' . $file . '" target="_blank">' . basename($file) . '</a><br/>';
                }
            }
        } else {
            $returnStr .= $this->displayWarning(Language::labelResearcherNoDocs());
        }

//ENDCONTENT        




        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();
        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function showResearchHeader($title, $extra = '') {

        if (loadvar(POST_PARAM_AJAX_LOAD) == AJAX_LOAD) {
            return;
        }

        $extra2 = '<link href="js/formpickers/css/bootstrap-formhelpers.min.css" rel="stylesheet">
                  <link href="css/uscicadmin.css" rel="stylesheet">
                  <link href="bootstrap/css/sticky-footer-navbar.css" rel="stylesheet">                  
                    ';
        $returnStr = $this->showHeader(Language::messageSMSTitle(), $extra . $extra2);
        $returnStr .= $this->displayOptionsSidebar("optionssidebarbutton", "optionssidebar");
        $returnStr .= $this->bindAjax();
        return $returnStr;
    }

    function showBottomBar() {

        if (loadvar(POST_PARAM_AJAX_LOAD) == AJAX_LOAD) {
            return;
        }

        return '</div>
    <div id="footer">
      <div class="container">
        <p class="text-muted credit">' . Language::nubisFooter() . '</p>
      </div>
    </div>
    <div class="waitmodal"></div>';
    }

    public function showNavBar() {

        if (loadvar(POST_PARAM_AJAX_LOAD) == AJAX_LOAD) {
            return;
        }
        $householdsActive = '';
        $reportsActive = '';
        $dataActive = '';
        $documentationActive = '';
        $sampleActive = '';
        if (startsWith(getFromSessionParams('page'), 'researcher.reports')) {
            $reportsActive = ' class="active"';
        }
        if (startsWith(getFromSessionParams('page'), 'researcher.documentation')) {
            $documentationActive = ' class="active"';
        }
        if (startsWith(getFromSessionParams('page'), 'researcher.data')) {
            $dataActive = ' class="active"';
        }
        if (startsWith(getFromSessionParams('page'), 'researcher.sample')) {
            $sampleActive = ' class="active"';
        }
        if (startsWith(getFromSessionParams('page'), 'researcher.households')) {
            $householdsActive = ' class="active"';
        }



        $returnStr = '
      <!-- Fixed navbar -->
      <div id="mainnavbar" class="navbar navbar-default navbar-fixed-top">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand">' . Language::messageSMSTitle() . '</a>
          </div>
          <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">';


        $returnStr .= '<li' . $reportsActive . '>' . setSessionParamsHref(array('page' => 'researcher.reports'), Language::linkReports()) . '</li>';
        $returnStr .= '<li' . $documentationActive . '>' . setSessionParamsHref(array('page' => 'researcher.documentation'), Language::linkDocumentation()) . '</li>';
        $returnStr .= '<li' . $dataActive . '>' . setSessionParamsHref(array('page' => 'researcher.data'), Language::linkData()) . '</li>';
        //    $returnStr .= '<li' . $householdsActive . '>' . setSessionParamsHref(array('page' => 'researcher.households'), 'Households') . '</li>';
        $returnStr .= '<li' . $sampleActive . '>' . setSessionParamsHref(array('page' => 'researcher.sample'), Language::linkUnassigned()) . '</li>';


//        $returnStr .'<li' . $smsActive . '>' . setSessionParamsHref(array('page' => 'sysadmin.sms'), Language::linkSms()) . '</li>';



        /*     $returnStr .= '<li class="dropdown' . $surveyActive . '"><a data-hover="dropdown" class="dropdown-toggle" data-toggle="dropdown">' . Language::linkSurvey() . ' <b class="caret"></b></a>';

          $surveys = new Surveys();
          $surveys = $surveys->getSurveys();
          $returnStr .= '<ul class="dropdown-menu">';
          foreach ($surveys as $survey) {
          $span = '';
          if (isset($_SESSION['SUID']) && $_SESSION['SUID'] == $survey->getSuid()) {
          $span = ' <span class="glyphicon glyphicon-chevron-down"></span>';
          }
          $returnStr .= '<li>' . setSessionParamsHref(array('page' => 'sysadmin.survey', 'suid' => $survey->getSuid()), $survey->getName() . $span) . '</li>';
          }
          $returnStr .= '</ul>';
          $returnStr .= '</li>'; */
        /*        $returnStr .= '<li class="dropdown' . $outputActive . '"><a data-hover="dropdown" class="dropdown-toggle" data-toggle="dropdown">' . Language::linkOutput() . ' <b class="caret"></b></a>';
          $returnStr .= '<ul class="dropdown-menu">';
          $returnStr .= '<li>' . setSessionParamsHref(array('page' => 'sysadmin.output.data'), '<span class="glyphicon glyphicon-save"></span> ' . Language::linkData()) . '</li>';
          $returnStr .= '<li>' . setSessionParamsHref(array('page' => 'sysadmin.output.statistics'), '<span class="glyphicon glyphicon-stats"></span> ' . Language::linkStatistics()) . '</li>';
          $returnStr .= '<li class="divider"></li>';
          $returnStr .= '<li>' . setSessionParamsHref(array('page' => 'sysadmin.output.documentation'), '<span class="glyphicon glyphicon-file"></span> ' . Language::linkDocumentation()) . '</li>';
          $returnStr .= '<li>' . setSessionParamsHref(array('page' => 'sysadmin.output.screendumps'), '<span class="glyphicon glyphicon-screenshot"></span> ' . Language::linkScreendumps()) . '</li>';
          $returnStr .= '</ul></li>'; */

        /* $returnStr .= '<li class="dropdown' . $toolsActive . '"><a data-hover="dropdown" class="dropdown-toggle" data-toggle="dropdown">' . Language::linkTools() . ' <b class="caret"></b></a>';
          $returnStr .= '<ul class="dropdown-menu">';
          $returnStr .= '<li>' . setSessionParamsHref(array('page' => 'sysadmin.tools.check'), '<span class="glyphicon glyphicon-check"></span> ' . Language::linkChecker()) . '</li>';
          $returnStr .= '<li>' . setSessionParamsHref(array('page' => 'sysadmin.tools.compile'), '<span class="glyphicon glyphicon-cog"></span> ' . Language::linkCompiler()) . '</li>';
          $returnStr .= '<li class="divider"></li>';
          $returnStr .= '<li>' . setSessionParamsHref(array('page' => 'sysadmin.tools.test'), '<span class="glyphicon glyphicon-comment"></span> ' . Language::linkTest()) . '</li>';
          $returnStr .= '<li>' . setSessionParamsHref(array('page' => 'sysadmin.tools.flood'), '<span class="glyphicon glyphicon-random"></span> ' . Language::linkFlood()) . '</li>';
          $returnStr .= '<li class="divider"></li>';
          $returnStr .= '<li>' . setSessionParamsHref(array('page' => 'sysadmin.tools.export'), '<span class="glyphicon glyphicon-export"></span> ' . Language::linkExport()) . '</li>';
          $returnStr .= '<li>' . setSessionParamsHref(array('page' => 'sysadmin.tools.import'), '<span class="glyphicon glyphicon-import"></span> ' . Language::linkImport()) . '</li>';
          $returnStr .= '<li class="divider"></li>';
          $returnStr .= '<li>' . setSessionParamsHref(array('page' => 'sysadmin.tools.clean'), '<span class="glyphicon glyphicon-trash"></span> ' . Language::linkCleaner()) . '</li>';
          $returnStr .= '</ul></li>'; */
        $returnStr .= '</ul>';
        $user = new User($_SESSION['URID']);
        $returnStr .= '<ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a data-hover="dropdown" class="dropdown-toggle" data-toggle="dropdown">' . $user->getUsername() . ' <b class="caret"></b></a>
                 <ul class="dropdown-menu">
        		<li><a href="' . setSessionParams(array('page' => 'sysadmin.preferences')) . '"><span class="glyphicon glyphicon-wrench"></span> ' . Language::linkPreferences() . '</a></li>';
        if ($user->getUserType() == USER_SYSADMIN) {
            $returnStr .= '<li><a href="' . setSessionParams(array('page' => 'sysadmin.users')) . '"><span class="glyphicon glyphicon-user"></span> ' . Language::linkUsers() . '</a></li>';
        }

        $returnStr .= '<li class="divider"></li>
                   <li><a ' . POST_PARAM_NOAJAX . '=' . NOAJAX . ' href="index.php?rs=1&se=2"><span class="glyphicon glyphicon-log-out"></span> ' . Language::linkLogout() . '</a></li>
                 </ul>
             </li>
            </ul>
';
        // $returnStr .= $this->showSearch();
        $returnStr .= '
          </div><!--/.nav-collapse -->
        </div>
      </div>
';

        $returnStr .= "<div id='content'>";

        return $returnStr;
    }

    function showReports() {
        $returnStr = $this->showResearchHeader(Language::messageSMSTitle());
        $returnStr .= '<div id="wrap">';

        $returnStr .= $this->showNavBar();
        $returnStr .= '<div class="container"><p>';

        //respondents mode!

        $returnStr .= '<ol class="breadcrumb">';
        $returnStr .= '<li class="active">' . Language::labelResearcherOutputReports() . '</li>';
        $returnStr .= '</ol>';


        $returnStr .= '<div class="list-group">';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'researcher.reports.responseoverview')) . '" class="list-group-item">' . Language::labelResearcherResponseOverview() . '</a>';

//        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'researcher.reports.completes')) . '" class="list-group-item">' . 'Response per supervisor' . '</a>';
//        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'researcher.data')) . '" class="list-group-item">' . 'Data' . '</a>';
        $returnStr .= '</div>';




        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();
        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function showReportsResponse() {

        $returnStr = $this->showResearchHeader(Language::messageSMSTitle());
        $returnStr .= '<div id="wrap">';

        $returnStr .= $this->showNavBar();
        $returnStr .= '<div class="container"><p>';

        //respondents mode!

        $returnStr .= '<ol class="breadcrumb">';
        $returnStr .= '<li>' . setSessionParamsHref(array('page' => 'researcher.reports'), Language::labelResearcherOutputReports()) . '</li>';
        $returnStr .= '<li class="active">Response overview</li>';

        $returnStr .= '</ol>';
//RESPONSE OVERVIEW

        $puid = loadvar('puid', 0);
        $rorh = loadvar('rorh', 1);
        $ctype = loadvar('ctype', 1);

        $returnStr .= '<nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand">Set filter</a>
   </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">';


        $returnStr .= '<form method="post" class="navbar-form navbar-left">';
        $returnStr .= setSessionParamsPost(array('page' => 'researcher.reports.responseoverview'));

        // $content .= $sessionparams;
        $returnStr .= '<div class="form-group">';

        //$content .= $input; //$this->displayUsers($users, $respondentOrHousehold->getUrid());
        $returnStr .= $this->displayRespondentOrHousehold($rorh);
        $returnStr .= $this->displayPsus($puid, true);
        $returnStr .= $this->displayChartType($ctype);


        $returnStr .= '</div>';
        $returnStr .= '<button type="submit" class="btn btn-default">' . Language::labelResearcherButtonGo() . '</button>';
        $returnStr .= '</form>
        </div>
      </div>
</nav>';





        $returnStr .= '<script src="js/highcharts.js"></script>';
        $returnStr .= '<script src="js/modules/exporting.js"></script>';
        $returnStr .= '<div id="chart1" style="min-width: 310px; height: 400px; margin: 0 auto"></div>';

        $returnStr .= $this->getResponseData($rorh, $puid, $ctype);





//OVERVIEW        
        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();
        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function showData() {
        $returnStr = $this->showResearchHeader(Language::messageSMSTitle());
        $returnStr .= '<div id="wrap">';

        $returnStr .= $this->showNavBar();
        $returnStr .= '<div class="container"><p>';


        //respondents mode!
        $returnStr .= '<ol class="breadcrumb">';
        $returnStr .= '<li class="active">' . Language::linkData() . '</li>';
        $returnStr .= '</ol>';


        $returnStr .= $this->displayWarning(Language::labelResearcherWarningStata());

        $returnStr .= '<form method=post>';
        $surveyList = array();
        $surveys = new Surveys();
        foreach ($surveys->getSurveys() as $survey) {
            $surveyList[$survey->getSuid()] = $survey->getName();
        }

        $returnStr .= '<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">' . Language::labelResearcherDownloadSurveyData() . '</h3>
  </div>
  <div class="panel-body">';
        $returnStr .= '<table>';
        $returnStr .= '<script>
            
             function updateFilename(element){
               var surveys = ["", "' . implode('","', $surveyList) . '"];
               $("input[name=\'filename\']").val(\'' . dbConfig::dbSurvey() . '_\' + surveys[$(element).val()]); 
               //alert($("input[name=\'filename\']").val());
             }
             
             function checkEmptyEncryption(){
               if ($("input[name=\'primkeyencryption\']").val() == \'\'){
                 alert(\'' . Language::labelResearcherEncryption() . '!\');
                 return false;
               }
               return true;
             }
             </script>';

        $returnStr .= '<tr><td>' . Language::labelOutputDataSurvey() . '</td><td><select id=survey name=survey class="form-control" onchange="updateFilename(this)">';
        foreach ($surveyList as $key => $survey) {
            $returnStr .= '<option value="' . $key . '">' . $survey . ' data (dta)</option>';
        }
        $returnStr .= '</select></td></tr>';
        $returnStr .= '<tr><td>' . Language::labelResearcherEncryptionKey() . '</td><td><input type=text class="form-control" name=primkeyencryption></td></tr>';
        $returnStr .= '</table>';
        $returnStr .= '<input type=hidden name="r" value="eNpLtDK0qi62MrFSKkhMT1WyLrYysrRSKq4sTkzJzczTyy8tKSgt0UtJLEkszsxLz0ktSi1Wsq4FXDDnKhLi">';
        $returnStr .= '<input type=hidden name="modes[]" value="1">';
        $returnStr .= '<input type=hidden name="modes[]" value="2">';
        $returnStr .= '<input type=hidden name="modes[]" value="3">';
        $returnStr .= '<input type=hidden name="languages[]" value="1">';
        $returnStr .= '<input type=hidden name="languages[]" value="2">';
        $returnStr .= '<input type=hidden name="typedata" value="2">';
        $returnStr .= '<input type=hidden name="completedinterviews" value="0">';
        $returnStr .= '<input type=hidden name="cleandata" value="2">';
        $returnStr .= '<input type=hidden name="filetype" value="1">';
        $returnStr .= '<input type=hidden name="filename" value="' . dbConfig::dbSurvey() . '_' . $surveyList[1] . '">';
        $returnStr .= '<input type=hidden name="primkeyindata" value="1">';
        $returnStr .= '<input type=hidden name="variableswithoutdata" value="1">';
        $returnStr .= '<input type=hidden name="fieldnamecase" value="1">';
        $returnStr .= '<input type=hidden name="includevaluelabels" value="1">';
        $returnStr .= '<input type=hidden name="includevaluelabelnumbers" value="1">';
        $returnStr .= '<input type=hidden name="markempty" value="1">';


        $returnStr .= '<br/><button type="submit" class="btn btn-default" onclick="return checkEmptyEncryption()">' . Language::labelResearcherDownloadData() . '</button>';
        $returnStr .= '</form>';
        $returnStr .= '</div></div>';


        /*
          $syid = 2;
          $link = "?r=eNpLtDK0qi62MrFSKkhMT1WyLrYysrRSKq4sTkzJzczTyy8tKSgt0UtJLEkszsxLz0ktSi1Wsq4FXDDnKhLi&survey=$syid&modes[]=1&modes[]=2&modes[]=3&languages[]=1&typedata=2&completedinterviews=0&cleandata=2&filetype=1&filename=&primkeyindata=1&variableswithoutdata=1&primkeyencryption=&fieldnamecase=1&includevaluelabels=1&includevaluelabelnumbers=1&markempty=1";
          $returnStr .= '<a href="' . $link . '">' . 'Individual data (dta)' . '</a><br/>';
         */

        $returnStr .= '<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">' . Language::labelResearcherDownloadOtherData() . '</h3>
  </div>
  <div class="panel-body">';


        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'researcher.data.other', 'type' => '1')) . '">' . Language::labelResearcherDownloadHouseholds() . '</a><br/>';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'researcher.data.other', 'type' => '2')) . '">' . Language::labelResearcherDownloadRespondents() . '</a><br/>';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'researcher.data.other', 'type' => '3')) . '">' . Language::labelResearcherDownloadContacts() . '</a><br/>';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'researcher.data.other', 'type' => '4')) . '">' . Language::labelResearcherDownloadRemarks() . '</a><br/>';



        $returnStr .= '</div></div>';


        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();
        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function getResponseData($type, $puid, $ctype = 1) { //$type 1: coverscreen, 2: individual
        $graphTypes = array('', 'spline', 'column');


        $title = Language::projectTitle();

        $sub = Language::labelResearcherResponseOverviewIndividual();
        $resporhhtext = Language::labelResearcherRespondents();
        if ($type == 1) {
            $sub = Language::labelResearcherResponseOverviewCover();
            $resporhhtext = Language::labelResearcherHouseholds();
        }

        $returnStr = '<script src="js/export-csv.js"></script>';
        $returnStr .= "<script type='text/javascript'>


        var chart = new Highcharts.Chart({

            chart: {
                renderTo: 'chart1',
                        type: '" . $graphTypes[$ctype] . "',
                        zoomType: 'x'
                    },
                    title: {
                        text: '" . $title . "'
                    },
                    subtitle: {
                        text: '" . $sub . "'
                    },
                    xAxis: {
                        type: 'datetime',
                        dateTimeLabelFormats: { // don't display the dummy year
                            month: '%e. %b',
                            year: '%b'
                        }
                    },
                    yAxis: {
                        title: {
                            text: '# " . $resporhhtext . "'
                        },
                        min: 0
                    },
                    tooltip: {
                        formatter: function() {
                                return '<b>'+ this.series.name +'</b><br/>'+
                                Highcharts.dateFormat('%e. %b', this.x) +': '+ this.y +' " . $resporhhtext . "';
                        }
                    },

                    series: [";

        $users = new users();
        $users = $users->getUsersByType(USER_SUPERVISOR);

        $i = 0;
        foreach ($users as $user) {
            if ($i != 0) {
                $returnStr .= ',';
            }
            $returnStr .= "{
                        name: '" . $user->getName() . "',
                        data: [";

            $users = new Users();
            $urids = array();
            $users = $users->getUsersBySupervisor($user->getUrid());
            foreach ($users as $user) {
                $urids[] = $user->getUrid();
            }
            $returnStr .= $this->getContactCodeData(500, $urids, $type, $ctype == 1, $puid);
            $returnStr .= "                ]
                    }";

            $i++;
        }

        $returnStr .= "
              ]
            });
        </script>";

        return $returnStr;
    }

    function getContactCodeData($code, $urid = 0, $rorh = 2, $cummulative = true, $puid = -1) {

        global $db;
        $dataStr = '';
        $actions = array();
        $uridstr = '';
        if ($urid > 0) {
            if (is_array($urid)) {
                $uridstr = ' AND (t1.urid = ';
                $uridstr .= implode(' OR t1.urid = ', $urid);
                $uridstr .= ' )';
            } else {
                $uridstr = ' AND t1.urid = ' . $urid;
            }
        }


        if ($rorh == 1) { //houseohld level
            $puidStr = '';
            if ($puid > 0) {
                $puidStr = ' AND t2.puid = "' . $puid . '" ';
            }
            $query = 'select DATE(t1.ts) as dateobs, count(*) as cntobs, t1.primkey from ' . dbConfig::dbSurvey() . '_contacts as t1 ';
            $query .= 'left join ' . dbConfig::dbSurvey() . '_households as t2 on t2.primkey = t1.primkey ';
            $query .= 'where ' . getTextmodeStr('t1.') . ' t1.ts > "' . date('Y-m-d', config::graphStartDate()) . ' 23:59:99" AND t2.primkey IS NOT NULL ' . $puidStr . ' AND t1.code = ' . $code . $uridstr . ' group by DATE(t1.ts) order by t1.ts asc';
        } else {
            $puidStr = '';
            if ($puid > 0) {
                $puidStr = ' AND t3.puid = "' . $puid . '" ';
            }
            $query = 'select DATE(t1.ts) as dateobs, count(*) as cntobs, t1.primkey from ' . dbConfig::dbSurvey() . '_contacts as t1 ';
            $query .= 'left join ' . dbConfig::dbSurvey() . '_respondents as t2 on t2.primkey = t1.primkey ';
            $query .= 'left join ' . dbConfig::dbSurvey() . '_households as t3 on t3.primkey = t2.hhid ';
            $query .= 'where ' . getTextmodeStr('t1.') . ' t1.ts > "' . date('Y-m-d', config::graphStartDate()) . ' 23:59:99" AND t2.primkey IS NOT NULL ' . $puidStr . '  AND t1.code = ' . $code . $uridstr . ' group by DATE(t1.ts) order by t1.ts asc';


            //$query = 'select DATE(ts) as dateobs, count(*) as cntobs, primkey from ' . dbConfig::dbSurvey()  . '_contacts as t1 where code = ' . $code . $uridstr . ' group by DATE(ts) order by ts asc'; 
        }


        //echo '<br><br/><br/>' . $query;
        $total = 0;
        $dataStr .= "[Date.UTC(" . date('Y,m,d', strtotime(date('Y-m-d', config::graphStartDate()) . " -1 months")) . "), 0   ],";
        $result = $db->selectQuery($query);
        while ($row = $db->getRow($result)) {
            $key = $row['dateobs'];
            if ($cummulative) {
                $total += $row['cntobs'];
            } else {
                $total = $row['cntobs'];
            }

            $dataStr .= "[Date.UTC(" . substr($key, 0, 4) . ", " . (substr($key, 5, 2) - 1) . ", " . substr($key, 8, 2) . "), " . $total . "],";
        }
        $returnStr = rtrim($dataStr, ',');
        return $returnStr;
    }

    function showSample($message = '') {
        $returnStr = $this->showResearchHeader(Language::messageSMSTitle());
        $returnStr .= '<div id="wrap">';

        $returnStr .= $this->showNavBar();
        $returnStr .= '<div class="container"><p>';

        $returnStr .= '<ol class="breadcrumb">';
        $returnStr .= '<li class="active">' . Language::linkUnassigned() . '</li>';
        $returnStr .= '</ol>';

        $returnStr .= $message;

        $displaySms = new DisplaySms();
        $returnStr .= $displaySms->showAvailableUnassignedHouseholds();

        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'researcher.sample.download')) . '&puid= ' . loadvar('puid', 0) . '">' . Language::labelResearcherDownloadCSV() . '</a>';
        $returnStr .= '&nbsp;&nbsp;|&nbsp;&nbsp;';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'researcher.sample.download.gps')) . '&puid= ' . loadvar('puid', 0) . '">' . Language::labelResearcherDownloadGPS() . '</a>';


        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();
        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

}

?>

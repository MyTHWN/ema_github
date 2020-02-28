<?php

/* 
------------------------------------------------------------------------
Copyright (C) 2014 Bart Orriens, Albert Weerman

This library/program is free software; you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation; either version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License along with this library; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
------------------------------------------------------------------------
*/
class DisplayUsers extends DisplaySysAdmin {

    public function __construct() {
        parent::__construct();
    }

    function showUsers($message) {
        $returnStr = $this->showSysAdminHeader(Language::messageSMSTitle());
        $returnStr .= '<div id="wrap">';
        $returnStr .= $this->showNavBar();
        $returnStr .= '<div class="container"><p>';

        $returnStr .= '<ol class="breadcrumb">';
        $returnStr .= '<li>' . Language::headerUsers() . '</li>';
        $returnStr .= '</ol>';

        //$users = new Users();
        //foreach($users->getUsers() as $user){
        //    $returnStr .= $user->getUrid() . '---<br/>';
        //}
        $returnStr .= $message;
        

        
        $returnStr .= '<div id=usersdiv>';

        $returnStr .= '</div>';
        $usertype = loadvar('usertype', USER_INTERVIEWER);        
        $returnStr .= '<script>$("#usersdiv").load("index.php",{ \'p\': \'sysadmin.users\', \'' . POST_PARAM_SMS_AJAX . '\': \'' . SMS_AJAX_CALL . '\', \'usertype\': \'' . $usertype . '\' } );</script>';
        $returnStr .= '<a href="' . setSessionParams(array('page' => 'sysadmin.users.adduser')) . '">' . 'add new user' . '</a>';
        
        
        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();
        $returnStr .= $this->showFooter(false);
        return $returnStr;        
        
    }
    
    
    function showUsersList($users){

        $returnStr = '';
        
        
        $usertypes = array(USER_INTERVIEWER => Language::labelInterviewer(), USER_NURSE => Language::labelNurse(), USER_SUPERVISOR => Language::labelSupervisor(), USER_TRANSLATOR => Language::labelTranslator(), USER_RESEARCHER => Language::labelResearcher(), USER_SYSADMIN => Language::labelSysadmin(), USER_TESTER => Language::labelTester());
        $usertype = loadvar('usertype', USER_INTERVIEWER);        
        $returnStr .= $this->showActionBar('Filter on user type', $this->displaySelectFromArray($usertypes, $usertype, 'usertype'), 'Go', setSessionParamsPost(array('page' => 'sysadmin.users')));

        
        
        if (sizeof($users) > 0) {


            
            $returnStr .= $this->displayPopoverScript();
            $returnStr .= '<table class="table table-striped table-bordered pre-scrollable table-condensed table-hover">';
            $returnStr .= '<tr><th></td><th>Username</th><th>Name</th><th>Type</th></tr>';
            
            $usertypes = array(USER_INTERVIEWER => Language::labelInterviewer(), USER_NURSE => Language::labelNurse(), USER_SUPERVISOR => Language::labelSupervisor(), USER_TRANSLATOR => Language::labelTranslator(), USER_RESEARCHER => Language::labelResearcher(), USER_SYSADMIN => Language::labelSysadmin(), USER_TESTER => Language::labelTester());

            
            foreach ($users as $user) {
                $returnStr .= '<tr><td>';
                $content = '<a id="' . $user->getUrid() . '_edit" title="' . Language::linkEditTooltip() . '" href="' . setSessionParams(array('page' => 'sysadmin.users.edituser', 'urid' => $user->getUrid())) . '"><span class="glyphicon glyphicon-edit"></span></a>';
                $content .= '&nbsp;&nbsp;<a id="' . $user->getUrid() . '_copy" title="' . Language::linkCopyTooltip() . '" href="' . setSessionParams(array('page' => 'sysadmin.users.copyuser', 'urid' => $user->getUrid())) . '" ' . confirmAction(language::messageCopyUser($user->getName()), 'COPY') . '><span class="glyphicon glyphicon-copyright-mark"></span></a>';
                $content .= '&nbsp;&nbsp;<a id="' . $user->getUrid() . '_remove" title="' . Language::linkRemoveTooltip() . '" href="' . setSessionParams(array('page' => 'sysadmin.users.removeuser', 'urid' => $user->getUrid())) . '" ' . confirmAction(language::messageRemoveUser($user->getName()), 'REMOVE') . '><span class="glyphicon glyphicon-remove"></span></a>';
                $returnStr .= '<a rel="popover" id="' . $user->getUrid() . '_popover" data-placement="right" data-html="true" data-toggle="popover" data-trigger="hover" href="' . setSessionParams(array('page' => 'sysadmin.users.edituser', 'urid' => $user->getUrid())) . '"><span class="glyphicon glyphicon-hand-right"></span></a>';
                $returnStr .= '<td>' . $user->getUsername() . '</td><td>' . $user->getName() . '</td>';
                $returnStr .= '<td>' . $usertypes[$user->getUserType()] . '</td></tr>';
                $returnStr .= $this->displayPopover("#" . $user->getUrid() . '_popover', $content);
            }
            $returnStr .= '</table>';
        } else {
            $returnStr .= $this->displayWarning(Language::messageNoUsersYet());
        }
        return $returnStr;
    }
    
    function showEditUser($urid, $message = ""){
        $user = new User($urid);
        $returnStr = $this->showSysAdminHeader(Language::messageSMSTitle());
        $returnStr .= '<div id="wrap">';
        $returnStr .= $this->showNavBar();
        $returnStr .= '<div class="container"><p>';

        $returnStr .= '<ol class="breadcrumb">';
        $returnStr .= '<li>' . setSessionParamsHref(array('page' => 'sysadmin.users'), Language::headerUsers()) . '</li>';
        $returnStr .= '<li>' . 'Edit users' . '</li>';
        $returnStr .= '</ol>';

        if ($urid != '') {
        
            
        }
        else {
        
            
        }
        
        $returnStr .= $this->displayComboBox();
        $returnStr .= '<form id="editform" method="post">';
        $returnStr .= '<div class="well">';
        $returnStr .= setSessionParamsPost(array('page' => 'sysadmin.users.edituserres', 'urid' => $urid));

        
        $returnStr .= '<div class="row">';
        $returnStr .= '<div class="col-md-6">';
        
        $returnStr .= '<table>';
        $returnStr .= '<tr><td>Username</td><td><input type="text" class="form-control" name="username" value="' . convertHTLMEntities($user->getUsername(), ENT_QUOTES) . '"></td></tr>';
        $returnStr .= '<tr><td>Name</td><td><input type="text" class="form-control" name="name" value="' . convertHTLMEntities($user->getName(), ENT_QUOTES) . '"></td></tr>';
        $returnStr .= '<tr><td align=top>Active</td><td>';        
        $returnStr .= $this->showDropDown(array(VARIABLE_ENABLED => Language::labelEnabled(), VARIABLE_DISABLED => Language::labelDisabled()), $user->getStatus(), 'status');
        $returnStr .= '</td></tr>';
        $returnStr .= '<tr><td align=top>Type</td><td>';
        $returnStr .= $this->showDropDown(array(USER_INTERVIEWER => Language::labelInterviewer(), USER_NURSE => Language::labelNurse(), USER_SUPERVISOR => Language::labelSupervisor(), USER_TRANSLATOR => Language::labelTranslator(), USER_RESEARCHER => Language::labelResearcher(), USER_SYSADMIN => Language::labelSysadmin(), USER_TESTER => Language::labelTester()), $user->getUserType(), 'usertype', 'usertype');
        $returnStr .= '</td></tr>';
        
        $returnStr .= "<script type='text/javascript'>";
        $returnStr .= '$( document ).ready(function() {
                                                $("#usertype").change(function (e) {
                                                    if (this.value == 5) {
                                                        $("#subtype").show(); 
                                                        $(".modesrow").hide();
                                                    }   
                                                    else {
                                                        $("#subtype").hide();                                                       
                                                        $(".modesrow").show();
                                                    }
                                                });
                                                })';
        $returnStr .= "</script>";
        
        if (inArray($user->getUserType(), array(USER_NURSE))) {
            $returnStr .= '<tr id=subtype><td align=top>Sub type</td><td>';
            $returnStr .= $this->showDropDown(array(USER_NURSE_MAIN => Language::labelNurseMain(), USER_NURSE_LAB => Language::labelNurseLab(), USER_NURSE_FIELD => Language::labelNurseField(), USER_NURSE_VISION => Language::labelNurseVision()), $user->getUserSubType(), 'usersubtype');
            $returnStr .= '</td></tr>';            
        }
        else {
            $returnStr .= '<tr id=subtype style="display: none;"><td align=top>Sub type</td><td>';
            $returnStr .= $this->showDropDown(array(USER_NURSE_MAIN => Language::labelNurseMain(), USER_NURSE_LAB => Language::labelNurseLab(), USER_NURSE_FIELD => Language::labelNurseField(), USER_NURSE_VISION => Language::labelNurseVision()), $user->getUserSubType(), 'usersubtype');
            $returnStr .= '</td></tr>';
        }
        
        
        $survey = new Survey($_SESSION['SUID']);
        
        /* available modes */
        if (!inArray($user->getUserType(), array(USER_NURSE))) {
            $modes = Config::surveyModes();
            $allowedmodes = explode("~", $survey->getAllowedModes());        
            $usermodes = $user->getModes();        
            foreach ($allowedmodes as $mode) {
                $returnStr .= "<tr class='modesrow'><td>" . $modes[$mode] . "</td><td>";
                $returnStr .= $this->displayUserMode(SETTING_USER_MODE . $mode, inArray($mode, $usermodes));
                $userlanguages = $user->getLanguages($mode);                        
                $returnStr .= "<td>" . Language::labelUserLanguageAllowed() . "</td>";        
                $returnStr .= "<td>" . $this->displayLanguagesAdmin(SETTING_USER_LANGUAGES . $mode, SETTING_USER_LANGUAGES . $mode, $userlanguages, true, false, false, "multiple", $survey->getAllowedLanguages($mode)) . "</td>";        
                $returnStr .= "</tr>";
            }
        }
        
        if (inArray($user->getUserType(), array(USER_INTERVIEWER, USER_CATIINTERVIEWER, USER_NURSE, USER_SUPERVISOR))) {
            $returnStr .= '<tr><td>Supervisor:</td><td>';

            $users = new Users();
            $users = $users->getUsersByType(USER_SUPERVISOR);
            $returnStr .= $this->displayUsers($users, $user->getSupervisor(), 'uridsel', true);
            $returnStr .= '</td></tr>';       
        }
        $returnStr .= '</table></div>';       
        $returnStr .= '<div class="col-md-6">';       
        $returnStr .= '<table>';
        $returnStr .= '<tr><td align=top>Password</td><td><input type="text" class="form-control" name="pwd1"></td></tr>';
        $returnStr .= '<tr><td align=top>Password (re-enter)</td><td><input type="text" class="form-control" name="pwd2"></td></tr>';                
        $returnStr .= '</table></div>';
        $returnStr .= '</div>';

        if ($urid != "") {
            $returnStr .= '<input type="submit" class="btn btn-default" value="' . Language::buttonEdit() . '"/>';
        } else {
            $returnStr .= '<input type="submit" class="btn btn-default" value="' . Language::buttonAdd() . '"/>';
        }
        $returnStr .= '</form>';
        $returnStr .= '</p></div>    </div>'; //container and wrap

        $returnStr .= $this->showBottomBar();
        $returnStr .= $this->showFooter(false);
        return $returnStr;
        
    }
    
    function displayUserMode($name, $selected = false) {
        $returnStr = "<select class='selectpicker show-tick' name=" . $name . ">";
        if ($selected) {
            $selected[USER_MODE_YES] = "SELECTED";        
        }
        else {
            $selected[USER_MODE_NO] = "SELECTED";        
        }
        $returnStr .= "<option " . $selected[USER_MODE_YES] . " value=" . USER_MODE_YES . ">" . Language::optionsUserModeYes() . "</option>";
        $returnStr .= "<option " . $selected[USER_MODE_NO] . " value=" . USER_MODE_NO . ">" . Language::optionsUserModeNo() . "</option>";
        $returnStr .= "</select>";
        return $returnStr;
    }

}

?>

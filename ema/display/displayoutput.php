<?php

/*
  ------------------------------------------------------------------------
  Copyright (C) 2014 Bart Orriens, Albert Weerman

  This library/program is free software; you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation; either version 2.1 of the License, or (at your option) any later version.

  This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.

  You should have received a copy of the GNU Lesser General Public License along with this library; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
  ------------------------------------------------------------------------
 */

class DisplayOutput extends DisplaySysAdmin {

    public function __construct() {
        parent::__construct();
    }

    /* OUTPUT MENU */

    function showOutput() {
        $returnStr = $this->showOutputHeader(array(array('link' => '', 'label' => Language::headerOutput())));
        $returnStr .= $this->showOutputList();
        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();
        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function showOutputList() {
        $user = new User($_SESSION['URID']);
        $utype = $user->getUserType();
        $ut = "";
        switch ($utype) {
            case USER_SYSADMIN:
                $ut = "sysadmin";
                break;
            case USER_RESEARCHER:
                $ut = "researcher";
                break;
            case USER_TRANSLATOR:
                $ut = "translator";
                break;
            case USER_INTERVIEWER:
                $ut = "interviewer";
                break;
        }

        if (inArray($utype, array(USER_SYSADMIN))) {
            $returnStr = '<span class="label label-default">' . Language::labelOutputData() . '</span>';
            $returnStr .= '<div class="well">';
            $returnStr .= '<div class="list-group">';
            $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => $ut . '.output.data')) . '" class="list-group-item">' . Language::labelOutputData() . '</a>';
            $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => $ut . '.output.statistics')) . '" class="list-group-item">' . Language::labelOutputStatistics() . '</a>';
            $returnStr .= '</div>';
            $returnStr .= '</div>';
        }

        $returnStr .= '<span class="label label-default">' . Language::labelOutputMeta() . '</span>';
        $returnStr .= '<div class="well">';
        $returnStr .= '<div class="list-group">';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => $ut . '.output.documentation')) . '" class="list-group-item">' . Language::labelOutputDocumentation() . '</a>';
        $returnStr .= '</div>';
        $returnStr .= '</div>';
        return $returnStr;
    }

    function showOutputData() {
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output'), Language::headerOutput()), 'label' => Language::headerOutput());
        $headers[] = array('link' => '', 'label' => Language::headerOutputData());
        $returnStr = $this->showOutputHeader($headers);
        $returnStr .= $this->showOutputDataList();
        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();
        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function showOutputDataList() {
        $returnStr = '<div class="list-group">';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'sysadmin.output.datasingle')) . '" class="list-group-item">' . Language::labelOutputRawData() . '</a>';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'sysadmin.output.addondata')) . '" class="list-group-item">' . Language::labelOutputAuxiliaryData() . '</a>';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'sysadmin.output.remarkdata')) . '" class="list-group-item">' . Language::labelOutputRemarkData() . '</a>';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'sysadmin.output.timings')) . '" class="list-group-item">' . Language::labelOutputTimings() . '</a>';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'sysadmin.output.screendumps')) . '" class="list-group-item">' . Language::labelOutputScreenDumps() . '</a>';
        $returnStr .= '</div>';
        return $returnStr;
    }

    function showOutputRawData() {
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output'), Language::headerOutput()), 'label' => Language::headerOutput());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.data'), Language::headerOutputData()), 'label' => Language::headerOutputData());
        $headers[] = array('link' => '', 'label' => Language::headerOutputRawData());
        $returnStr = $this->showOutputHeader($headers);
        $returnStr .= $this->showOutputRawDataList();
        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();
        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function showOutputRawDataList() {
        $returnStr = '<div class="list-group">';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'sysadmin.output.datasingle')) . '" class="list-group-item">' . Language::labelOutputDataSingle() . '</a>';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'sysadmin.output.datamultiple')) . '" class="list-group-item">' . Language::labelOutputDataMultiple() . '</a>';
        $returnStr .= '</div>';
        return $returnStr;
    }

    function showOutputRemarkData() {

        $suid = loadvar('survey');
        if ($suid == "") {
            $suid = $_SESSION['SUID'];
        }
        $survey = new Survey($suid);
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output'), Language::headerOutput()), 'label' => Language::headerOutput());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.data'), Language::headerOutputData()), 'label' => Language::headerOutputData());
        $headers[] = array('link' => '', 'label' => Language::headerOutputRemarkData());
        $returnStr = $this->showOutputHeader($headers);
        $returnStr .= $this->displayComboBox();

        $returnStr .= '<form ' . POST_PARAM_NOAJAX . '=' . NOAJAX . ' id=surveyform method="post">';
        $returnStr .= setSessionParamsPost(array('page' => 'sysadmin.output.remarkdatares'));

        $returnStr .= '<span class="label label-default">' . Language::labelOutputDataSource() . '</span>';
        $returnStr .= '<div class="well well-sm">';
        $returnStr .= '<table>';

        $returnStr .= '<tr><td>' . Language::labelOutputDataTable() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_TYPE . ">";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . DATA_OUTPUT_TYPE_DATARECORD_TABLE . ">" . Language::optionsDataDataRecordTable() . "</option>";
        $returnStr .= "<option value=" . DATA_OUTPUT_TYPE_DATA_TABLE . ">" . Language::optionsDataDataTable() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $user = new User($_SESSION['URID']);
        $modes = $user->getModes();
        $langs = array();
        foreach ($modes as $m) {
            $langs = array_merge($langs, explode("~", $user->getLanguages($m)));
        }
        $langs = array_unique($langs);

        $returnStr .= '<tr><td>' . Language::labelOutputDataSurvey() . '</td><td>' . $this->displaySurveys(DATA_OUTPUT_SURVEY, DATA_OUTPUT_SURVEY, $suid, '', "") . '</td></tr>';
        $returnStr .= '<tr><td>' . Language::labelOutputDataMode() . '</td><td>' . $this->displayModesAdmin(DATA_OUTPUT_MODES, DATA_OUTPUT_MODES, MODE_CAPI . "~" . MODE_CATI . "~" . MODE_CASI, "multiple", implode("~", $modes)) . '</td></tr>';
        $returnStr .= '<tr><td>' . Language::labelOutputDataLanguage() . '</td><td>' . $this->displayLanguagesAdmin(DATA_OUTPUT_LANGUAGES, DATA_OUTPUT_LANGUAGES, implode("~", $langs), true, false, false, "multiple", implode("~", $langs)) . '</td></tr>';
        $returnStr .= '<tr><td>' . Language::labelOutputDataType() . '</td><td>';

        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_TYPEDATA . ">";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . DATA_REAL . ">" . Language::optionsDataReal() . "</option>";
        $returnStr .= "<option value=" . DATA_TEST . ">" . Language::optionsDataTest() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataCompleted() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_COMPLETED . ">";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . INTERVIEW_NOTCOMPLETED . ">" . Language::optionsDataNotCompleted() . "</option>";
        $returnStr .= "<option value=" . INTERVIEW_COMPLETED . ">" . Language::optionsDataCompleted() . "</option>";
        $returnStr .= "</select>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataClean() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_CLEAN . ">";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . DATA_CLEAN . ">" . Language::optionsDataClean() . "</option>";
        $returnStr .= "<option value=" . DATA_DIRTY . ">" . Language::optionsDataDirty() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataKeepOnly() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_KEEP_ONLY . ">";
        $returnStr .= "<option value=" . DATA_KEEP_NO . ">" . Language::optionsDataKeepNo() . "</option>";
        $returnStr .= "<option value=" . DATA_KEEP_YES . ">" . Language::optionsDataKeepYes() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataHidden() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_HIDDEN . ">";
        $returnStr .= "<option value=" . DATA_NOTHIDDEN . ">" . Language::optionsDataNotHidden() . "</option>";
        $returnStr .= "<option value=" . DATA_HIDDEN . ">" . Language::optionsDataHidden() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";
        $returnStr .= '</table>';
        $returnStr .= '</div>';

        $returnStr .= '<span class="label label-default">' . Language::labelOutputDataFormat() . '</span>';
        $returnStr .= '<div class="well well-sm">';
        $returnStr .= '<table>';

        $returnStr .= '<tr><td>' . Language::labelOutputDataFileName() . '</td><td>';
        $returnStr .= "<div class='input-group'><input type=text class='form-control' name='" . DATA_OUTPUT_FILENAME . "' ><span class='input-group-addon'>" . Language::labelOutputDataFileNameNoExtension() . "</span></div>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataPrimaryKey() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name='" . DATA_OUTPUT_PRIMARY_KEY_IN_DATA . "'>";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . PRIMARYKEY_YES . ">" . Language::optionsPrimaryKeyInDataYes() . "</option>";
        $returnStr .= "<option value=" . PRIMARYKEY_NO . ">" . Language::optionsPrimaryKeyInDataNo() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataPrimaryKeyEncryption() . '</td><td>';
        $returnStr .= "<div class='input-group'><input type=text class='form-control' name='" . DATA_OUTPUT_PRIMARY_KEY_ENCRYPTION . "' ><span class='input-group-addon'>" . Language::labelOutputDataPrimaryKeyEncryptionNo() . "</span></div>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataFieldname() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name='" . DATA_OUTPUT_FIELDNAME_CASE . "'>";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . FIELDNAME_LOWERCASE . ">" . Language::optionsFieldnameLowerCase() . "</option>";
        $returnStr .= "<option value=" . FIELDNAME_UPPERCASE . ">" . Language::optionsFieldnameUpperCase() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '</table>';
        $returnStr .= '</div>';

        $returnStr .= '<input type="submit" class="btn btn-default" value="' . Language::buttonDownload() . '"/>';
        $returnStr .= '</form>';

        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();

        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function showOutputTimingsData() {

        $suid = loadvar('survey');
        if ($suid == "") {
            $suid = $_SESSION['SUID'];
        }
        $survey = new Survey($suid);
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output'), Language::headerOutput()), 'label' => Language::headerOutput());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.data'), Language::headerOutputData()), 'label' => Language::headerOutputData());
        $headers[] = array('link' => '', 'label' => Language::headerOutputTimingsData());
        $returnStr = $this->showOutputHeader($headers);
        $returnStr .= $this->displayComboBox();

        $returnStr .= '<form ' . POST_PARAM_NOAJAX . '=' . NOAJAX . ' id=surveyform method="post">';
        $returnStr .= setSessionParamsPost(array('page' => 'sysadmin.output.timingsres'));

        $returnStr .= '<span class="label label-default">' . Language::labelOutputDataSource() . '</span>';
        $returnStr .= '<div class="well well-sm">';
        $returnStr .= '<table>';

        $returnStr .= '<tr><td>' . Language::labelOutputDataSurvey() . '</td><td>' . $this->displaySurveys(DATA_OUTPUT_SURVEY, DATA_OUTPUT_SURVEY, $suid, '', "") . '</td></tr>';

        $user = new User($_SESSION['URID']);
        $modes = $user->getModes();
        $langs = array();
        foreach ($modes as $m) {
            $langs = array_merge($langs, explode("~", $user->getLanguages($m)));
        }
        $langs = array_unique($langs);

        $returnStr .= '<tr><td>' . Language::labelOutputDataMode() . '</td><td>' . $this->displayModesAdmin(DATA_OUTPUT_MODES, DATA_OUTPUT_MODES, MODE_CAPI . "~" . MODE_CATI . "~" . MODE_CASI, "multiple", implode("~", $modes)) . '</td></tr>';
        $returnStr .= '<tr><td>' . Language::labelOutputDataLanguage() . '</td><td>' . $this->displayLanguagesAdmin(DATA_OUTPUT_LANGUAGES, DATA_OUTPUT_LANGUAGES, implode("~", $langs), true, false, false, "multiple", implode("~", $langs)) . '</td></tr>';
        $returnStr .= '<tr><td>' . Language::labelOutputDataType() . '</td><td>';

        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_TYPEDATA . ">";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . DATA_REAL . ">" . Language::optionsDataReal() . "</option>";
        $returnStr .= "<option value=" . DATA_TEST . ">" . Language::optionsDataTest() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";
        $returnStr .= '</table>';
        $returnStr .= '</div>';

        $returnStr .= '<span class="label label-default">' . Language::labelOutputDataFormat() . '</span>';
        $returnStr .= '<div class="well well-sm">';
        $returnStr .= '<table>';

        $returnStr .= '<tr><td>' . Language::labelOutputDataFileName() . '</td><td>';
        $returnStr .= "<div class='input-group'><input type=text class='form-control' name='" . DATA_OUTPUT_FILENAME . "' ><span class='input-group-addon'>" . Language::labelOutputDataFileNameNoExtension() . "</span></div>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataPrimaryKey() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name='" . DATA_OUTPUT_PRIMARY_KEY_IN_DATA . "'>";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . PRIMARYKEY_YES . ">" . Language::optionsPrimaryKeyInDataYes() . "</option>";
        $returnStr .= "<option value=" . PRIMARYKEY_NO . ">" . Language::optionsPrimaryKeyInDataNo() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataPrimaryKeyEncryption() . '</td><td>';
        $returnStr .= "<div class='input-group'><input type=text class='form-control' name='" . DATA_OUTPUT_PRIMARY_KEY_ENCRYPTION . "' ><span class='input-group-addon'>" . Language::labelOutputDataPrimaryKeyEncryptionNo() . "</span></div>";
        $returnStr .= "</td></tr>";


        $returnStr .= '</table>';
        $returnStr .= '</div>';

        $returnStr .= '<input type="submit" class="btn btn-default" value="' . Language::buttonDownload() . '"/>';
        $returnStr .= '</form>';

        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();

        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function showOutputAddOnData() {

        $suid = loadvar('survey');
        if ($suid == "") {
            $suid = $_SESSION['SUID'];
        }
        $survey = new Survey($suid);
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output'), Language::headerOutput()), 'label' => Language::headerOutput());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.data'), Language::headerOutputData()), 'label' => Language::headerOutputData());
        $headers[] = array('link' => '', 'label' => Language::headerOutputAuxiliaryData());
        $returnStr = $this->showOutputHeader($headers);
        $returnStr .= $this->displayComboBox();
        $returnStr .= '<form ' . POST_PARAM_NOAJAX . '=' . NOAJAX . ' id=surveyform method="post">';
        $returnStr .= setSessionParamsPost(array('page' => 'sysadmin.output.addondatares'));

        $returnStr .= '<span class="label label-default">' . Language::labelOutputDataSource() . '</span>';
        $returnStr .= '<div class="well well-sm">';
        $returnStr .= '<table>';

        $returnStr .= '<tr><td>' . Language::labelOutputDataTable() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_TYPE . ">";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . DATA_OUTPUT_TYPE_DATARECORD_TABLE . ">" . Language::optionsDataDataRecordTable() . "</option>";
        $returnStr .= "<option value=" . DATA_OUTPUT_TYPE_DATA_TABLE . ">" . Language::optionsDataDataTable() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataSurvey() . '</td><td>' . $this->displaySurveys(DATA_OUTPUT_SURVEY, DATA_OUTPUT_SURVEY, $suid, '', "") . '</td></tr>';
        $returnStr .= '<tr><td>' . Language::labelOutputDataType() . '</td><td>';

        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_TYPEDATA . ">";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . DATA_REAL . ">" . Language::optionsDataReal() . "</option>";
        $returnStr .= "<option value=" . DATA_TEST . ">" . Language::optionsDataTest() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataCompleted() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_COMPLETED . ">";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . INTERVIEW_NOTCOMPLETED . ">" . Language::optionsDataNotCompleted() . "</option>";
        $returnStr .= "<option value=" . INTERVIEW_COMPLETED . ">" . Language::optionsDataCompleted() . "</option>";
        $returnStr .= "</select>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataClean() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_CLEAN . ">";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . DATA_CLEAN . ">" . Language::optionsDataClean() . "</option>";
        $returnStr .= "<option value=" . DATA_DIRTY . ">" . Language::optionsDataDirty() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataKeepOnly() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_KEEP_ONLY . ">";
        $returnStr .= "<option value=" . DATA_KEEP_NO . ">" . Language::optionsDataKeepNo() . "</option>";
        $returnStr .= "<option value=" . DATA_KEEP_YES . ">" . Language::optionsDataKeepYes() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataHidden() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_HIDDEN . ">";
        $returnStr .= "<option value=" . DATA_NOTHIDDEN . ">" . Language::optionsDataNotHidden() . "</option>";
        $returnStr .= "<option value=" . DATA_HIDDEN . ">" . Language::optionsDataHidden() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";
        $returnStr .= '</table>';
        $returnStr .= '</div>';

        /* format */

        /*
          exportDirectory
          encoding
          outputType
         * 
         */
        $returnStr .= '<span class="label label-default">' . Language::labelOutputDataFormat() . '</span>';
        $returnStr .= '<div class="well well-sm">';
        $returnStr .= '<table>';

        $returnStr .= '<tr><td>' . Language::labelOutputDataFileName() . '</td><td>';
        $returnStr .= "<div class='input-group'><input type=text class='form-control' name='" . DATA_OUTPUT_FILENAME . "' ><span class='input-group-addon'>" . Language::labelOutputDataFileNameNoExtension() . "</span></div>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataPrimaryKey() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name='" . DATA_OUTPUT_PRIMARY_KEY_IN_DATA . "'>";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . PRIMARYKEY_YES . ">" . Language::optionsPrimaryKeyInDataYes() . "</option>";
        $returnStr .= "<option value=" . PRIMARYKEY_NO . ">" . Language::optionsPrimaryKeyInDataNo() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataPrimaryKeyEncryption() . '</td><td>';
        $returnStr .= "<div class='input-group'><input type=text class='form-control' name='" . DATA_OUTPUT_PRIMARY_KEY_ENCRYPTION . "' ><span class='input-group-addon'>" . Language::labelOutputDataPrimaryKeyEncryptionNo() . "</span></div>";
        $returnStr .= "</td></tr>";

        $returnStr .= '</table>';
        $returnStr .= '</div>';

        $returnStr .= '<input type="submit" class="btn btn-default" value="' . Language::buttonDownload() . '"/>';
        $returnStr .= '</form>';

        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();

        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function showOutputDataSingleSurvey() {
        $suid = loadvar('survey');
        if ($suid == "") {
            $suid = $_SESSION['SUID'];
        }
        $survey = new Survey($suid);
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output'), Language::headerOutput()), 'label' => Language::headerOutput());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.data'), Language::headerOutputData()), 'label' => Language::headerOutputData());
        //$headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.rawdata'), Language::headerOutputRawData()), 'label' => Language::headerOutputRawData());
        $headers[] = array('link' => '', 'label' => Language::headerOutputRawData());
        //$headers[] = array('link' => '', 'label' => Language::headerOutputRawDataSingle());
        $returnStr = $this->showOutputHeader($headers);
        $returnStr .= $this->displayComboBox();
        $returnStr .= '<form ' . POST_PARAM_NOAJAX . '=' . NOAJAX . ' id=surveyform method="post">';
        $returnStr .= setSessionParamsPost(array('page' => 'sysadmin.output.datasingleres'));

        /* DATA CRITERIA */
//TODO:              limitToFields
//TODO:      primkeys

        $returnStr .= '<span class="label label-default">' . Language::labelOutputDataSource() . '</span>';
        $returnStr .= '<div class="well well-sm">';
        $returnStr .= '<table>';

        $returnStr .= '<tr><td>' . Language::labelOutputDataTable() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_TYPE . ">";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . DATA_OUTPUT_TYPE_DATARECORD_TABLE . ">" . Language::optionsDataDataRecordTable() . "</option>";
        $returnStr .= "<option value=" . DATA_OUTPUT_TYPE_DATA_TABLE . ">" . Language::optionsDataDataTable() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $user = new User($_SESSION['URID']);
        $modes = $user->getModes();
        $langs = array();
        foreach ($modes as $m) {
            $langs = array_merge($langs, explode("~", $user->getLanguages($m)));
        }
        $langs = array_unique($langs);

        $returnStr .= '<tr><td>' . Language::labelOutputDataSurvey() . '</td><td>' . $this->displaySurveys(DATA_OUTPUT_SURVEY, DATA_OUTPUT_SURVEY, $suid, '', "") . '</td></tr>';
        $returnStr .= '<tr><td>' . Language::labelOutputDataMode() . '</td><td>' . $this->displayModesAdmin(DATA_OUTPUT_MODES, DATA_OUTPUT_MODES, MODE_CAPI . "~" . MODE_CATI . "~" . MODE_CASI, "multiple", implode("~", $modes)) . '</td></tr>';
        $returnStr .= '<tr><td>' . Language::labelOutputDataLanguage() . '</td><td>' . $this->displayLanguagesAdmin(DATA_OUTPUT_LANGUAGES, DATA_OUTPUT_LANGUAGES, implode("~", $langs), true, false, false, "multiple", implode("~", $langs)) . '</td></tr>';
        $returnStr .= '<tr><td>' . Language::labelOutputDataType() . '</td><td>';

        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_TYPEDATA . ">";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . DATA_REAL . ">" . Language::optionsDataReal() . "</option>";
        $returnStr .= "<option value=" . DATA_TEST . ">" . Language::optionsDataTest() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataCompleted() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_COMPLETED . ">";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . INTERVIEW_NOTCOMPLETED . ">" . Language::optionsDataNotCompleted() . "</option>";
        $returnStr .= "<option value=" . INTERVIEW_COMPLETED . ">" . Language::optionsDataCompleted() . "</option>";
        $returnStr .= "</select>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataClean() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_CLEAN . ">";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . DATA_CLEAN . ">" . Language::optionsDataClean() . "</option>";
        $returnStr .= "<option value=" . DATA_DIRTY . ">" . Language::optionsDataDirty() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataKeepOnly() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_KEEP_ONLY . ">";
        $returnStr .= "<option value=" . DATA_KEEP_NO . ">" . Language::optionsDataKeepNo() . "</option>";
        $returnStr .= "<option value=" . DATA_KEEP_YES . ">" . Language::optionsDataKeepYes() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataHidden() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_HIDDEN . ">";
        $returnStr .= "<option value=" . DATA_NOTHIDDEN . ">" . Language::optionsDataNotHidden() . "</option>";
        $returnStr .= "<option value=" . DATA_HIDDEN . ">" . Language::optionsDataHidden() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";
        $returnStr .= '</table>';
        $returnStr .= '</div>';

        /* format */

        /*
          exportDirectory
          encoding
          outputType
         * 
         */
        $returnStr .= '<span class="label label-default">' . Language::labelOutputDataFormat() . '</span>';
        $returnStr .= '<div class="well well-sm">';
        $returnStr .= '<table>';

        $returnStr .= '<tr><td>' . Language::labelOutputDataFileType() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name=" . DATA_OUTPUT_FILETYPE . ">";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . FILETYPE_STATA . ">" . Language::optionsFileTypeStata() . "</option>";
        $returnStr .= "<option value=" . FILETYPE_CSV . ">" . Language::optionsFileTypeCSV() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataFileName() . '</td><td>';
        $returnStr .= "<div class='input-group'><input type=text class='form-control' name='" . DATA_OUTPUT_FILENAME . "' ><span class='input-group-addon'>" . Language::labelOutputDataFileNameNoExtension() . "</span></div>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataPrimaryKey() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name='" . DATA_OUTPUT_PRIMARY_KEY_IN_DATA . "'>";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . PRIMARYKEY_YES . ">" . Language::optionsPrimaryKeyInDataYes() . "</option>";
        $returnStr .= "<option value=" . PRIMARYKEY_NO . ">" . Language::optionsPrimaryKeyInDataNo() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataPrimaryKeyEncryption() . '</td><td>';
        $returnStr .= "<div class='input-group'><input type=text class='form-control' name='" . DATA_OUTPUT_PRIMARY_KEY_ENCRYPTION . "' ><span class='input-group-addon'>" . Language::labelOutputDataPrimaryKeyEncryptionNo() . "</span></div>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataNoData() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name='" . DATA_OUTPUT_VARIABLES_WITHOUT_DATA . "'>";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . VARIABLES_WITHOUT_DATA_YES . ">" . Language::optionsVariablesNoDataInDataYes() . "</option>";
        $returnStr .= "<option value=" . VARIABLES_WITHOUT_DATA_NO . ">" . Language::optionsVariablesNoDataInDataNo() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataFieldname() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name='" . DATA_OUTPUT_FIELDNAME_CASE . "'>";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . FIELDNAME_LOWERCASE . ">" . Language::optionsFieldnameLowerCase() . "</option>";
        $returnStr .= "<option value=" . FIELDNAME_UPPERCASE . ">" . Language::optionsFieldnameUpperCase() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataValueLabel() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name='" . DATA_OUTPUT_INCLUDE_VALUE_LABELS . "'>";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . VALUELABEL_YES . ">" . Language::optionsValueLabelsYes() . "</option>";
        $returnStr .= "<option value=" . VALUELABEL_NO . ">" . Language::optionsValueLabelsNo() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataValueLabelNumbers() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name='" . DATA_OUTPUT_INCLUDE_VALUE_LABEL_NUMBERS . "'>";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . VALUELABELNUMBERS_YES . ">" . Language::optionsValueLabelNumbersYes() . "</option>";
        $returnStr .= "<option value=" . VALUELABELNUMBERS_NO . ">" . Language::optionsValueLabelNumbersNo() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";

        $returnStr .= '<tr><td>' . Language::labelOutputDataMarkEmpty() . '</td><td>';
        $returnStr .= "<select class='selectpicker show-tick' name='" . DATA_OUTPUT_MARK_EMPTY . "'>";
        //$returnStr .= "<option></option>";
        $returnStr .= "<option value=" . MARKEMPTY_IN_VARIABLE . ">" . Language::optionsMarkEmptyInVariable() . "</option>";
        $returnStr .= "<option value=" . MARKEMPTY_IN_SKIP_VARIABLE . ">" . Language::optionsMarkEmptyInSkipVariable() . "</option>";
        $returnStr .= "<option value=" . MARKEMPTY_NO . ">" . Language::optionsMarkEmptyNo() . "</option>";
        $returnStr .= "</select>";
        $returnStr .= "</td></tr>";
        $returnStr .= '</table>';
        $returnStr .= '</div>';
        $returnStr .= '<input type="submit" class="btn btn-default" value="' . Language::buttonDownload() . '"/>';
        $returnStr .= '</form>';

        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();

        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function showOutputDataMultipleSurvey() {
        $suid = loadvar('survey');
        if ($suid == "") {
            $suid = $_SESSION['SUID'];
        }
        $survey = new Survey($suid);
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output'), Language::headerOutput()), 'label' => Language::headerOutput());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.data'), Language::headerOutputData()), 'label' => Language::headerOutputData());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.rawdata'), Language::headerOutputRawData()), 'label' => Language::headerOutputRawData());
        $headers[] = array('link' => '', 'label' => Language::headerOutputRawDataMultiple());

        //
        $returnStr = $this->showOutputHeader($headers);

        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();

        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function showOutputStatistics($content = "") {
        $survey = new Survey(getSurvey());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output'), Language::headerOutput()), 'label' => Language::headerOutputData());
        $headers[] = array('link' => '', 'label' => Language::headerOutputStatistics());

        $returnStr = $this->showOutputHeader($headers);
        $returnStr .= $content;
        
        $returnStr .= '<form id=modeform method="post">';
        $returnStr .= '<input type=hidden name=r value="' . setSessionsParamString(getSessionParams()) . '">';        
        
        $returnStr .= '<script type=text/javascript>
                            $(document).ready(function(){
                                    $("#survey").on("change", function(event) {
                                        var values = $("#modeform").serialize();
                                        values += "&' . POST_PARAM_AJAX_LOAD . '=' . AJAX_LOAD . '";

                                        // Send the data using post
                                        var posting = $.post( $("#modeform").attr("action"), values );

                                        posting.done(function( data ) {       
                                          $("#content").html( $( data ).html());
                                          $("[data-hover=\'dropdown\']").dropdownHover();  
                                        });                                                                     
                                    });</script>';
        
        $returnStr .= $this->displayComboBox();
        $returnStr .= '<span class="label label-default">' . Language::labelOutputStatistics() . '</span>';
        $returnStr .= '<div class="well well-sm">';
        $returnStr .= '<table>';
        $returnStr .= '<tr><td>' . Language::labelOutputScreenDumpsSurvey() . '</td><td>' . $this->displaySurveys("survey", "survey", $_SESSION['SUID'], '', "multiple") . '</tr>';
        $returnStr .= '</table><br/>';
        $returnStr .= '</form>';
        
        $returnStr .= '<div class="list-group">';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'sysadmin.output.statistics.response')) . '" class="list-group-item">' . 'Response' . '</a>';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'sysadmin.output.statistics.aggregates')) . '" class="list-group-item">' . 'Aggregate data' . '</a>';
        $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'sysadmin.output.statistics.contacts.graphs')) . '" class="list-group-item">' . 'Contact graphs' . '</a>';

        $returnStr .= '</div>';
        $returnStr .= '</div>';

        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();

        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function showOutputStatisticsAggregates($content = "") {
        $survey = new Survey($_SESSION['SUID']);
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output'), Language::headerOutput()), 'label' => Language::headerOutputData());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.statistics'), Language::headerOutputStatistics()), 'label' => Language::headerOutputStatistics());
        $headers[] = array('link' => '', 'label' => 'Aggregate data');

        $returnStr = $this->showOutputHeader($headers);
        $returnStr .= $content;
        $returnStr .= $this->displayComboBox();
        $surveys = new Surveys();
        $surveys = $surveys->getSurveys();
        if (sizeof($surveys) > 0) {
            $returnStr .= "<form id=refreshform method=post>";
            $returnStr .= '<input type=hidden name=page value="tester.tools.test">';
            $returnStr .= '<input type=hidden name="' . SMS_POST_SURVEY . '" id="' . SMS_POST_SURVEY . '_hidden" value="' . getSurvey() . '">';
            $returnStr .= '<input type=hidden name="' . SMS_POST_MODE . '" id="' . SMS_POST_MODE . '_hidden" value="' . getSurveyMode() . '">';
            $returnStr .= '<input type=hidden name="' . SMS_POST_LANGUAGE . '" id="' . SMS_POST_LANGUAGE . '_hidden" value="' . getSurveyLanguage() . '">';
            $returnStr .= "</form>";

            $returnStr .= '<div class="well well-sm">';
            $returnStr .= '<table>';
            $returnStr .= '<tr><td>' . Language::labelTestSurvey() . "</td><td><select onchange='document.getElementById(\"" . SMS_POST_SURVEY . "_hidden\").value=this.value; document.getElementById(\"refreshform\").submit();' name=" . POST_PARAM_SUID . " class='selectpicker show-tick'>";
            $current = new Survey(getSurvey());
            foreach ($surveys as $survey) {
                $selected = "";
                if ($survey->getSuid() == $current->getSuid()) {
                    $selected = "SELECTED";
                }
                $returnStr .= "<option $selected value=" . $survey->getSuid() . '>' . $survey->getName() . '</option>';
            }
            $returnStr .= "</select></td></tr>";
            $returnStr .= '</table><br/><br/>';

            $sections = $survey->getSections();
            foreach ($sections as $section) {
                $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'sysadmin.output.statistics.aggregates.section', 'seid' => $section->getSeid())) . '" class="list-group-item">' . $section->getName() . ' ' . $section->getDescription() . '</a>';
            }
            $returnStr .= "</div>";
        } else {
            $returnStr .= $this->displayInfo(Language::messageNoSurveysAvailable());
        }
        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();

        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function showOutputStatisticsAggregatesSection($seid) {
        $survey = new Survey($_SESSION['SUID']);
        $section = $survey->getSection($seid);
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output'), Language::headerOutput()), 'label' => Language::headerOutputData());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.statistics'), Language::headerOutputStatistics()), 'label' => Language::headerOutputStatistics());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.statistics.aggregates'), 'Aggregate data'), 'label' => 'Aggregate data');
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.statistics.aggregates', 'suid' => $suid), $survey->getName()), 'label' => $survey->getName());
        $headers[] = array('link' => '', 'label' => $section->getName());

        $returnStr = $this->showOutputHeader($headers);
        $returnStr .= $content;


        $variables = $survey->getVariableDescriptives($seid);
        foreach ($variables as $variable) {
            if (!inArray($variable->getAnswerType(), array(ANSWER_TYPE_NONE, ANSWER_TYPE_SECTION))) {
                $returnStr .= '<a href="index.php?r=' . setSessionsParamString(array('page' => 'sysadmin.output.statistics.aggregates.variable', 'seid' => $seid, 'vsid' => $variable->getVsid())) . '" class="list-group-item">' . $variable->getName() . ' ' . $variable->getDescription() . '</a>';
            }
        }
        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();

        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function showOutputStatisticsAggregatesVariable($seid, $vsid) {
        $survey = new Survey($_SESSION['SUID']);
        $section = $survey->getSection($seid);        
        $variable = $survey->getVariableDescriptive($vsid);
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output'), Language::headerOutput()), 'label' => Language::headerOutputData());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.statistics'), Language::headerOutputStatistics()), 'label' => Language::headerOutputStatistics());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.statistics.aggregates'), 'Aggregate data'), 'label' => 'Aggregate data');
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.statistics.aggregates', 'suid' => $suid), $survey->getName()), 'label' => $survey->getName());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.statistics.aggregates.section', 'seid' => $seid), $section->getName()), 'label' => $section->getName());

        $headers[] = array('link' => '', 'label' => $variable->getName());

        $returnStr = $this->showOutputHeader($headers);

        $returnStr .= '<span class="label label-default">' . Language::labelAggregateDetails() . '</span>';
        $returnStr .= '<div class="well well-sm">';
        $returnStr .= '<br/><table>';
        $returnStr .= '<tr><td valign=top style="min-width: 100px;">' . Language::labelTypeEditGeneralQuestion() . ": </td><td valign=top>";
        $returnStr .= $variable->getQuestion() . "</td></tr>";
        $returnStr .= '<tr><td valign=top>' . Language::labelTypeEditGeneralAnswerType() . ": </td><td valign=top>";
        $answertype = $variable->getAnswerType();
        $arr = Language::getAnswerTypes();
        $returnStr .= $arr[$answertype] . "</td></tr>";
        if (inArray($answertype, array(ANSWER_TYPE_ENUMERATED, ANSWER_TYPE_SETOFENUMERATED, ANSWER_TYPE_DROPDOWN, ANSWER_TYPE_MULTIDROPDOWN))) {
            $returnStr .= '<tr><td valign=top>' . Language::labelTypeEditGeneralCategories() . ": </td><td valign=top>";
            $returnStr .= str_replace("\r\n", "<br/>", $variable->getOptionsText()) . "</td></tr>";
        }
        else if (inArray($answertype, array(ANSWER_TYPE_RANGE, ANSWER_TYPE_SLIDER))) {
            $returnStr .= '<tr><td valign=top>' . Language::labelTypeEditRangeMinimum() . ": </td><td valign=top>";
            $returnStr .= $variable->getMinimum() . "</td></tr>";
            $returnStr .= '<tr><td valign=top>' . Language::labelTypeEditRangeMaximum() . ": </td><td valign=top>";
            $returnStr .= $variable->getMaximum() . "</td></tr>";
        }
        
        if ($variable->isArray()) {
            $returnStr .= $this->displayComboBox();
            $returnStr .= '<tr><td valign=top>' . Language::labelTypeEditGeneralArrayInstance() . ": </td><td valign=top>";
            $options = $this->getArrayData($_SESSION['SUID'], $variable->getName());
            $returnStr .= "<form id=instanceform method=post>";
            $returnStr .= "<select class='selectpicker show-tick' id='arrayinstance' name='arrayinstance'>";
            foreach ($options as $op) {
                $returnStr .= "<option value='" . $op . "'>"  . $op . "</option>";                
            } 
            $returnStr .= "</select>";
            $returnStr .= "</td></tr>";            
            $params = getSessionParams();
            $params['vsid'] = $variable->getVsid();
            $returnStr .= setSessionParamsPost($params);
            $returnStr .= "</form>";
            $returnStr .= "<script type='text/javascript'>";
            $returnStr .= "$('#arrayinstance').change(function () {
                                $('#instanceform').submit();
                            });";
            $returnStr .= "</script>";
        }
        
        $returnStr .= "</table></div>";
        if (inArray($answertype, array(ANSWER_TYPE_NONE, ANSWER_TYPE_SECTION, ANSWER_TYPE_STRING, ANSWER_TYPE_OPEN))) {

            $returnStr .= '<span class="label label-default">' . Language::labelAggregateData() . '</span>';
            $returnStr .= '<div class="well well-sm">';
            $returnStr .= $this->displayWarning(Language::messageNoAggregateData());
            $returnStr .= "</div>";
        } else {

            $returnStr .= '<span class="label label-default">' . Language::labelAggregateData() . '</span>';
            $returnStr .= '<div class="well well-sm">';
            $data = new Data();
            $brackets = array();
            $varname = $variable->getName();
            if ($variable->isArray()) {                
                if (loadvar("arrayinstance") != "") {
                    $varname = loadvar("arrayinstance");
                }
                else {
                    $varname = $varname . "[1]";
                }
            }
            $aggdata = $data->getAggregrateData($variable, $varname, $brackets);
            
            //$aggdata = array(2,5);
            if (sizeof($aggdata) == 0) {
                $returnStr .= "<br>" . $this->displayWarning(Language::messageNoData());
            } else {

                $returnStr .= '<script src="js/highcharts/highcharts.js"></script>';
                $returnStr .= '<script src="js/modules/exporting.js"></script>';
                $returnStr .= '<script src="js/export-csv.js"></script>';
                $returnStr .= '<div id="chart1" style="min-width: 310px; height: 400px; margin: 0 auto"></div>';                
                switch ($answertype) {
                    case ANSWER_TYPE_ENUMERATED:
                        /* fall through */
                    case ANSWER_TYPE_SETOFENUMERATED:
                        /* fall through */
                    case ANSWER_TYPE_DROPDOWN:
                        /* fall through */
                    case ANSWER_TYPE_MULTIDROPDOWN:
                        $options = $variable->getOptions();
                        $brackets = array();
                        foreach ($options as $opt) {
                            $brackets[] = $opt["code"] . ' ' . $opt["label"];
                        }
                        $brackets[] = Language::labelOutputEmptyBracket();
                        $brackets[] = Language::labelOutputDKBracket();
                        $brackets[] = Language::labelOutputNABracket();
                        $brackets[] = Language::labelOutputRFBracket();
                        break;
                    case ANSWER_TYPE_INTEGER:
                        /* fall through */
                    case ANSWER_TYPE_SLIDER:
                        /* fall through */
                    case ANSWER_TYPE_RANGE:
                        /* fall through */
                    case ANSWER_TYPE_DOUBLE:
                        $brackets[] = Language::labelOutputEmptyBracket();
                        $brackets[] = Language::labelOutputDKBracket();
                        $brackets[] = Language::labelOutputNABracket();
                        $brackets[] = Language::labelOutputRFBracket();
                        break;
                    default:
                        break;
                }
                                
                $returnStr .= $this->createChart($variable->getName(), implode($aggdata, ","), $brackets);
            }
            $returnStr .= "</div>";
        }

        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();
        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function createChart($title, $data, $brackets = array()) {

        $bracks = '';
        for ($i = 0; $i < sizeof($brackets); $i++) {
            $br = $brackets[$i];
            $bracks .= "'" . $br . "'";
            if ($i + 1 <= sizeof($brackets)) {
                $bracks .= ",";
            }
        }
        $returnStr = '<script src="../js/export-csv.js"></script>';
        $returnStr.= "<script type='text/javascript'>
            

var chart = new Highcharts.Chart({

    chart: {
        renderTo: 'chart1',
            type: 'column',
            zoomType: 'x'            
        },
        title: {
            text: '" . $title . "'
        },
        subtitle: {
            text: 'Source: NubiS'
        },
        xAxis: {
            categories: [
                " . $bracks . "
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Number of respondents'
            }
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },            
        series: 
        
        [{  
            name: 'Responses',
            data: [" . $data . "]        
            ";

        $returnStr.= "                }]
            });
</script>";
        return $returnStr;
    }

    function showOutputResponse() {
        $survey = new Survey($_SESSION['SUID']);

        $headers = array();
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output'), Language::headerOutput()), 'label' => Language::headerOutputData());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.statistics'), Language::headerOutputStatistics()), 'label' => Language::headerOutputStatistics());
        $headers[] = array('link' => '', 'label' => 'Response');

        $returnStr = $this->showOutputHeader($headers);

        //$returnStr .= 'test<hr>';

        $returnStr .= '<script src="http://code.highcharts.com/highcharts.js"></script>';
        $returnStr .= '<script src="http://code.highcharts.com/modules/exporting.js"></script>';
        $returnStr .= '<div id="chart1" style="min-width: 310px; height: 400px; margin: 0 auto"></div>';
//        echo '<br/><br/><br/><br><br/>' . $this->getContactData();
//        echo '<hr><hr>';
        $returnStr .= $this->getResponseData();


        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();

        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function showOutputStatisticsContactsGraphs($seid) {
        $survey = new Survey(loadvar("survey"));

        $headers = array();
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output'), Language::headerOutput()), 'label' => Language::headerOutputData());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.statistics'), Language::headerOutputStatistics()), 'label' => Language::headerOutputStatistics());
        $headers[] = array('link' => '', 'label' => 'Contact graphs');

        $returnStr = $this->showOutputHeader($headers);

        // $returnStr .= 'test<hr>';

        $returnStr .= '<script src="http://code.highcharts.com/highcharts.js"></script>';
        $returnStr .= '<script src="http://code.highcharts.com/modules/exporting.js"></script>';
        $returnStr .= '<div id="chart1" style="min-width: 310px; height: 400px; margin: 0 auto"></div>';
//        echo '<br/><br/><br/><br><br/>' . $this->getContactData();
//        echo '<hr><hr>';
        $returnStr .= $this->getContactData();


        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();

        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function getResponseData() {
        $survey = new Survey(getSurvey());
        $title = $survey->getTitle();
        $sub = Language::labelResponseDataSubtitle();
        $names = array(Language::labelResponseDataStarted(), Language::labelResponseDataCompleted());
        $actiontype = array('begintime', 'endtime');


        $returnStr = '<script src="../js/export-csv.js"></script>';
        $returnStr .= "<script type='text/javascript'>


var chart = new Highcharts.Chart({

    chart: {
        renderTo: 'chart1',
                type: 'spline',
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
                    text: '# " . Language::labelResponseDataRespondents() . "'
                },
                min: 0
            },
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        Highcharts.dateFormat('%e. %b', this.x) +': '+ this.y + '" . Language::labelResponseDataRespondents() . "';
                }
            },
            
            series: [";

        foreach ($names as $key => $name) {
            if ($key != 0) {
                $returnStr .= ',';
            }
            $returnStr .= "{
                name: '" . $name . "',
                data: [";
            $returnStr .= $this->getFieldNotNull(getSurvey(), $actiontype[$key]);
            $returnStr .= "                ]
            }";
        }
        $returnStr .= "
      ]
        });
    //}); 


</script>";

        return $returnStr;
    }

    function getFieldNotNull($survey, $fieldname) {
        global $db;
        $dataStr = '';
        $actions = array();

        //99900174

        $query = 'select DATE(ts) as dateobs, count(*) as cntobs, primkey from ' . Config::dbSurveyData() . '_data where suid = ' . $survey . ' and variablename="' . $fieldname . '" and length(primkey) > ' . Config::getMinimumPrimaryKeyLength() . ' and length(primkey) < ' . Config::getMaximumPrimaryKeyLength() . '  and answer is not null group by DATE(ts) order by ts asc';
        $total = 0;
        $dataStr .= "[Date.UTC(2014,  6, 20), 0   ],";
        $result = $db->selectQuery($query);
        while ($row = $db->getRow($result)) {
            $key = $row['dateobs'];
            $total += $row['cntobs'];
            $dataStr .= "[Date.UTC(" . substr($key, 0, 4) . ", " . (substr($key, 5, 2) - 1) . ", " . substr($key, 8, 2) . "), " . $total . "],";
        }
        $returnStr = rtrim($dataStr, ',');
        return $returnStr;
    }
    
    function getArrayData($survey, $fieldname) {
        global $db;        
        $array = array();
        $query = 'select variablename from ' . Config::dbSurveyData() . '_data where suid = ' . $survey . ' and variablename like "' . $fieldname . '[%" and length(primkey) > ' . Config::getMinimumPrimaryKeyLength() . ' and length(primkey) < ' . Config::getMaximumPrimaryKeyLength();        
        $result = $db->selectQuery($query);
        if ($db->getNumberOfRows($result) > 0) {
            while ($row = $db->getRow($result)) {
                $array[] = $row["variablename"];
            }
        }        
        return $array;
    }

    function getContactData() {
        $title = Language::messageSMSTitle();
        $sub = Language::labelResponseDataContactsSub();
        $names = Language::labelResponseDataContacts();
        $actiontype = array(101, 103, 109, 502, 504);


        $returnStr = '<script src="../js/export-csv.js"></script>';
        $returnStr .= "<script type='text/javascript'>


var chart = new Highcharts.Chart({

    chart: {
        renderTo: 'chart1',
                type: 'spline',
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
                    text: '# " . Language::labelResponseDataRespondents() . "'
                },
                min: 0
            },
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        Highcharts.dateFormat('%e. %b', this.x) +': '+ this.y + '" . Language::labelResponseDataRespondents() . "';
                }
            },
            
            series: [";

        foreach ($names as $key => $name) {
            if ($key != 0) {
                $returnStr .= ',';
            }
            $returnStr .= "{
                name: '" . $name . "',
                data: [";
            $returnStr .= $this->getContactCodeData($actiontype[$key]);
            $returnStr .= "                ]
            }";
        }

        $returnStr .= "
      ]
        });
    //}); 


</script>";

        return $returnStr;
    }

    function getContactCodeData($code) {
        global $db;
        $dataStr = '';
        $actions = array();
        $query = 'select DATE(ts) as dateobs, count(*) as cntobs, primkey from ' . Config::dbSurveyData() . '_contacts where code = ' . $code . ' group by DATE(ts) order by ts asc';
        $total = 0;
        $dataStr .= "[Date.UTC(2014,  6, 20), 0   ],";
        $result = $db->selectQuery($query);
        while ($row = $db->getRow($result)) {
            $key = $row['dateobs'];
            $total += $row['cntobs'];
            $dataStr .= "[Date.UTC(" . substr($key, 0, 4) . ", " . (substr($key, 5, 2) - 1) . ", " . substr($key, 8, 2) . "), " . $total . "],";
        }
        $returnStr = rtrim($dataStr, ',');
        return $returnStr;
    }

    function showOutputDocumentation() {
        $survey = new Survey(getSurvey());

        $user = new User($_SESSION['URID']);
        $utype = $user->getUserType();
        $ut = "";
        switch ($utype) {
            case USER_SYSADMIN:
                $ut = "sysadmin";
                $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output'), Language::headerOutput()), 'label' => Language::headerOutput());
                $headers[] = array('link' => '', 'label' => Language::headerOutputDocumentation());
                break;
            case USER_ADMIN:
                $ut = "admin";
                break;
            case USER_TRANSLATOR:
                $ut = "translator";
                $headers[] = array('link' => setSessionParamsHref(array('page' => 'translator.output'), Language::headerOutput()), 'label' => Language::headerOutput());
                $headers[] = array('link' => '', 'label' => Language::headerOutputDocumentation());
                break;
            case USER_INTERVIEWER:
                $ut = "interviewer";
                break;
        }
        $returnStr = $this->showOutputHeader($headers);

        $returnStr .= '<form id=modeform method="post">';
        $returnStr .= '<input type=hidden name=r value="' . setSessionsParamString(getSessionParams()) . '">';
        $returnStr .= $this->displayComboBox();
        $returnStr .= '<span class="label label-default">' . Language::labelOutputDocumentation() . '</span>';
        $returnStr .= '<div class="well well-sm">';
        $returnStr .= '<table>';
        $returnStr .= '<tr><td>' . Language::labelOutputDocumentationSurvey() . '</td><td>' . $this->displaySurveys("survey", "survey", $_SESSION['SUID'], '') . '</tr>';
        $returnStr .= '<tr><td>' . Language::labelOutputDocumentationMode() . '</td><td>' . $this->displayModesAdmin("surveymode", "surveymode", getSurveyMode(), "", implode("~", $user->getModes())) . '</tr>';

        /* language dropdown */
        $langs = explode("~", $user->getLanguages(getSurveyMode()));
        $default = $survey->getDefaultLanguage();
        if (!inArray($default, $langs)) {
            $langs[] = $default;
        }

        $returnStr .= '<tr><td>' . Language::labelOutputDocumentationLanguage() . '</td><td>' . $this->displayLanguagesAdmin("surveylanguage", "surveylanguage", getSurveyLanguage(), true, false, true, "", implode("~", $langs)) . '</tr>';

        $returnStr .= '</table><br/>';
        $returnStr .= '<script type=text/javascript>
                            $(document).ready(function(){
                                    $("#survey").on("change", function(event) {
                                        var values = $("#modeform").serialize();
                                        values += "&' . POST_PARAM_AJAX_LOAD . '=' . AJAX_LOAD . '";

                                        // Send the data using post
                                        var posting = $.post( $("#modeform").attr("action"), values );

                                        posting.done(function( data ) {       
                                          $("#content").html( $( data ).html());
                                          $("[data-hover=\'dropdown\']").dropdownHover();  
                                        });                                                                     
                                    });
                                
                                    $("#surveymode").on("change", function(event) {
                                        var values = $("#modeform").serialize();
                                        values += "&' . POST_PARAM_AJAX_LOAD . '=' . AJAX_LOAD . '";

                                        // Send the data using post
                                        var posting = $.post( $("#modeform").attr("action"), values );

                                        posting.done(function( data ) {       
                                          $("#content").html( $( data ).html());
                                          $("[data-hover=\'dropdown\']").dropdownHover();  
                                        });                                                                     
                                    });';

        //if ($ut != "sysadmin" && $ut != "translator") {
        $returnStr .= '$("#surveylanguage").on("change", function(event) {
                                        var values = $("#modeform").serialize();
                                        values += "&' . POST_PARAM_AJAX_LOAD . '=' . AJAX_LOAD . '";

                                        // Send the data using post
                                        var posting = $.post( $("#languageform").attr("action"), values );

                                        posting.done(function( data ) {       
                                          $("#content").html( $( data ).html());
                                          $("[data-hover=\'dropdown\']").dropdownHover();  
                                        });  
                                    });';
        //}
        $returnStr .= '
                                });
                            </script>';
        $returnStr .= "</form>";

        $returnStr .= '<div class="list-group">';

        $user = new User($_SESSION['URID']);
        $utype = $user->getUserType();
        if (inArray($utype, array(USER_SYSADMIN))) {
            $returnStr .= '<a target="_blank" href="index.php?r=' . setSessionsParamString(array('page' => $ut . '.output.documentation.dictionary')) . '" class="list-group-item">' . Language::labelOutputDocumentationDictionary() . '</a>';
            $returnStr .= '<a target="_blank" href="index.php?r=' . setSessionsParamString(array('page' => $ut . '.output.documentation.routing')) . '" class="list-group-item">' . Language::labelOutputDocumentationRouting() . '</a>';
            $returnStr .= '<a target="_blank" href="index.php?r=' . setSessionsParamString(array('page' => $ut . '.output.documentation.routing.dash')) . '" class="list-group-item">' . Language::labelOutputDocumentationRouting() . ' (text only)</a>';
        }
        $returnStr .= '<a target="_blank" href="index.php?r=' . setSessionsParamString(array('page' => $ut . '.output.documentation.translation')) . '" class="list-group-item">' . Language::labelOutputTranslation() . '</a>';
        $returnStr .= '</div>';
        $returnStr .= '</div>';

        $returnStr .= '</p></div>    </div>'; //container and wrap        
        $returnStr .= $this->showBottomBar();

        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function replaceFills($text) {
        return $text;
    }

    function showOutputDictionary() {
        $_SESSION['PARAMETER_RETRIEVAL'] = PARAMETER_SURVEY_RETRIEVAL;
        $survey = new Survey($_SESSION['SUID']);
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output'), Language::headerOutput()), 'label' => Language::headerOutput());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.documentation'), Language::headerOutputDocumentation()), 'label' => Language::headerOutputDocumentation());
        $headers[] = array('link' => '', 'label' => Language::headerOutputDictionary());

        $returnStr = $this->showOutputHeader($headers, false);
        $returnStr .= '<div>';


        /* loop through sections */
        $sections = $survey->getSections();
        foreach ($sections as $section) {
            $returnStr .= "<div class='uscic-dictionary-section'>Section: " . $section->getName() . "</div>";
            $vars = $survey->getVariableDescriptives($section->getSeid());
            foreach ($vars as $var) {
                if ($var->isHiddenPaperVersion() == false) {
                    $answertype = $var->getAnswerType();
                    if ($answertype == SETTING_FOLLOW_TYPE) {
                        $type = $survey->getType($var->getTyd());
                        $answertype = $type->getAnswerType();
                    }

                    $table = "";
                    if ($var->getDescription() != "") {
                        $table .= "<tr><td width='200px'>Description</td>";
                        $table .= "<td class='uscic-dictionary-description'>" . $var->getDescription() . "</td></tr>";
                    }

                    if (trim($var->getQuestion()) != "") {
                        $table .= "<tr><td width='200px'>Text</td>";
                        $table .= "<td class='uscic-dictionary-text'>" . convertHTLMEntities($this->replaceFills($var->getQuestion())) . "</td></tr>";
                    }

                    if (inArray($answertype, array(ANSWER_TYPE_ENUMERATED, ANSWER_TYPE_SETOFENUMERATED, ANSWER_TYPE_DROPDOWN, ANSWER_TYPE_MULTIDROPDOWN))) {
                        $table .= "<tr><td width='200px'>Answer options</td>";
                        $table .= "<td class='uscic-dictionary-categories'>" . str_replace("\r\n", "<br/>", convertHTLMEntities($this->replaceFills($var->getOptionsText()))) . "</td></tr>";
                    }

                    if (trim($var->getPreText()) != "") {
                        $table .= "<tr><td width='200px'>Text before answer box</td>";
                        $table .= "<td class='uscic-dictionary-pretext'>" . str_replace("\r\n", "<br/>", convertHTLMEntities($this->replaceFills($var->getPreText()))) . "</td></tr>";
                    }
                    if (trim($var->getPostText()) != "") {
                        $table .= "<tr><td width='200px'>Text after answer box</td>";
                        $table .= "<td class='uscic-dictionary-posttext'>" . str_replace("\r\n", "<br/>", convertHTLMEntities($this->replaceFills($var->getPostText()))) . "</td></tr>";
                    }

                    /* if (trim($var->getFillText()) != "") {
                      $table .= "<tr><td width='200px'>Dynamic text</td>";
                      $table .= "<td class='uscic-dictionary-filltext'>" . str_replace("\r\n", "<br/>", convertHTLMEntities($this->replaceFills($var->getFillText()))) . "</td></tr>";
                      }

                      if (strtoupper($var->getLabelBackButton()) != $backbutton) {
                      $table .= "<tr><td width='200px'>Back button</td>";
                      $table .= "<td class='uscic-dictionary-button'>" . str_replace("\r\n", "<br/>", convertHTLMEntities($this->replaceFills($var->getLabelBackButton()))) . "</td></tr>";
                      }

                      if (strtoupper($var->getLabelNextButton()) != $nextbutton) {
                      $table .= "<tr><td width='200px'>Next button</td>";
                      $table .= "<td class='uscic-dictionary-button'>" . str_replace("\r\n", "<br/>", convertHTLMEntities($this->replaceFills($var->getLabelNextButton()))) . "</td></tr>";
                      }

                      if (strtoupper($var->getLabelDKButton()) != $dkbutton) {
                      $table .= "<tr><td width='200px'>Don't know button</td>";
                      $table .= "<td class='uscic-dictionary-button'>" . str_replace("\r\n", "<br/>", convertHTLMEntities($this->replaceFills($var->getLabelDKButton()))) . "</td></tr>";
                      }

                      if (strtoupper($var->getLabelRFButton()) != $rfbutton) {
                      $table .= "<tr><td width='200px'>Refuse button</td>";
                      $table .= "<td class='uscic-dictionary-button'>" . str_replace("\r\n", "<br/>", convertHTLMEntities($this->replaceFills($var->getLabelRFButton()))) . "</td></tr>";
                      }

                      if (strtoupper($var->getLabelNAButton()) != $nabutton) {
                      $table .= "<tr><td width='200px'>Not applicable button</td>";
                      $table .= "<td class='uscic-dictionary-button'>" . str_replace("\r\n", "<br/>", convertHTLMEntities($this->replaceFills($var->getLabelNAButton()))) . "</td></tr>";
                      } */

                    if ($table != "") {
                        $returnStr .= "<div class='uscic-dictionary-question'>Question: " . $var->getName() . "</div>";
                        $returnStr .= "<table class='table table-bordered'>";
                        $returnStr .= $table;
                        $returnStr .= "</table>";
                    }
                }
            }
            $returnStr .= "<hr>";
            //break;
        }
        $returnStr .= '</div>';
        //$returnStr .= '<script type="text/javascript">$( document ).ready(function() {$("#editor").wysiwyg();});</script>';


        $returnStr .= '</p></div>    </div>'; //container and wrap
        //$returnStr .= $this->showBottomBar();

        $returnStr .= $this->showFooter(false);
        $_SESSION['PARAMETER_RETRIEVAL'] = PARAMETER_ADMIN_RETRIEVAL;
        return $returnStr;
    }

    function showOutputTranslation() {
        $_SESSION['PARAMETER_RETRIEVAL'] = PARAMETER_SURVEY_RETRIEVAL;
        $survey = new Survey($_SESSION['SUID']);


        $user = new User($_SESSION['URID']);
        $utype = $user->getUserType();
        $ut = "";
        switch ($utype) {
            case USER_SYSADMIN:
                $ut = "sysadmin";
                break;
            case USER_ADMIN:
                $ut = "admin";
                break;
            case USER_TRANSLATOR:
                $ut = "translator";
                break;
            case USER_INTERVIEWER:
                $ut = "interviewer";
                break;
        }

        $headers[] = array('link' => setSessionParamsHref(array('page' => $ut . '.output'), Language::headerOutput()), 'label' => Language::headerOutput());
        $headers[] = array('link' => setSessionParamsHref(array('page' => $ut . '.output.documentation'), Language::headerOutputDocumentation()), 'label' => Language::headerOutputDocumentation());
        $headers[] = array('link' => '', 'label' => Language::headerOutputDictionary());

        $returnStr = $this->showOutputHeader($headers, false);

        /* display survey texts */
        $returnStr .= "<div class='uscic-dictionary-messages'>Generic messages</div>";
        $returnStr .= "<table class='table table-bordered'";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceEmptyMessage() . "</td><td class='uscic-dictionary-message'>" . $survey->getEmptyMessage() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageDouble() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageDouble() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelGroupEditAssistanceExactRequired() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageExactRequired() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelGroupEditAssistanceExclusive() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageExclusive() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelGroupEditAssistanceInclusive() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageInclusive() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageInlineAnswered() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageInlineAnswered() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageInlineExactRequired() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageInlineExactRequired() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageInlineExclusive() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageInlineExclusive() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageInlineInclusive() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageInlineInclusive() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageInlineMaxRequired() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageInlineMaximumRequired() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageInlineMinRequired() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageInlineMinimumRequired() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageInteger() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageInteger() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageMaxCalendar() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageMaximumCalendar() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageMaxLength() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageMaximumLength() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelGroupEditAssistanceMaximumRequired() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageMaximumRequired() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageMaxWords() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageMaximumWords() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageMinLength() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageMinimumLength() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelGroupEditAssistanceMinimumRequired() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageMinimumRequired() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageMinWords() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageMinimumWords() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessagePattern() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessagePattern() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageRange() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageRange() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageExactSelect() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageSelectExact() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageInvalidSelect() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageSelectInvalidSet() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageInvalidSubSelect() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageSelectInvalidSubset() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageMaxSelect() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageSelectMaximum() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditAssistanceErrorMessageMinSelect() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageSelectMinimum() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelGroupEditAssistanceUniqueRequired() . "</td><td class='uscic-dictionary-message'>" . $survey->getErrorMessageUniqueRequired() . "</td></tr>";
        $returnStr .= "</table>";

        $backbutton = strtoupper($survey->getLabelBackButton());
        $nextbutton = strtoupper($survey->getLabelNextButton());
        $dkbutton = strtoupper($survey->getLabelDKButton());
        $rfbutton = strtoupper($survey->getLabelRFButton());
        $nabutton = strtoupper($survey->getLabelNAButton());
        $updatebutton = strtoupper($survey->getLabelUpdateButton());

        /* display survey texts */
        $returnStr .= "<div class='uscic-dictionary-messages'>Button labels</div>";
        $returnStr .= "<table class='table table-bordered'";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditBackButton() . "</td><td class='uscic-dictionary-message'>" . $survey->getLabelBackButton() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditNextButton() . "</td><td class='uscic-dictionary-message'>" . $survey->getLabelNextButton() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditDKButton() . "</td><td class='uscic-dictionary-message'>" . $survey->getLabelDKButton() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditRFButton() . "</td><td class='uscic-dictionary-message'>" . $survey->getLabelRFButton() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditNAButton() . "</td><td class='uscic-dictionary-message'>" . $survey->getLabelNAButton() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditUpdateButton() . "</td><td class='uscic-dictionary-message'>" . $survey->getLabelUpdateButton() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditRemarkButton() . "</td><td class='uscic-dictionary-message'>" . $survey->getLabelRemarkButton() . "</td></tr>";
        $returnStr .= "<tr><td width='200px'>" . Language::labelTypeEditCloseButton() . "</td><td class='uscic-dictionary-message'>" . $survey->getLabelCloseButton() . "</td></tr>";
        $returnStr .= "</table>";
        $returnStr .= "<hr>";

        /* loop through sections */
        $sections = $survey->getSections();
        foreach ($sections as $section) {
            $returnStr .= "<div class='uscic-dictionary-section'>Section: " . $section->getName() . "</div>";
            $vars = $survey->getVariableDescriptives($section->getSeid());
            foreach ($vars as $var) {
                if ($var->isHiddenTranslation() == false) {
                    $answertype = $var->getAnswerType();
                    if ($answertype == SETTING_FOLLOW_TYPE) {
                        $type = $survey->getType($var->getTyd());
                        $answertype = $type->getAnswerType();
                    }

                    $table = "";
                    //if ($var->getDescription() != "") {
                    //    $table .= "<tr><td width='200px'>Description</td>";
                    //    $table .= "<td class='uscic-dictionary-description'>" . $var->getDescription() . "</td></tr>";
                    //}

                    if (trim($var->getQuestion()) != "") {
                        $table .= "<tr><td width='200px'>Text</td>";
                        $table .= "<td class='uscic-dictionary-text'>" . convertHTLMEntities($this->replaceFills($var->getQuestion())) . "</td></tr>";
                    }

                    if (inArray($answertype, array(ANSWER_TYPE_ENUMERATED, ANSWER_TYPE_SETOFENUMERATED, ANSWER_TYPE_DROPDOWN, ANSWER_TYPE_MULTIDROPDOWN))) {
                        $table .= "<tr><td width='200px'>Answer options</td>";
                        $table .= "<td class='uscic-dictionary-categories'>" . str_replace("\r\n", "<br/>", convertHTLMEntities($this->replaceFills($var->getOptionsText()))) . "</td></tr>";
                    }

                    if (trim($var->getPreText()) != "") {
                        $table .= "<tr><td width='200px'>Text before answer box</td>";
                        $table .= "<td class='uscic-dictionary-pretext'>" . str_replace("\r\n", "<br/>", convertHTLMEntities($this->replaceFills($var->getPreText()))) . "</td></tr>";
                    }
                    if (trim($var->getPostText()) != "") {
                        $table .= "<tr><td width='200px'>Text after answer box</td>";
                        $table .= "<td class='uscic-dictionary-posttext'>" . str_replace("\r\n", "<br/>", convertHTLMEntities($this->replaceFills($var->getPostText()))) . "</td></tr>";
                    }

                    if (trim($var->getFillText()) != "") {
                        $table .= "<tr><td width='200px'>Dynamic text</td>";
                        $table .= "<td class='uscic-dictionary-filltext'>" . str_replace("\r\n", "<br/>", convertHTLMEntities($this->replaceFills($var->getFillText()))) . "</td></tr>";
                    }

                    if (strtoupper($var->getLabelBackButton()) != $backbutton) {
                        $table .= "<tr><td width='200px'>Back button</td>";
                        $table .= "<td class='uscic-dictionary-button'>" . str_replace("\r\n", "<br/>", convertHTLMEntities($this->replaceFills($var->getLabelBackButton()))) . "</td></tr>";
                    }

                    if (strtoupper($var->getLabelNextButton()) != $nextbutton) {
                        $table .= "<tr><td width='200px'>Next button</td>";
                        $table .= "<td class='uscic-dictionary-button'>" . str_replace("\r\n", "<br/>", convertHTLMEntities($this->replaceFills($var->getLabelNextButton()))) . "</td></tr>";
                    }

                    if (strtoupper($var->getLabelDKButton()) != $dkbutton) {
                        $table .= "<tr><td width='200px'>Don't know button</td>";
                        $table .= "<td class='uscic-dictionary-button'>" . str_replace("\r\n", "<br/>", convertHTLMEntities($this->replaceFills($var->getLabelDKButton()))) . "</td></tr>";
                    }

                    if (strtoupper($var->getLabelRFButton()) != $rfbutton) {
                        $table .= "<tr><td width='200px'>Refuse button</td>";
                        $table .= "<td class='uscic-dictionary-button'>" . str_replace("\r\n", "<br/>", convertHTLMEntities($this->replaceFills($var->getLabelRFButton()))) . "</td></tr>";
                    }

                    if (strtoupper($var->getLabelNAButton()) != $nabutton) {
                        $table .= "<tr><td width='200px'>Not applicable button</td>";
                        $table .= "<td class='uscic-dictionary-button'>" . str_replace("\r\n", "<br/>", convertHTLMEntities($this->replaceFills($var->getLabelNAButton()))) . "</td></tr>";
                    }

                    if ($table != "") {
                        $returnStr .= "<div class='uscic-dictionary-question'>Question: " . $var->getName() . "</div>";
                        $returnStr .= "<table class='table table-bordered'>";
                        $returnStr .= $table;
                        $returnStr .= "</table>";
                    }
                }
            }
            $returnStr .= "<hr>";
            //break;
        }
        //$returnStr .= '</div>';
        //$returnStr .= '<script type="text/javascript">$( document ).ready(function() {$("#editor").wysiwyg();});</script>';


        $returnStr .= '</p></div>    </div>'; //container and wrap
        //$returnStr .= $this->showBottomBar();

        $returnStr .= $this->showFooter(false);
        $_SESSION['PARAMETER_RETRIEVAL'] = PARAMETER_ADMIN_RETRIEVAL;
        return $returnStr;
    }

    function showOutputRoutingDash() {
        $survey = new Survey($_SESSION['SUID']);
        $returnStr = $this->showOutputHeader($headers, false);

        require_once('papergenerator.php');
        $gen = new PaperGenerator($_SESSION['SUID'], getSurveyVersion(), 1);
        $gen->generate(1);
        $returnStr .= $gen->getString();

        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function showOutputRouting() {
        $survey = new Survey($_SESSION['SUID']);
        $returnStr = $this->showOutputHeader($headers, false);

        require_once('papergenerator.php');
        $gen = new PaperGenerator($_SESSION['SUID'], getSurveyVersion());
        $gen->generate(1);
        $returnStr .= $gen->getString();

        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function showOutputHeader($actions, $navbar = true, $extra = '') {

        $user = new User($_SESSION['URID']);
        $utype = $user->getUserType();
        switch ($utype) {
            case USER_SYSADMIN:
                $returnStr = $this->showSysAdminHeader(Language::messageSMSTitle(), $extra);
                break;
            case USER_TRANSLATOR:
                $dt = new DisplayTranslator();
                $returnStr = $dt->showTranslatorHeader(Language::messageSMSTitle(), $extra);
                break;
            case USER_INTERVIEWER:
                $dt = new DisplayInterviewer();
                $returnStr = $dt->showHeader(Language::messageSMSTitle(), $extra);
                break;
            default:
                $returnStr = $this->showSysAdminHeader(Language::messageSMSTitle(), $extra);
                break;
        }
        $returnStr .= '<div id="wrap">';
        if ($navbar) {
            switch ($utype) {
                case USER_SYSADMIN:
                    $returnStr .= $this->showNavBar();
                    break;
                case USER_INTERVIEWER:
                    $dt = new DisplayInterviewer();
                    $returnStr .= $dt->showNavBar();
                    break;
                default:
                    $dt = new DisplayTranslator();
                    $returnStr .= $dt->showNavBar();
                    break;
            }
        }
        $returnStr .= '<div class="container">';

        if ($navbar) {
            $returnStr .= '<ol class="breadcrumb">';
            for ($i = 0; $i < sizeof($actions); $i++) {
                $action = $actions[$i];
                if ($action['link'] == '') {
                    $returnStr .= '<li class="active">' . $action['label'] . '</li>';
                } else {
                    $returnStr .= '<li>' . $action['link'] . '</li>';
                }
            }

            $returnStr .= '</ol>';
        }
//        $returnStr .= '<div class="row row-offcanvas row-offcanvas-right">';
//        $returnStr .= '<div id=sectiondiv class="col-xs-12 col-sm-9">';
//        $returnStr .= $message;
        return $returnStr;
    }

    function showOutputScreenDumps() {

        $suid = loadvar("survey");
        if ($suid == "") {
            $suid = $_SESSION['SUID'];
        }
        if ($suid == "") {
            $suid = 1;
        }
        $survey = new Survey($suid);
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output'), Language::headerOutput()), 'label' => Language::headerOutputData());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.data'), Language::headerOutputData()), 'label' => Language::headerOutputData());
        $headers[] = array('link' => '', 'label' => Language::headerOutputScreenDumps());

        $returnStr = $this->showOutputHeader($headers);
        $returnStr .= $content;

        $surveys = new Surveys();
        $surveys = $surveys->getSurveys();

        if (sizeof($surveys) > 0) {
            $returnStr .= $this->displayComboBox();
            $returnStr .= '<form id=surveyform method="post">';
            $returnStr .= '<span class="label label-default">' . Language::labelOutputScreenDumps() . '</span>';
            $returnStr .= '<div class="well well-sm">';
            $returnStr .= '<table>';
            $returnStr .= '<tr><td>' . Language::labelOutputScreenDumpsSurvey() . '</td><td>' . $this->displaySurveys("survey", "survey", $suid) . '</tr>';
            $returnStr .= '<script type=text/javascript>
                        $(document).ready(function(){
                            $("#survey").on("change", function(event) {
                                document.getElementById("sv").value = this.value;
                                document.getElementById("surveyform").submit();
                            });
                        });
                    </script>';
            $returnStr .= "</form>";
            $returnStr .= '<form method="post">';
            $returnStr .= "<input type=hidden name='sv' id='sv' value=$suid />";
            $returnStr .= "<input type=hidden name='type' id='type' value=1 />";
            $returnStr .= setSessionParamsPost(array('page' => 'sysadmin.output.screendumpsres', "cnt" => 0));
            $returnStr .= '<tr><td>' . Language::labelOutputScreenDumpsRespondent() . '</td><td>' . $this->displayRespondents($suid) . '</tr>';
            $returnStr .= '</table>';
            $returnStr .= '</div>';
            $returnStr .= '<input type="submit" onclick="$(\'#type\').val(1);" class="btn btn-default" value="' . Language::buttonView() . '"/>';
            $returnStr .= '<input type="submit" onclick="$(\'#type\').val(2);" class="btn btn-default" value="' . Language::buttonDownload() . '"/>';
            $returnStr .= '</form>';
        } else {
            $returnStr .= $this->displayInfo(Language::messageNoSurveysAvailable());
        }
//END CONTENT
        $returnStr .= '</p></div>    </div>'; //container and wrap
        $returnStr .= $this->showBottomBar();

        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

    function displayRespondents($suid) {
        $data = new Data();
        $respondents = $data->getRespondentPrimKeys(false, "ts");
        $returnStr = "<select class='selectpicker show-tick' name=respondent id=respondent>";
        foreach ($respondents as $respondent) {
            $returnStr .= "<option value='" . $respondent . "'>" . $respondent . "</option>";
        }
        $returnStr .= "</select>";
        return $returnStr;
    }

    function showOutputScreenDumpsRes() {
        $suid = loadvar("sv");
        if ($suid == "") {
            $suid = getFromSessionParams("sv");
        }
        $primkey = loadvar("respondent");
        if ($primkey == "") {
            $primkey = getFromSessionParams("respondent");
        }
        $type = loadvar("type");

        // download
        $data = new Data();
        if ($type == 2) {
            $dumps = $data->getScreendumps($suid, $primkey);

            header('Content-Type: application/html');
            header("Content-Disposition: attachment; filename=\"screenshots_" . $primkey . ".html\"");
            foreach ($dumps as $d) {
                echo $d;
            }
            exit;
        }

        // fix looping through screenshots!
        $cnt = getFromSessionParams("cnt");
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output'), Language::headerOutput()), 'label' => Language::headerOutputData());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.data'), Language::headerOutputData()), 'label' => Language::headerOutputData());
        $headers[] = array('link' => setSessionParamsHref(array('page' => 'sysadmin.output.screendumps'), Language::headerOutputScreenDumps()), 'label' => Language::headerOutputScreenDumps());
        $headers[] = array('link' => '', 'label' => Language::headerOutputScreenDumpsFor($primkey));

        $returnStr = $this->showOutputHeader($headers);
        $no = $data->getNumberOfScreenDumps($suid, $primkey);
        $screenshot = $data->getScreendump($suid, $primkey, $cnt);
        $strpos = strpos($screenshot, "<body>");
        $screenshot = substr($screenshot, $strpos + strlen("<body>"));
        $strpos = strpos($screenshot, "</body>");
        $screenshot = substr($screenshot, 0, $strpos);

        $returnStr .= '<span class="label label-default">' . Language::labelOutputScreenDumpsRespondentFor() . $primkey . '</span>';
        $returnStr .= '<div class="well well-sm">';

        $returnStr .= '<br/><br/><div id="carousel" class="carousel slide" data-interval="false" data-ride="carousel">';
        $shield = '<div class="rightshield"></div>';
        if ($cnt > 1) {
            $leftmargin = " margin-left: 140px; ";
            $shield = '<div class="leftshield"></div>';
        }
        $returnStr .= '<!-- Wrapper for slides -->
        <div class="carousel-inner">';
        $returnStr .= '<div class="item active">';
        $returnStr .= '<div class="with-shield">' . $shield . '<div style="' . $leftmargin . ' max-width: 80%;">' . $screenshot . '</div></div>
        </div>';

        $returnStr .= '
        </div>
        <!-- Controls -->';

        if ($cnt > 1) {
            $returnStr .= '<a class="left carousel-control" href="' . setSessionParams(array('page' => 'sysadmin.output.screendumpsres', 'sv' => $suid, "cnt" => ($cnt - 1), "respondent" => $primkey)) . '" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left"></span>
        </a>';
        }
        if ($cnt < $no) {
            $returnStr .= '<a class="right carousel-control" href="' . setSessionParams(array('page' => 'sysadmin.output.screendumpsres', 'sv' => $suid, "cnt" => ($cnt + 1), "respondent" => $primkey)) . '" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right"></span>
        </a>';
        }
        $returnStr .= '</div></div>';
        $returnStr .= $this->showFooter(false);
        return $returnStr;
    }

}

?>

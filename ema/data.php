<?php

/*
  ------------------------------------------------------------------------
  Copyright (C) 2014 Bart Orriens, Albert Weerman

  This library/program is free software; you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation; either version 2.1 of the License, or (at your option) any later version.

  This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.

  You should have received a copy of the GNU Lesser General Public License along with this library; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
  ------------------------------------------------------------------------
 */

class Data {

    function __construct() {
        
    }
    
    function getRespondentData($suid, $primkey) {
        global $db, $survey;
        $key = "answer as answer_dec";
        if ($survey->getDataEncryptionKey() != "") {
            $key = "aes_decrypt(answer, '" . $survey->getDataEncryptionKey() . "') as answer_dec";
        }
        $query = "select variablename, " . $key  . ", language, mode, ts from " . Config::dbSurveyData() . "_data where suid=" . $suid . " and primkey='" . $primkey  . "' order by ts asc, variablename asc";
        //echo $query;
        $res = $db->selectQuery($query);
        $arr = array();
        if ($res) {
            if ($db->getNumberOfRows($res) > 0) {
                while ($row = $db->getRow($res)) {
                    $arr[] = $row;
                }
            }
        }
        return $arr;
    }

    function getRespondentPrimKeys($completed = true, $orderBy = "ts") {
        global $db;
        $where = "";
        if ($complete) {
            $where = " where completed=" . prepareDatabaseString(INTERVIEW_COMPLETED) . " ";
        }
        $query = "select distinct primkey from " . Config::dbSurveyData() . "_data " . $where . " order by " . prepareDatabaseString($orderBy);
        $res = $db->selectQuery($query);
        $arr = array();
        if ($res) {
            if ($db->getNumberOfRows($res) > 0) {
                while ($row = $db->getRow($res)) {
                    $arr[] = $row["primkey"];
                }
            }
        }
        return $arr;
    }

    function getScreendumps($suid, $id) {
        global $db, $survey;
        $decrypt = "screen as screen_dec";
        if ($survey->getDataEncryptionKey() != "") {
            $decrypt = "aes_decrypt(screen, '" . $survey->getDataEncryptionKey() . "') as screen_dec";
        }
        $query = "select $decrypt from " . Config::dbSurveyData() . "_screendumps where suid=" . prepareDatabaseString($suid) . " and primkey='" . prepareDatabaseString($id) . "' order by ts asc";
        $res = $db->selectQuery($query);
        $arr = array();
        //echo $query;
        if ($res) {
            if ($db->getNumberOfRows($res) > 0) {
                while ($row = $db->getRow($res)) {
                    $arr[] = gzuncompress($row["screen_dec"]);
                    //$arr[] = ($row["screen"]);
                }
            }
        }
        return $arr;
    }

    function getScreendump($suid, $id, $cnt) {
        global $db, $survey;
        $decrypt = "screen as screen_dec";
        if ($survey->getDataEncryptionKey() != "") {
            $decrypt = "aes_decrypt(screen, '" . $survey->getDataEncryptionKey() . "') as screen_dec";
        }
        $query = "select $decrypt from " . Config::dbSurveyData() . "_screendumps where suid=" . prepareDatabaseString($suid) . " and primkey='" . prepareDatabaseString($id) . "' order by scdid limit " . $cnt . ", 1";
        $res = $db->selectQuery($query);
        $arr = array();
        //echo $query;
        if ($res) {
            if ($db->getNumberOfRows($res) > 0) {
                $row = $db->getRow($res);
                return gzuncompress($row["screen_dec"]);
            }
        }
        return "";
    }

    function getNumberOfScreenDumps($suid, $id) {
        global $db;
        $query = "select screen from " . Config::dbSurveyData() . "_screendumps where suid=" . prepareDatabaseString($suid) . " and primkey='" . prepareDatabaseString($id) . "'";
        $res = $db->selectQuery($query);
        $arr = array();
        //echo $query;
        if ($res) {
            return $db->getNumberOfRows($res);
        }
        return 0;
    }

    function getAggregrateData($variable, $name, &$brackets) {
        $_SESSION['PARAMETER_RETRIEVAL'] = PARAMETER_SURVEY_RETRIEVAL;
        $answertype = $variable->getAnswerType();
        if (inArray($answertype, array(ANSWER_TYPE_NONE, ANSWER_TYPE_SECTION, ANSWER_TYPE_STRING, ANSWER_TYPE_OPEN))) {
            return null;
        }

        global $survey, $db;
        $arr = array();
        $dkarray = array();
        $decrypt = "answer as data_dec";
        if ($survey->getDataEncryptionKey() != "") {
            $decrypt = "aes_decrypt(answer, '" . $survey->getDataEncryptionKey() . "') as data_dec";
        }
        //if ($variable->isArray()) {
        //    $query = "select $decrypt from " . Config::dbSurveyData() . "_data where suid=" . $survey->getSuid() . ' and variablename like "' . $name . '"' . " order by primkey";
        //} else {
            $query = "select $decrypt from " . Config::dbSurveyData() . "_data where suid=" . $survey->getSuid() . ' and variablename = "' . $name . '"' . " order by primkey";
        //}
        $res = $db->selectQuery($query);
        if ($res) {
            if ($db->getNumberOfRows($res) > 0) {
                while ($row = $db->getRow($res)) {
                    $ans = $row["data_dec"];
                    if (inArray($ans, array(ANSWER_DK, "", ANSWER_RF, ANSWER_NA))) {
                        $dkarray["'" . $ans . "'"]++;
                    } else {
                        
                        if (inArray($answertype, array(ANSWER_TYPE_ENUMERATED, ANSWER_TYPE_SETOFENUMERATED, ANSWER_TYPE_DROPDOWN, ANSWER_TYPE_MULTIDROPDOWN))) {
                            // set of enum/dropdown, then look at all options selected
                            if (inArray($answertype, array(ANSWER_TYPE_SETOFENUMERATED, ANSWER_TYPE_MULTIDROPDOWN))) {
                                $ans = explode(SEPARATOR_SETOFENUMERATED, $ans);
                                foreach ($ans as $a) {
                                    $arr[$a]++;
                                }
                            } else {
                                $arr[$ans]++;
                            }
                        }
                        else {
                            $arr[] = $ans;
                        }
                    }
                }
            }
        }

        // add non-chosen options
        $answertype = $variable->getAnswerType();
        if (inArray($answertype, array(ANSWER_TYPE_ENUMERATED, ANSWER_TYPE_SETOFENUMERATED, ANSWER_TYPE_DROPDOWN, ANSWER_TYPE_MULTIDROPDOWN))) {
            $options = $variable->getOptions();
            foreach ($options as $opt) {
                if (!isset($arr[$opt["code"]])) {
                    $arr[$opt["code"]] = 0;
                }
            }
        }
        // define brackets and recode
        else if (inArray($answertype, array(ANSWER_TYPE_INTEGER, ANSWER_TYPE_DOUBLE, ANSWER_TYPE_SLIDER, ANSWER_TYPE_RANGE))) {
            if (inArray($answertype, array(ANSWER_TYPE_SLIDER, ANSWER_TYPE_RANGE))) {
                $min = floor($variable->getMinimum());
                $max = ceil($variable->getMaximum());
            } else {
                $min = floor(min(array_keys($arr)));
                $max = ceil(max(array_keys($arr)));
            }

            $splt = (abs(min($arr)) + abs(max($arr))) / OUTPUT_AGGREGATE_NUMBEROFBRACKETS;
            $summation = array();
            $labels = array();
            $labels[0] = "  " . (string) (0) . "-" . (string) (min($arr)); // first label
            for ($i = 0; $i < count($arr); $i++) {
                for ($j = 0, $start = min($arr); $j < OUTPUT_AGGREGATE_NUMBEROFBRACKETS; $j++, $start += $splt) {
                    if ($arr[$i] >= $start && $arr[$i] < $start + $splt) {
                        $summation[$j + 1] = (isset($summation[$j + 1]) ? $summation[$j + 1] + 1 : 1);
                    }
                    $labels[$j + 1] = "  " . (string) ($start) . "-" . (string) ($start + $splt);
                }
            }
            $brackets = $labels;
            $arr = $summation;
            
            foreach ($labels as $k => $l) {
                if (!isset($arr[$k])) {
                    $arr[$k] = 0;
                }
            }
        }

        /* add any empty options */
        $a = array(ANSWER_DK, "", ANSWER_RF, ANSWER_NA);
        foreach ($a as $a1) {
            if (!isset($dkarray["'" . $a1 . "'"])) {
                $dkarray["'" . $a1 . "'"] = 0;
            }
        }

        // sort array from low to high        
        ksort($arr, SORT_NUMERIC);
        ksort($dkarray, SORT_NATURAL);

        // add dkarray if active
        $arr = array_merge($arr, $dkarray);
        //print_r($arr);
        // return result        

        $_SESSION['PARAMETER_RETRIEVAL'] = PARAMETER_ADMIN_RETRIEVAL;
        return $arr;
    }

    function getAggregrateDataOld($variable) {
        global $survey, $db;
        $arr = array();
        $decrypt = "data as data_dec";
        if ($survey->getDataEncryptionKey() != "") {
            $decrypt = "aes_decrypt(data, '" . $survey->getDataEncryptionKey() . "') as data_dec";
        }

        $query = "select $decrypt from " . Config::dbSurveyData() . "_datarecords where suid=" . $survey->getSuid() . $extracompleted . " order by primkey";
        $res = $db->selectQuery($query);
        $datanames = array();
        if ($res) {
            if ($db->getNumberOfRows($res) == 0) {
                return 'No records found';
            } else {
                /* go through records */
                while ($row = $db->getRow($res)) {
                    $record = new DataRecord();
                    $record->setAllData(unserialize(gzuncompress($row["data_dec"])));
                    $data = $record->getDataForVariable($variable->getName());
                    foreach ($data as $rec) {
                        $arr[$rec->getAnswer()]++;
                    }
                }
            }
        }
        return $arr;
    }

}

?>

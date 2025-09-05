<?php


class FFXIPH_HTMLTabMobSearch {
    public function __construct() { }

    public function searchForm(){
        $html = "<div id=\"FFXIPackageHelper_tabs_combatsim_searchForm\">" .
                "<span><i><b>Site very much under construction. Data displayed is based on ASB/LSB code. Please reach out to us on discord if you feel the numbers
                are incorrect so we can work to get them more accurate. </b></i></span>" .
                "<table><tbody>
                    <tr><td>
                        <table><tbody>
                        <tr>" . 
                            //<td>Mob Name<br><input class=\"FFXIPackageHelper_dynamiccontent_textinput\" name=\"mobNameSearch\" value=\"$this->mobName\" size=\"25\"></td>
                            "<td>Mob Name<br><input id=\"FFXIPackageHelper_dynamiccontent_combatsim_mobsearch\" class=\"FFXIPackageHelper_dynamiccontent_textinput\" size=\"25\">" . 
                                "<br>Level: " . FFXIPackageHelper_HTMLTableHelper::selectLvlDropDown("FFXIPackageHelper_dynamiccontent_selectLvlMob", 95) . 
                            "</td>" .
                        "</tr>
                        <tr>
                            <td><b>AND / OR</b></td>
                        </tr>
                        <tr>
                            <td>Zone<br>" . FFXIPackageHelper_HTMLOptions::zonesDropDown("FFXIPackageHelper_dynamiccontent_selectMobZoneName") . "<br><br><button id=\"FFXIPackageHelper_dynamiccontent_searchForMobAndZone\" class=\"FFXIPackageHelper_dynamiccontent_customButton\">Find Mob</button></td>
                        </tr>
                        </tbody></table>
                        </td>
                    </tr></tbody></table>
                    <div id=\"FFXIPackageHelper_tabs_combatsim_queryresult\"></div>
                </div>";
        return $html;
    }

}



?>
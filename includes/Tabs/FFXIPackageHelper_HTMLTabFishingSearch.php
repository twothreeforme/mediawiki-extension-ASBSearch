<?php


class FFXIPackageHelper_HTMLTabFishingSearch {
    
    private $query_limit = 0;
    private $baitName = "";
    private $fishName = "";
    private $zoneName = "";

    public function __construct($query = null) {
        if ( gettype($query) == 'array'){
            $this->query_limit = $query[0];
            $this->baitName = $query[1];
            $this->fishName = $query[2];
            $this->zoneName = $query[3];
        }
      }

    public function searchForm(){
        $html = "<div id=\"FFXIPackageHelper_tabs_fishing_searchForm\">" .
                    "<table><tbody>
                    <tr><td>
                        <table><tbody>
                        <tr>
                            <td>Bait<br><input class=\"FFXIPackageHelper_dynamiccontent_textinput\" name=\"baitSearch\" value=\"$this->baitName\" size=\"25\"></td>
                        </tr>
                        <tr>
                            <td>Fish<br><input class=\"FFXIPackageHelper_dynamiccontent_textinput\" name=\"fishNameSearch\" value=\"$this->fishName\" size=\"25\"></td>
                        </tr>
                        <tr>
                            <td>Zone<br>" . FFXIPackageHelper_HTMLOptions::fishZonesDropDown() . "<br><br><button id=\"FFXIPackageHelper_dynamiccontent_searchFishingSubmit\" class=\"FFXIPackageHelper_dynamiccontent_customButton\">Search</button></td>
                            </tr>
                        </tbody></table>
                        </td>
                        <td style=\"vertical-align:top;\">".$this->showShareButton()."<br>
                    </tr></tbody></table>
                    <div id=\"FFXIPackageHelper_tabs_fishing_queryresult\">". $this->postQueryResults() ."</div>
                </div>";
        return $html;
    }

    private function showShareButton(){
        return "";
    }

    private function postQueryResults(){
        return "";
    }

    // private function selectLvlDropDown(){
    //     return "";
    // }

    // private function selectionOptions(){
    //     return "";
    // }
}

?>
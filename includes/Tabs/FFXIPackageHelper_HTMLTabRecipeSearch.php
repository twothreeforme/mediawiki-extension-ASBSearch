<?php


class FFXIPackageHelper_HTMLTabRecipeSearch {
    public function __construct() {
      }

    public function searchForm(){
        $html = "<div id=\"FFXIPackageHelper_tabs_recipes_searchForm\">" .
                    "<table><tbody><tr><td>
                        <table><tbody>
                        <tr>
                            <td>Recipe Name <input class=\"FFXIPackageHelper_dynamiccontent_textinput\" name=\"recipeNameSearch\" size=\"25\"></td>
                        </tr>
                        <tr>
                            <td>Ingredient <input class=\"FFXIPackageHelper_dynamiccontent_textinput\" name=\"ingredientSearch\" size=\"25\"></td>
                        </tr>
                        <tr>
                            <td>Crystal " . $this->crystalDropDown() . "<br><button id=\"FFXIPackageHelper_dynamiccontent_searchRecipeSubmit\" class=\"FFXIPackageHelper_dynamiccontent_customButton\">Search</button>
                        </tr>
                        </tbody></table>
                    </td><td>
                            <td>Craft " . $this->craftDropDown() .
                            "<br>Skill Rank " . $this->skillRankDropDown() .
                            "<br>Min. Craft Level " . $this->craftLvlDropDown("FFXIPackageHelper_dynamiccontent_selectMinCraftLvl") .
                            "<br>Max. Craft Level " . $this->craftLvlDropDown("FFXIPackageHelper_dynamiccontent_selectMaxCraftLvl") . "
                        </td>
                    </tr></tbody>
                    </table>
                    <div id=\"FFXIPackageHelper_tabs_recipeSearch_queryresult\"></div>
                </div>";
        return $html;
    }

    private function craftDropDown(){
        // <select id=\"FFXIPackageHelper_dynamiccontent_selectLvlMIN\"></select>
        $html = "<select id=\"FFXIPackageHelper_dynamiccontent_selectCraft\" >";
        $html .= "<option value=\"none\">None Specified</option>";
        $html .= "<option value=\"Wood\">Woodworking</option>";
        $html .= "<option value=\"Smith\">Smithing</option>";
        $html .= "<option value=\"Gold\">Goldsmithing</option>";
        $html .= "<option value=\"Cloth\">Clothcraft</option>";
        $html .= "<option value=\"Leather\">Leatherworking</option>";
        $html .= "<option value=\"Bone\">Bonecrafting</option>";
        $html .= "<option value=\"Alchemy\">Alchemy</option>";
        $html .= "<option value=\"Cook\">Cooking</option>";
        $html .= "</select>";
        return $html;
    }

    private function crystalDropDown(){
        // <select id=\"FFXIPackageHelper_dynamiccontent_selectLvlMIN\"></select>
        $html = "<select id=\"FFXIPackageHelper_dynamiccontent_selectCrystal\" >";
        $html .= "<option value=\"0\">Any</option>";
        $html .= "<option value=\"4096\">Fire</option>";
        $html .= "<option value=\"4097\">Ice</option>";
        $html .= "<option value=\"4098\">Wind</option>";
        $html .= "<option value=\"4099\">Earth</option>";
        $html .= "<option value=\"4100\">Lightning</option>";
        $html .= "<option value=\"4101\">Water</option>";
        $html .= "<option value=\"4102\">Light</option>";
        $html .= "<option value=\"4103\">Dark</option>";
        $html .= "</select>";
        return $html;
    }

    private function craftLvlDropDown($classname){
        // <select id=\"FFXIPackageHelper_dynamiccontent_selectLvlMIN\"></select> FFXIPackageHelper_dynamiccontent_selectMinCraftLvl
        $html = "<select id=\"". $classname ."\" disabled=\"disabled\">";

        for ($i = 0; $i <= 120; $i++) {
            if ( $i == 0 ) $html .= "<option value=\"" . $i . "\">None</option>";
            else $html .= "<option value=\"" . $i . "\">" . $i . "</option>";
        }
        $html .= "</select>";
        return $html;
    }

    private function skillRankDropDown(){
        // <select id=\"FFXIPackageHelper_dynamiccontent_selectLvlMIN\"></select>
        $html = "<select id=\"FFXIPackageHelper_dynamiccontent_selectSkillRank\" disabled=\"disabled\" defaultValue=\"0\">";
        $html .= "<option value=\"0\">Any</option>";
        $html .= "<option value=\"1\">Amatuer</option>";
        $html .= "<option value=\"11\">Recruit</option>";
        $html .= "<option value=\"21\">Initiate</option>";
        $html .= "<option value=\"31\">Novice</option>";
        $html .= "<option value=\"41\">Apprentice</option>";
        $html .= "<option value=\"51\">Journeyman</option>";
        $html .= "<option value=\"61\">Craftsman</option>";
        $html .= "<option value=\"71\">Artisan</option>";
        $html .= "<option value=\"81\">Adept</option>";
        $html .= "<option value=\"91\">Veteran</option>";
        $html .= "</select>";
        return $html;
    }

}

?>
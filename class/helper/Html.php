<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 08/11/14
 * Time: 13:06
 */

namespace Helper;

class Html {
    private $_header;
    private $_body;
    private $_div;
    private $_form;

    public function __CONSTRUCT(){}

    public function newDiv(array $props, $content){
        $properties = null;

        foreach($props as $propKey => $propVal){
            $properties .= " " . $propKey . "='" . $propVal . "'";
        }

        return "<div" . $properties.">" . $content . "</div>";
    }

    public function newTitle($title){
        return "<title>" . $title . "</title>";
    }

    public function newLinkTag(array $props){
        $properties = null;

        foreach($props as $propKey => $propVal){
            $properties .= " " . $propKey . "='" . $propVal . "'";
        }

        return "<link" . $properties.">";
    }

    /**
     *
     * properties : type|src|charset|defer|async(html5)|
     * @param array $props
     * @return string
     */
    public function newScriptTag(array $props){
        $properties = null;

        foreach($props as $propKey => $propVal){
            $properties .= " " . $propKey . "='" . $propVal . "'";
        }

        return "<script" . $properties."></script>";
    }

    public function newMeta(array $props){
        $properties = null;

        foreach($props as $propKey => $propVal){
            $properties .= " " . $propKey . "='" . $propVal . "'";
        }

        return "<meta" . $properties.">";
    }

    public function newA(array $props, $content){
        $properties = null;

        foreach($props as $propKey => $propVal){
            $properties .= " " . $propKey . "='" . $propVal . "'";
        }

        return "<a" . $properties.">" . $content ."</a>";
    }

    public function newUl(array $props, $list){
        $properties = null;

        if(!empty($props)){
            foreach($props as $propKey => $propVal){
                $properties .= " " . $propKey . "='" . $propVal . "'";
            }
        }

        return "<ul" . $properties.">" . $list ."</ul>";
    }

    public function newLi(array $props, array $list, $positionProp = null){
        $properties = null;
        $li = null;

        if(!empty($props)){
            foreach($props as $propKey => $propVal){
                $properties .= " " . $propKey . "='" . $propVal . "'";
            }
        }

        foreach($list as $liKey => $liVal){
            if(!is_null($positionProp)){
                $i = 1;
                if($positionProp == $i){
                    $li .= "<li" . $properties.">" . $liVal ."</li>";
                }
                else{
                    $li .= "<li>" . $liVal ."</li>";
                }
                $i++;
            }
            else{
                $li .= "<li>" . $liVal ."</li>";
            }
        }

        return $li;
    }

    public function newButton(array $props, $content){
        $properties = null;
        $btn = null;

        foreach($props as $propKey => $propVal){
            $properties .= " " . $propKey . "='" . $propVal . "'";
        }

        $btn = "<button" . $properties . ">" . $content ."</button>";

        return $btn;
    }

    public function newP(array $props, $content){
        $properties = null;
        $p = null;

        foreach($props as $propKey => $propVal){
            $properties .= " " . $propKey . "='" . $propVal . "'";
        }

        $p = "<p" . $properties . ">" . $content ."</p>";

        return $p;
    }

    public function newH(array $props, $hForce, $content){
        $properties = null;

        foreach($props as $propKey => $propVal){
            $properties .= " " . $propKey . "='" . $propVal . "'";
        }

        $h = "<h" . $hForce . $properties . ">" . $content ."</h" . $hForce .">";

        return $h;
    }

    public function newForm(array $props, $content){
        $properties = null;

        foreach($props as $propKey => $propVal){
            $properties .= " " . $propKey . "='" . $propVal . "'";
        }

        $form = "<form" . $properties . ">" . $content ."</form>";

        return $form;
    }

    public function newFormInput(array $props, $fieldset = null){
        $properties = null;

        foreach($props as $propKey => $propVal){
            $properties .= " " . $propKey . "='" . $propVal . "'";
        }

        $input = "<input" . $properties . ">";

        if(!is_null($fieldset)){
            $input = "<fieldset>" . $fieldset . $input . "</fieldset>";
            return $input;
        }
        else{
            return $input;
        }
    }

    public function newFormSelect(array $props, $content){
        $properties = null;

        foreach($props as $propKey => $propVal){
            $properties .= " " . $propKey . "='" . $propVal . "'";
        }

        $select = "<select" . $properties . ">" . $content ."</select>";

        return $select;
    }

    public function newFormOption(array $props, array $options){
        $properties = null;
        $option = null;

        foreach($props as $propKey => $propVal){
            if($propKey != "value"){
                $properties .= " " . $propKey . "='" . $propVal . "'";
            }
        }

        foreach($options as $key => $val){
            $option .= "<option" . $properties . " value='" . $key . "'>" . $val ."</option>";
        }

        return $option;
    }

    public function newTextarea(array $props, $content, $fieldset = null){
        $properties = null;

        foreach($props as $propKey => $propVal){
            $properties .= " " . $propKey . "='" . $propVal . "'";
        }

        $textarea = "<textarea" . $properties . ">" . $content ."</textarea>";

        if(!is_null($fieldset)){
            $textarea = "<fieldset>" . $textarea . "</fieldset>";
            return $textarea;
        }
        else{
            return $textarea;
        }
    }

    public function newFieldset(array $props, $content){
        $properties = null;

        foreach($props as $propKey => $propVal){
            $properties .= " " . $propKey . "='" . $propVal . "'";
        }

        $fieldset = "<fieldset" . $properties . ">" . $content ."</fieldset>";

        return $fieldset;
    }
} 
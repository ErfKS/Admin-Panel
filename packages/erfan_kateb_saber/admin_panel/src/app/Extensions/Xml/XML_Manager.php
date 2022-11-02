<?php

namespace erfan_kateb_saber\admin_panel\app\Extensions\Xml;

use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;

class XML_Manager
{
    static function arrayToXml($array, $path=null,$rootElement = null, $xml = null) {
        $_xml = $xml;

        // If there is no Root Element then insert root
        if ($_xml === null) {
            $_xml = new SimpleXMLElement($rootElement !== null ? $rootElement : '<root/>');
        }

        // Visit all key value pair
        foreach ($array as $k => $v) {

            // If there is nested array then
            if (is_array($v)) {

                // Call function for nested array
                XML_Manager::arrayToXml($v,$path, XML_Manager::xmlFilter($k), $_xml->addChild(XML_Manager::xmlFilter($k)));
            }

            else {

                // Simply add child element.
                $_xml->addChild(XML_Manager::xmlFilter($k), $v);
            }
        }
        Storage::disk('private')->put($path, $_xml->asXML());
        return $_xml->asXML();
    }

    static public function xmlToArray($path , $specialReplace,$ignore_negativeFilter = false){
        $xmlstring = Storage::disk('private')->get($path);
        $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        if(!$ignore_negativeFilter) {
            $json = XML_Manager::negativeXmlFilter($json, $specialReplace);
        }
        $array = json_decode($json,TRUE);
        return $array;
    }
    static public function xmlFilter($value){
        $value = str_replace("/" ,"slash" , $value);
        $value = str_replace('\\' ,"Back_slash" , $value);
        $value = str_replace("{" ,"Acolad_Baz" , $value);
        $value = str_replace("}" ,"Acolad_Baste" , $value);
        $value = str_replace("?" ,"Question_mark" , $value);
        $value = str_replace("@" ,"Atsign" , $value);
        return $value;
    }
    static private function negativeXmlFilter($value , $specialReplace){
        $value = str_replace("slash","/" , $value);
        $value = str_replace("Back_slash",'\\' , $value);
        $value = str_replace("Acolad_Baz","{" , $value);
        $value = str_replace("Acolad_Baste","}" , $value);
        $value = str_replace("Question_mark","?" , $value);
        $value = str_replace("Atsign" ,"@" , $value);
        foreach ($specialReplace as $item){
            $value = str_replace($item,"" , $value);
        }
        return $value;
    }
}

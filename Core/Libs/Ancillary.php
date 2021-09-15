<?php
trait Ancillary {
    public static function toArray($obj) 
    {
        return json_decode(json_encode($obj), true);
    }
    
    public static function toObject(array $arr) 
    {
        return json_decode(json_encode($arr));
    }
    
    
    
    
    
    public static function toCamelCase($string) 
    {
        return str_replace(" ", "", ucwords(str_replace("_", " ", str_replace("-", " ", $string))));         
    }
    
    public static function toUnderscoreCase($string) 
    {
        return strtolower(preg_replace("/([a-z])([A-Z])/", "$1_$2", $string));
    }
    
    public static function pluralize($singular, $quantity=2) {
        if ($quantity === 1 || !strlen($singular)) {
            return $singular;
        }

        $lastLetter = strtolower($singular[strlen($singular)-1]);
        switch($lastLetter) {
            case "y":
                return substr($singular, 0, -1) . "ies";
            case "s":
                return $singular . "es";
            default:
                return $singular . "s";
        }
    }
    
    
    
    
    public static function mergeObjects($firstObj, $secondObj)
    {
        foreach ($secondObj as $name=>$value) {
            if (!isset($firstObj->{$name})) {
                $firstObj->{$name} = $value;
            }
        }
        
        return $firstObj;
    }
    
    public static function objectColumn(array $objectList, $column, $indexColumn=null)
    {
        $result = [];
        
        foreach ($objectList as $object) {            
            if (is_null($indexColumn)) {
                $result[] = $object->{$column};
            } else {
                $result[$object->{$indexColumn}] = $object->{$column};
            }    
        }
        
        return $result;
    }
    
    
    
    
    public static function getVar($name, $default=null, $source="GET") 
    {
        $source = strtoupper($source);
        
        if (!in_array($source, ["GET", "POST", "REQUEST", "COOKIE", "SESSION"])) {
            throw new Exception ("Unknown varaiable source {$source}");
        }
        
        switch ($source) {
            case "GET":
                $source_array = $_GET;
                break;
            
                case "POST":
                $source_array = $_POST;
                break;
                
                case "REQUEST":
                $source_array = $_REQUEST;
                break;
                
                case "COOKIE":
                $source_array = $_COOKIE;
                break;
                
                case "SESSION":
                $source_array = $_SESSION;
                break;
        }
        
        return (isset($source_array[$name]) ? $source_array[$name] : $default);
    }
    
    public static function getPostVar($name, $default=null) 
    {
        return self::getVar($name, $default, "POST");
    }
    
    public static function createLink($path, array $params = []) {
        parse_str($_SERVER['QUERY_STRING'], $query);
        $query = array_merge($query, $params);
        
        // Unset empty params
        foreach ($query as $i=>$param) {
            if (empty($param)) {
                unset($query[$i]);
            }
        }
        
        $link = (empty($query) ? $path : $path. "?" . http_build_query($query));

        return $link;      
    }
    
    
    
    
    public static function getIndexOfObjectBy($key, $value, array $objects)
    {
        $index = null;

        foreach ($objects as $i=>$obj) {
            if ($obj->{$key} == $value)  {
                $index = $i;
                break;
            }                
        }
        
        return $index;
    }
    
    public static function getObjectBy($key, $value, array $objects)
    {
        $index = self::getIndexOfObjectBy($key, $value, $objects);
        
        return (!is_null($index)
            ? $objects[$index]
            : null
        );
    }
    
    public static function index(array $array, $key, $keyObject=null, $valueObject=null, array $includeValueObject=null) {
        $indexed = [];
                
        foreach ($array as $element) {
            if (is_null($keyObject)) { 
                $indexed[$element->{$key}] = $element;
            } else {
                if (is_null($valueObject)) {
                    $valueObject = $keyObject;
                }
                
                if (!is_null($includeValueObject)) {
                    $includeValueObjectName = key($includeValueObject);
                    $includeValueObjectValue = $includeValueObject[$includeValueObjectName];
                    
                    $element->{$valueObject}->{$includeValueObjectValue} = $element->{$includeValueObjectName};
                }
                
                $indexed[$element->{$keyObject}->{$key}] = $element->{$valueObject};           
            }
        }
        
        return $indexed;
    }
    

    
    
    
    public static function compare($value, $sign, $threshold)
    {
        $result = false;
        
        switch ($sign) {
            case ">":
                $result = ($value > $threshold);
                break;
            
            case ">=":
                $result = ($value >= $threshold);
                break; 
            
            case "<":
                $result = ($value < $threshold);
                break; 
                
            case "<=":
                $result = ($value <= $threshold);
                break; 
            
            case "=":
            case "==":            
                $result = ($value == $threshold);
                break; 
            
            case "!=":
                $result = ($value != $threshold);
                break; 
        }
        
        return $result;        
    }
    
    
    
    
    public static function splitByColumns(array $list, $columnsQuantity=4)
    {
        $splitted = [];
        $numericalList = array_values($list);
        $subtrahend = 0;
        
        foreach ($numericalList as $i=>$element) {
            if ($i !== 0 && $i%$columnsQuantity === 0) {
                $subtrahend += $columnsQuantity;
            }
            
            $column = $i-$subtrahend;
            $splitted[$column][] = $element;
        }
        
        return $splitted;
    }
    
    
    
    
    public static function verify($property, $properties, $default, callable $verification_function, $error_info="") 
    {
        $property = (string)$property;
        $properties = (object)$properties;
        
        if (!isset($properties->{$property})) {
            return $default;
        }
        
        
        
        $verified = $verification_function($properties->{$property}, $error_info);
        
        if (empty($verified)) {
            return $default;
        }
        
        
        
        return $verified;        
    }   
    
    
    
    
    
    public static function excerpt($content, $length=200, $textMore="...") {        
        if (mb_strlen($content) <= $length) {
            return $content;
        }
                
        $excerpt = mb_substr($content, 0, $length, "UTF-8");
        $lastSpace = mb_strripos($excerpt, " ", 0, "UTF-8");
        $excerpt = mb_substr($excerpt, 0, $lastSpace, "UTF-8");
        
        return $excerpt . $textMore;
    }
    
    
    
    
    
    public static function setData($recipient, $data) 
    {
        if (!is_array($recipient) && !is_object($recipient)) {
            throw new \Exception("Recipient should have type \"object\" or \"array\"");
        }
        
        foreach ($data as $key=>$value) {
            if (is_array($recipient)) {
                if (!isset($recipient[$key])) {
                    $recipient[$key] = $value;
                }
                
                $recipient[$key] = !is_array($recipient[$key]) && !is_object($recipient[$key]) 
                    ? $value 
                    : self::setData($recipient[$key], $value);
            }
            else {
                if (!isset($recipient->{$key})) {
                    $recipient->{$key} = $value;
                }
                
                $recipient->{$key} = !is_array($recipient->{$key}) && !is_object($recipient->{$key})
                    ? $value 
                    : self::setData($recipient->{$key}, $value);
            }
        }
        
        return $recipient;
    }
    
    public static function getData($source, $name, $default=null) {
        if (!is_array($source) && !is_object($source)) {
            throw new \Exception("Source should have type \"object\" or \"array\"");
        }
        
        if (is_object($source)) { 
            return (isset($source->{$name}) ? $source->{$name} : $default);
        } else {
            return (isset($source[$name]) ? $source[$name] : $default);
        }
    }
    
    public static function arrayMergeMax(array $arrays, $unique_key, $max_key) 
    {
        $result = [];
        
        foreach ($arrays as $array) {
            foreach ($array as $element) {
                $key = $element[$unique_key];
                if (!isset($result[$key]) || $result[$key][$max_key] < $element[$max_key]) {
                    $result[$key] = $element;
                }
            }
        }
        
        return $result;
    }
       
    public static function calcMedian(array $array) 
    {      
        rsort($array);        
        $middle = (count($array) / 2);
        $median = ($middle%2 != 0) ? $array[$middle-1] : (($array[$middle-1]) + $array[$middle]) / 2;
        
        return $median;
    }
    
    public static function formatAsTime($duration) 
    {
        $days = intval($duration/86400);
        $hours = str_pad(floor($duration/3600), 2, "0", STR_PAD_LEFT);
        $minutes = str_pad(floor(($duration%3600)/60), 2, "0", STR_PAD_LEFT);
        $seconds = str_pad(($duration%3600)%60, 2, "0", STR_PAD_LEFT);
        
		if ($days > 0) {
			$formatted = "$d д. $h ч. $m м. $s с.";
		} elseif ($hours > 0) {
			$formatted = "$h ч. $m м. $s с.";
		} elseif ($minutes > 0) {
			$formatted = "$m м. $s с.";
		} else {
			$formatted = "$s с.";
		}

        return $formatted;
    }
}

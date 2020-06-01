<?php
namespace Concise\Libs;

abstract class Form
{
    public static function getRequiredFields()
    {
        return [];
    }
    
    public static function getRules()
    {
         return [];
    }
    
    
    
    
    
    public static function checkRequiredFields($data)
    {
        $missed = [];
        $requiredFields = static::getRequiredFields();
        
        foreach ($requiredFields as $requiredField) {
            if (!array_key_exists($requiredField, $data) || empty($data[$requiredField])) {
                $missed[] = $requiredField;
            }
        }
        
        return $missed;
    }
    
    public static function checkRules($data)
    {
        $rules = static::getRules($data); 
        $failed = [];
        
        foreach ($data as $field=>$value) {
            // Skip fields without rules, and empty optional fields
            if (!array_key_exists($field, $rules) || empty($value)) {
                continue;
            }
            
            $rule = $rules[$field];
            $error = $rule($value);
            
            if (!empty($error)) {
                $failed[$field] = $error;
            }
        }
        
        return $failed;
    }
    
    public static function check($data)
    {
        return [
            static::checkRequiredFields($data),
            static::checkRules($data)
        ];
    }   
    
    public static function validate($data)
    {
        list($missed, $failed) = static::check($data);
        
        if (!empty($missed) || !empty($failed)) {
            throw new \InvalidArgumentException("One of pieces of data hasn't pass the check. Errors: " . json_encode(compact("missed", "failed")));
        }
        
        return $data;
    }
    
    
    
    
    
    /* Default validation functions */
    protected static function getValidationFunctionFor($fieldType, $fieldName)
    {
        $functions= [
            'string' => function($value) use ($fieldName) {                                
                if (mb_strlen($value) < 2) {
                    return "{$fieldName} can't be less than 2 symbols";
                }
                
                if (mb_strlen($value) > 128) {
                    return "{$fieldName} can't be longer then 128 symbols";
                }
            },
            
            
            
            'text' => function($value) use ($fieldName) {                                
                if (mb_strlen($value) < 2) {
                    return "{$fieldName} can't be less than 2 symbols";
                }
                
                if (mb_strlen($value) > 512) {
                    return "{$fieldName} can't be longer then 512 symbols";
                }
            },
            
            
            
            'email' => function($value) use ($fieldName) { 
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return "{$fieldName} is not a valid email";
                }
            },
            
            
            
            'phone' => function($value) use ($fieldName) { 
                if (!preg_match("/^\+1 \([0-9]{3}\) [0-9]{3}-[0-9]{4}$/", $value)) {
                    return "{$fieldName} is not a valid phone number"; 
                }
            },
            
            
            
            'latitude' => function($value) use ($fieldName) { 
                if (!preg_match("/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$/", $value)) {
                    return "{$fieldName} is not a valid latitude"; 
                }
            },
            
            
            
            'longitude' => function($value) use ($fieldName) { 
                if (!preg_match("/^[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/", $value)) {
                    return "{$fieldName} is not a valid longitude"; 
                }
            },
            
            
            
            'postalCode' => function($value) use ($fieldName) { 
                if (!preg_match("/^[0-9]{5}|[0-9a-z ]{6,7}$/i", $value)) {
                    return "Please, enter a valid {$fieldName}"; 
                }
            },
            
            
            
            'hexColor' => function ($value) use ($fieldName) {
                if (!preg_match("/^#([0-9a-f]{6}|[0-9a-f]{3})$/i", $value)) {
                    return "Please, enter a valid {$fieldName}"; 
                }
            },
            
            
            
            'price' => function($value) use ($fieldName) {
                if (!preg_match("/^[0-9]{1,8}(\.[0-9]{1,2})?$/", $value)) {
                    return "Please, enter a valid {$fieldName}"; 
                }
            },
            
            
            
            'volume' => function($value) use ($fieldName) {
                if (!preg_match("/^[0-9]{1,8}(\.[0-9]{1,5})?$/", $value)) {
                    return "Please, enter a valid {$fieldName}"; 
                }
            },
            
            
            
            'upc' => function($value) use ($fieldName) {
                if (!preg_match("/^[0-9]{8}$|^[0-9]{13}$|^[0-9]{12}$/", $value)) {
                    return "Please, enter a valid {$fieldName}"; 
                }
            },            
            
            
            
            'checkbox' => function($value) use ($fieldName) {
                if (!in_array($value, [0,1])) {
                    return "{$fieldName} is incorrect"; 
                }
            },
            
            
            
            'uniqid' => function($value) use ($fieldName) {
                if (!preg_match("/^[0-9a-f]{13}$|^[0-9a-f]{14}\.[0-9a-f]{8}$/i", $value)) {
                    return "{$fieldName} is incorrect";
                }
            }, 
        ];
        
        if (!array_key_exists($fieldType, $functions)) {
            throw new \BadMethodCallException("There is no default validation function for field type {$fieldType}");
        }
        
        return $functions[$fieldType];
    }
}

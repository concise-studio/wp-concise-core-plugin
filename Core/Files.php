<?php 
namespace Concise;

class Files
{  
    public  $prefix;    
    private $filesDir;
    private $filesUrl;    
    private $allowedTypes = [
        '.doc' => "application/msword",
        '.docx' => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        '.xls' => "application/vnd.ms-excel",
        '.xlsx' => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        '.jpg' => "image/jpeg", 
        '.png' => "image/png", 
        '.gif' => "image/gif", 
        '.pdf' => "application/pdf"
    ];
    
    
    
    
    
    public function __construct($folderName, $prefix="", array $allowedTypes=null) {
        $uploadDir = wp_upload_dir();
        $filesDir = $uploadDir['basedir'] . "/" . $folderName;
        
        if (!file_exists($filesDir)) {
            wp_mkdir_p($filesDir);
        }
        
        $this->filesDir = $filesDir;
        $this->filesUrl= $uploadDir['baseurl'] . "/" . $folderName;
        $this->prefix = $prefix;
        
        if (!is_null($allowedTypes)) {
            $this->allowedTypes = $allowedTypes;
        }
    }
    
    
    
    
    
    public function validate($fieldName, $maxSize=50)
    {
        // Check fetched data
        if (empty($_FILES[$fieldName])) { 
            return [
                'result' => 0,
                'message' => __("File not recieved")
            ];
        };
        
        $file = $_FILES[$fieldName];
           
        
        
        // Check system errors
        if ($file['error']) {
            return [
                'result' => 0,
                'message' => __("System error of loading file")
            ]; 
        }     
        
        
        
        // Check size of file
        if ($file['size'] > $maxSize*1024*1024) {
            return [
                'result' => 0,
                'message' => __("File for can't be more than {$maxSize} mb")
            ];
        }
        
        
        
        // Check type of file 
        if (!in_array($file['type'], $this->allowedTypes)) {
            return [
                'result' => 0,
                'message' => __("Wrong type of file. Allowed types: " . implode(", ", array_keys($this->allowedTypes)))
            ];
        }
        
        
        
        // Return positive result
        return [
            'result' => 1
        ];
    }
    
    
    
    
    
    public function upload($fieldName)
    {
        // Validate
        $validation = $this->validate($fieldName);
       
        if (!$validation['result']) {
            return $validation;
        }
        
        $file = $_FILES[$fieldName];
        
        

        // Save file
        $extension = array_search($file['type'], $this->allowedTypes);
        $fileName = $this->prefix . hash("crc32", uniqid()) . $extension;
        $filePath = "{$this->filesDir}/{$fileName}";
        $uploadResult = move_uploaded_file($file['tmp_name'], $filePath);
        
        
        
        // Check upload
        if (!$uploadResult) {
            die(json_encode([
                'result' => 0,
                'message' => __("Error of uploading file")
            ]));
        }
        
        
        
        // Return file data
        return [
            'result' => 1,
            'originalName' => $file['name'],
            'name' => $fileName,
            'link' => $this->filesUrl . "/" . $fileName
        ];
    }
    
    
    
    public function getAllowedTypes()
    {
        return $this->allowedTypes;
    }
    
    public function getFilesDir()
    {
        return $this->filesDir;
    }
}

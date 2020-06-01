<?php
namespace Concise;

trait Messages 
{   
    private $positive;
    private $negative;
    
    
    
    
    
    public function addMessage($type, $text) {
        if (!in_array($type, ["positive", "negative"])) {
            throw new \BadCallException("Type must be 'postitive' or 'negative'");
        }
        
        $this->{$type}[] = $text;
        
        return $this;
    }
    
    public function addPositiveMessage($text) {        
        return $this->addMessage("positive", $text);
    }
    
    public function addNegativeMessage($text) {        
        return $this->addMessage("negative", $text);
    }
    
    
    
    
    
    public function setMessage($type, $text) {
        if (!in_array($type, ["positive", "negative"])) {
            throw new \BadCallException("Type must be 'postitive' or 'negative'");
        }
        
        $this->{$type} = [$text];
        
        return $this;
    }
    
    public function setPositiveMessage($text) {        
        return $this->setMessage("positive", $text);
    }
    
    public function setNegativeMessage($text) {        
        return $this->setMessage("negative", $text);
    }
    
    
    
    
    
    
    public function getMessages()
    {
        return [
            'positive' => $this->positive,
            'negative' => $this->negative
        ];
    }
}

<?php
namespace Concise;

class Component 
{   
    protected $Template;
    
    
    
    
    
    public function __construct(Template $Template) {
        $this->Template = $Template;
    }
}

<?php

/*
 * Originally coded by endeavor@rainbowsandpwnies.com 2012 and released into
 * the public domain.
 * The latest version of this file can be found at github.com/endeav0r/php-lib
 */

define("TEMPLATE_BASE_PATH",   "templates/");
define("TEMPLATE_EXTENSION",   ".tpl");
define("TEMPLATE_OPEN_BRACE",  "{");
define("TEMPLATE_CLOSE_BRACE", "}");

class Template
{
    private $templates = array();
    
    private $vars = array();
    
    public function parse ($template_name)
    {
        if (! isset($this->templates[$template_name])) {
            $filename = TEMPLATE_BASE_PATH . $template_name . TEMPLATE_EXTENSION;
            $fh = fopen($filename, "r");
            $this->templates[$template_name] = fread($fh, filesize($filename));
            fclose($fh);
        }
        
        $template = $this->templates[$template_name];
        
        $num_matches = preg_match_all('/\{tpl\.(.*?)\}/', $template, $matches);
        
        for ($i = 0; $i < $num_matches; $i++)
            $template = str_replace($matches[0][$i], $this->parse($matches[1][$i]), $template);
        
        foreach ($this->vars as $k => $v) {
            $needle = TEMPLATE_OPEN_BRACE . $k . TEMPLATE_CLOSE_BRACE;
            $template = str_replace($needle, $v, $template);
        }
        
        return $template;
    }
    
    public function setvar ($key, $value)
    {
        $this->vars[$key] = $value;
    }
}

?>

<?php

class Mail_Template
{
    /**
     * Assigns variables to the view script via differing strategies.
     *
     * Mail_Template::assign('name', $value) assigns a variable called 'name'
     * with the corresponding $value.
     *
     * Mail_Template::assign($array) assigns the array keys as variable
     * names (with the corresponding array values).
     *
     * @see    __set()
     * @param  string|array The assignment strategy to use.
     * @param  mixed (Optional) If assigning a named variable, use this
     * as the value.
     * @return Mail_Template Fluent interface
     * @throws Zeed_Exception if $spec is neither a string nor an array,
     * or if an attempt to set a private or protected member is detected
     */
    public function assign($spec, $value = null)
    {
        // which strategy to use?
        if (is_string($spec)) {
            // assign by name and value
            if ('_' == substr($spec, 0, 1)) {
                throw new Zeed_Exception('Setting private or protected class members is not allowed');
            }
            $this->$spec = $value;
        } elseif (is_array($spec)) {
            // assign from associative array
            $error = false;
            foreach ($spec as $key => $val) {
                if ('_' == substr($key, 0, 1)) {
                    $error = true;
                    break;
                }
                $this->$key = $val;
            }
            if ($error) {
                throw new Zeed_Exception('Setting private or protected class members is not allowed');
            }
        } else {
            throw new Zeed_Exception('assign() expects a string or array, received ' . gettype($spec));
        }
        
        return $this;
    }
    
    /**
     * Processes a view script and returns the output.
     *
     * @param string $name The script name to process.
     * @return string The template output.
     */
    public function render($name)
    {
    }
    
    /**
     * parse mail template
     * 
     * @param string $content mail template
     * @return string the template ouput
     */
    public function parse($content)
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            $content = str_replace("#\${$key}#", $value, $content);
        }
        return $content;
    }
}
<?php
/**
 * Validates a form by using Validation Rules set by the user
 *
 * The validation rules you can set.
 *
 * Rule             value               Description    
 * -----------   ---------           -----------
 * name             string                 the name of the field
 * required         boolean             Set if the field is required
 * validEmail    boolean             Validates an email address
 * validURL      boolean             Validates an URL
 * match         Fieldname to match  The fields value needs to match the value of the field given to match
 * numeric       boolean             Checks if the value contains only numeric characters
 * alpha         boolean             Checks if the value contains only alphabetical characters
 * aplhaNumeric  boolean             Checks if the value contains only alpha-numeric characters
 * minLength     int                  Checks if the value has less characters than the minLength
 * maxLength     int                   Checks if the value has more characters than the maxLength
 * exactLength   int                 Checks if the value is the exact length of the exactLength
 * lessThan      int                    Checks if the value is less than lessThan
 * greaterThan:  int                 Checks if the value is greater than greaterThan                
 *
 * Note: No validation rules have been created for File upload validation
 */
Class Validation
{    
    /**
     * @var  array  Holds the posted data
     */
    private $_post;
    
    /**
     * @var  array  A multidimensional array containg the validation rules for all the fields
     */
    private $_validationRules;
    
    /**
     * @var  array  Holds all the errors for the fields
     */
    private $_validationErrors;
        
    /**
     * Validates the form
     *
     * @param  array  $post  The posted data
     */
    public function validateFields($post)
    {
        //Drop the last element of the array $post, this is the submit button (if you gave the submit button a name)
        array_pop($post);
        
        //Assign the posted data to a private property
        $this->_post = $post;
        
        //Loop through all the posted data
        foreach($this->_post as $key => $value)
        {
            //Trim the whitespace
            $value = trim($value);
            
            //Check if a name of a posted field exists as a key in the validationRules array, i.e if Rules have been set for this field
            if(array_key_exists($key, $this->_validationRules))
            {                             
                //Loop through all the rules
                foreach($this->_validationRules[$key] as $rule => $ruleValue)
                {
                    //Check if a method exists with the same name as $rule, if so call it
                    if(method_exists($this, $rule))
                    {
                        $this->$rule($key, $value, $ruleValue);
                    }
                }            
            }
        }
        
        //If we don't have any errors
        if(empty($this->_validationErrors))
        {
            return TRUE;
        }
    }
    
    /**
     * --------------------------------------------------------------------------------------
     * Check if the required field is empty
     *
     * @param  string  $key        The name of the posted field
     * @param  string  $value      The value of the posted field
     * @param  string  $ruleValue  The value of the rule
     */
    private function required($key, $value, $ruleValue)
    {
        //Check if the field is empty
        if(empty($value) && $ruleValue == TRUE)
        {
            $this->_validationErrors[$key] = 'This field is required';
        }
    }
    
    /**
     * --------------------------------------------------------------------------------------
     * Validates an email address
     *
     * @param  string  $key        The name of the posted field
     * @param  string  $value      The value of the posted field
     * @param  string  $ruleValue  The value of the rule
     */
    private function validEmail($key, $value, $ruleValue)
    {
        //Validate the email address
        if(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $value) && $ruleValue == TRUE)
        {
            //Check if there was already an error set for this field and if the field is empty, we don't want to set this error if the field is empty
            if(!isset($this->_validationErrors[$key]) && !empty($value))
            {
                $this->_validationErrors[$key] = 'Please fill in a valid email addresss';
            }
        }
    }
    
    /**
     * --------------------------------------------------------------------------------------
     * Validates an URL
     *
     * @param  string  $key        The name of the posted field
     * @param  string  $value      The value of the posted field
     * @param  string  $ruleValue  The value of the rule
     */
    private function validURL($key, $value, $ruleValue)
    {
        //Validate the URL
        if(!preg_match('/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/', $value) && $ruleValue == TRUE)
        {
            //Check if there was already an error set for this field and if the field is empty, we don't want to set this error if the field is empty
            if(!isset($this->_validationErrors[$key]) && !empty($value))
            {
                $this->_validationErrors[$key] = 'This is not a valid URL';
            }
        }
    }
    
    /**
     * --------------------------------------------------------------------------------------
     * Checks if two fields match
     *
     * @param  string  $key        The name of the posted field
     * @param  string  $value      The value of the posted field
     * @param  string  $ruleValue  The value of the rule
     */
    private function match($key, $value, $ruleValue)
    {
        //Check if the values match
        if($value != $this->_post[$ruleValue])
        {
            $this->_validationErrors[$key] = 'does not match';
        }
    }
    
    /**
     * --------------------------------------------------------------------------------------
     * Checks the minimum length of the string
     *
     * @param  string  $key        The name of the posted field
     * @param  string  $value      The value of the posted field
     * @param  string  $ruleValue  The value of the rule
     */
    private function minLength($key, $value, $ruleValue)
    {
        //Validate the minlength of the field
        if(strlen($value) < $ruleValue)
        {
            //Check if the field is empty, we don't want to set this error if the field is empty
            if(!empty($value))
            {
                $this->_validationErrors[$key] = 'must be longer than '.$ruleValue.' characters';
            }
        }
    }
    
    /**
     * --------------------------------------------------------------------------------------
     * Checks the maximum length of the string
     *
     * @param  string  $key        The name of the posted field
     * @param  string  $value      The value of the posted field
     * @param  string  $ruleValue  The value of the rule
     */
    private function maxLength($key, $value, $ruleValue)
    {
        //Validate the maxLength of the field
        if(strlen($value) > $ruleValue)
        {
            //Check if the field is empty, we don't want to set this error if the field is empty
            if(!empty($value))
            {
                $this->_validationErrors[$key] = 'must be shorter than '.$ruleValue.' characters';
            }
        }
    }
    
    /**
     * --------------------------------------------------------------------------------------
     * Checks the exact length of the string
     *
     * @param  string  $key        The name of the posted field
     * @param  string  $value      The value of the posted field
     * @param  string  $ruleValue  The value of the rule
     */
    private function exactLength($key, $value, $ruleValue)
    {
        //Validate the exactlength of the field
        if(strlen($value) != $ruleValue)
        {
            //Check if the field is empty, we don't want to set this error if the field is empty
            if(!empty($value))
            {
                $this->_validationErrors[$key] = 'must be '.$ruleValue.' characters long';
            }
        }
    }
    
    /**
     * --------------------------------------------------------------------------------------
     * Checks if the value only contains alphabetical characters
     *
     * @param  string  $key        The name of the posted field
     * @param  string  $value      The value of the posted field
     * @param  string  $ruleValue  The value of the rule
     */
    private function alpha($key, $value, $ruleValue)
    {
        //Check if the value is alphabetical
        if(!preg_match('/^([a-z])+$/i', $value))
        {
            //Check if there was already an error set for this field and if the field is empty, we don't want to set this error if the field is empty
            if(!isset($this->_validationErrors[$key]) && !empty($value))
            {
                $this->_validationErrors[$key] = 'can only contain letters';
            }
        }
    }
    
    /**
     * --------------------------------------------------------------------------------------
     * Checks if the value only contains alphabetical and numerical characters
     *
     * @param  string  $key        The name of the posted field
     * @param  string  $value      The value of the posted field
     * @param  string  $ruleValue  The value of the rule
     */
    private function alphaNumeric($key, $value, $ruleValue)
    {
        //Check if the value is alphabetical and numerical
        if(!preg_match('/^([a-z0-9])+$/i', $value))
        {
            //Check if there was already an error set for this field and if the field is empty, we don't want to set this error if the field is empty
            if(!isset($this->_validationErrors[$key]) && !empty($value))
            {
                $this->_validationErrors[$key] = 'can only contain letters and numbers';
            }
        }
    }
    
    /**
     * --------------------------------------------------------------------------------------
     * Checks if the value only contains numerical characters
     *
     * @param  string  $key        The name of the posted field
     * @param  string  $value      The value of the posted field
     * @param  string  $ruleValue  The value of the rule
     */
    private function numeric($key, $value, $ruleValue)
    {
        //Check if the value is numeric
        if(!preg_match('/^[\-+]?[0-9]*\.?[0-9]+$/', $value))
        {
            //Check if there was already an error set for this field and if the field is empty, we don't want to set this error if the field is empty
            if(!isset($this->_validationErrors[$key]) && !empty($value))
            {
                $this->_validationErrors[$key] = 'can only contain numbers';
            }
        }
    }
    
    /**
     * --------------------------------------------------------------------------------------
     * Checks if the value is low enough
     *
     * @param  string  $key        The name of the posted field
     * @param  string  $value      The value of the posted field
     * @param  string  $ruleValue  The value of the rule
     */
    private function lessThan($key, $value, $ruleValue)
    {
        //Check if the value is numeric, because we can't validate this on a string
        if(preg_match('/^[\-+]?[0-9]*\.?[0-9]+$/', $value))
        {
            //Check if the value is low enough
            if($value >= $ruleValue)
            {
                //Check if there was already an error set for this field and if the field is empty, we don't want to set this error if the field is empty
                if(!isset($this->_validationErrors[$key]) && !empty($value))
                {
                    $this->_validationErrors[$key] = 'Must be less than '.$ruleValue;
                }
            }
        }
        else
        {
            //Check if there was already an error set for this field and if the field is empty, we don't want to set this error if the field is empty
            if(!isset($this->_validationErrors[$key]) && !empty($value))
            {
                $this->_validationErrors[$key] = 'can only contain numbers';
            }
        }
    }
    
    /**
     * --------------------------------------------------------------------------------------
     * Checks if the value is high enough
     *
     * @param  string  $key        The name of the posted field
     * @param  string  $value      The value of the posted field
     * @param  string  $ruleValue  The value of the rule
     */
    private function greaterThan($key, $value, $ruleValue)
    {
        
        //Check if the value is numeric, because we can't validate this on a string
        if(preg_match('/^[\-+]?[0-9]*\.?[0-9]+$/', $value))
        {
            //Check if the value is low enough
            if($value <= $ruleValue)
            {
                if(!isset($this->_validationErrors[$key]) && !empty($value))
                {            
                    $this->_validationErrors[$key] = 'Must be higher than '.$ruleValue;
                }
            }
        }
        else
        {
            //Check if there was already an error set for this field and if the field is empty, we don't want to set this error if the field is empty
            if(!isset($this->_validationErrors[$key]) && !empty($value))
            {
                $this->_validationErrors[$key] = 'can only contain numbers';
            }
        }
    }
}

?>
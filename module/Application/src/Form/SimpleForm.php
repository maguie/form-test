<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Application\Filter\PhoneFilter;
use Application\Validator\PhoneValidator;

/**
 * This form is used to collect First and Last names and validate  E-mail.
 * 
 */
class SimpleForm extends Form
{
    /**
     * Constructor.     
     */
    public function __construct()
    {
        // Define form name
        parent::__construct('simple-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
                
        $this->addElements();
        $this->addInputFilter(); 
    }
    
    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements() 
    {
	// Add "First Name" field
        $this->add([
            'type'  => 'text',
            'name' => 'firstName',
            'attributes' => [                
                'id' => 'firstName'
            ],
            'options' => [
                'label' => 'First name',
            ],
        ]);

	// Add "Last Name" field
        $this->add([
            'type'  => 'text',
            'name' => 'lastName',
            'attributes' => [                
                'id' => 'lastName'
            ],
            'options' => [
                'label' => 'Last name',
            ],
        ]);

        // Add "email" field
        $this->add([           
            'type'  => 'text',
            'name' => 'email',
            'attributes' => [
                'id' => 'email'
            ],
            'options' => [
                'label' => 'Your E-mail',
            ],
        ]);
        
        // Add the CSRF field
        $this->add([
            'type'  => 'csrf',
            'name' => 'csrf',
            'attributes' => [],
            'options' => [                
                'csrf_options' => [
                     'timeout' => 600
                ]
            ],
        ]);
        
        // Add the submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Submit',
                'id' => 'submitbutton',
            ],
        ]);        
    }
    
    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter() 
    {
        $inputFilter = $this->getInputFilter();        

	$inputFilter->add([
                'name'     => 'firstName',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                    ['name' => 'StripNewlines'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 128
                        ],
                    ],
                ],
            ]);
	$inputFilter->add([
                'name'     => 'lastName',
                'required' => false,
                'filters'  => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                    ['name' => 'StripNewlines'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 128
                        ],
                    ],
                ],
            ]);
 
        $inputFilter->add([
                'name'     => 'email',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],                    
                ],                
                'validators' => [
                    [
                        'name' => 'EmailAddress',
                        'options' => [
                            'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                            'useMxCheck'    => false,                            
                        ],
                    ],
                ],
            ]);

    }
    
    /**
     * Custom filter for a phone number.
     * @param string $value User-entered phone number.
     * @param string $format Desired phone format ('intl' or 'local').
     * @return string Phone number in form of "1 (808) 456-7890" or "123-4567".
     */
    public function filterPhone($value, $format) {
                
        if(!is_scalar($value)) {
            // Return non-scalar value unfiltered.
            return $value;
        }
            
        $value = (string)$value;
        
        if(strlen($value)==0) {
            // Return empty value unfiltered.
            return $value;
        }
        
        // First remove any non-digit character.
        $digits = preg_replace('#[^0-9]#', '', $value);
        
        if($format == 'intl') {
            
            // Pad with zeros if count of digits is incorrect.
            $digits = str_pad($digits, 11, "0", STR_PAD_LEFT);

            // Add the braces, spacing and the dash.
            $phoneNumber = substr($digits, 0, 1) . ' ('. substr($digits, 1, 3) . ') ' .
                            substr($digits, 4, 3) . '-'. substr($digits, 7, 4);
        } else { // 'local'
            // Pad with zeros if count of digits is incorrect.
            $digits = str_pad($digits, 7, "0", STR_PAD_LEFT);

            // Add the the dash.
            $phoneNumber = substr($digits, 0, 3) . '-'. substr($digits, 3, 4);
        }
        
        return $phoneNumber;                  
    }
    
    /**
     * Custom validator for a phone number.
     * @param string $value Phone number in form of "1 (808) 456-7890"
     * @params array $context Form field values.
     * @param string $format Phone format ('intl' or 'local').
     * @return boolean true if phone format is correct; otherwise false.
     */
    public function validatePhone($value, $context, $format) {
                
        // Determine the correct length and pattern of the phone number,
        // depending on the format.
        if($format == 'intl') {
            $correctLength = 16;
            $pattern = '/^\d\ (\d{3}\) \d{3}-\d{4}$/';
        } else { // 'local'
            $correctLength = 8;
            $pattern = '/^\d{3}-\d{4}$/';
        }
                
        // Check phone number length.
        if(strlen($value)!=$correctLength)
            return false;

        // Check if the value matches the pattern.
        $matchCount = preg_match($pattern, $value);
        
        return ($matchCount!=0)?true:false;
    }
}

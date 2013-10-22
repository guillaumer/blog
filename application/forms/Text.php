<?php

/**
 * DONO
 *
 * @author g.raoult@gmail.com
 */

class Application_Form_Text extends Zend_Form_Element_Text
{
    public function __construct($options = null,$label){
            parent::__construct($options);
            $this->setLabel($label)
                    ->setRequired(true)
                    ->addFilter('StripTags')
                    ->addFilter('StringTrim');
    }

}


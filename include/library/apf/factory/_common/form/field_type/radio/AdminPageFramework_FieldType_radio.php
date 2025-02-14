<?php 
/**
	Admin Page Framework v3.9.0b10 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/task-scheduler>
	Copyright (c) 2013-2021, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class TaskScheduler_AdminPageFramework_FieldType_radio extends TaskScheduler_AdminPageFramework_FieldType {
    public $aFieldTypeSlugs = array('radio');
    protected $aDefaultKeys = array('label' => array(), 'attributes' => array(),);
    protected function getScripts() {
        return '';
        $_aJSArray = json_encode($this->aFieldTypeSlugs);
        return <<<JAVASCRIPTS
jQuery( document ).ready( function(){

    jQuery().registerTaskScheduler_AdminPageFrameworkCallbacks( { 
        
        /**
         * Called when a field of this field type gets repeated.
         */
        repeated_field: function( oCloned, aModel ) {            
            oCloned.find( 'input[type=radio]' )
                .off( 'change' )                                    
                .on( 'change', function( e ) {
            
                // Uncheck the other radio buttons
                // prop( 'checked', ... ) does not seem to take effect so use .attr( 'checked' ) also.
                // removeAttr( 'checked' ) causes JQMIGRATE warnings for its deprecation.  
                jQuery( this ).closest( '.task-scheduler-field' ).find( 'input[type=radio]' )
                    .prop( 'checked', false )
                    .attr( 'checked', false ); 
                                    
                // Make sure the clicked item is checked                
                jQuery( this )
                    .prop( 'checked', true )
                    .attr( 'checked', 'checked' );       
            });                           
        },
    },
    {$_aJSArray}
    );
});
JAVASCRIPTS;
        
    }
    protected function getEnqueuingScripts() {
        return array(array('handle_id' => 'task-scheduler-field-type-radio', 'src' => dirname(__FILE__) . '/js/radio.bundle.js', 'in_footer' => true, 'dependencies' => array('jquery', 'task-scheduler-script-form-main'), 'translation_var' => 'TaskScheduler_AdminPageFrameworkFieldTypeRadio', 'translation' => array('fieldTypeSlugs' => $this->aFieldTypeSlugs, 'messages' => array(),),),);
    }
    protected function getField($aField) {
        $_aOutput = array();
        foreach ($this->getAsArray($aField['label']) as $_sKey => $_sLabel) {
            $_aOutput[] = $this->_getEachRadioButtonOutput($aField, $_sKey, $_sLabel);
        }
        $_aOutput[] = $this->_getUpdateCheckedScript($aField['input_id']);
        return implode(PHP_EOL, $_aOutput);
    }
    private function _getEachRadioButtonOutput(array $aField, $sKey, $sLabel) {
        $_aAttributes = $aField['attributes'] + $this->getElementAsArray($aField, array('attributes', $sKey));
        $_oRadio = new TaskScheduler_AdminPageFramework_Input_radio($_aAttributes);
        $_oRadio->setAttributesByKey($sKey);
        $_oRadio->setAttribute('data-default', $aField['default']);
        return $this->getElementByLabel($aField['before_label'], $sKey, $aField['label']) . "<div " . $this->getLabelContainerAttributes($aField, 'task-scheduler-input-label-container task-scheduler-radio-label') . ">" . "<label " . $this->getAttributes(array('for' => $_oRadio->getAttribute('id'), 'class' => $_oRadio->getAttribute('disabled') ? 'disabled' : null,)) . ">" . $this->getElementByLabel($aField['before_input'], $sKey, $aField['label']) . $_oRadio->get($sLabel) . $this->getElementByLabel($aField['after_input'], $sKey, $aField['label']) . "</label>" . "</div>" . $this->getElementByLabel($aField['after_label'], $sKey, $aField['label']);
    }
    private function _getUpdateCheckedScript($sInputID) {
        $_sScript = <<<JAVASCRIPTS
jQuery( document ).ready( function(){
    jQuery( 'input[type=radio][data-id=\"{$sInputID}\"]' ).on( 'change', function( e ) {
    
        // Uncheck the other radio buttons
        jQuery( this ).closest( '.task-scheduler-field' ).find( 'input[type=radio][data-id=\"{$sInputID}\"]' )
            .prop( 'checked', false )
            .attr( 'checked', false );
        
        // Make sure the clicked item is checked
        jQuery( this )  
            .prop( 'checked', true )
            .attr( 'checked', 'checked' );

    });
});                 
JAVASCRIPTS;
        return "<script type='text/javascript' class='radio-button-checked-attribute-updater'>" . '/* <![CDATA[ */' . $_sScript . '/* ]]> */' . "</script>";
    }
    }
    
<?php 
/**
	Admin Page Framework v3.9.0b10 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/task-scheduler>
	Copyright (c) 2013-2021, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class TaskScheduler_AdminPageFramework_FieldType_select extends TaskScheduler_AdminPageFramework_FieldType {
    public $aFieldTypeSlugs = array('select',);
    protected $aDefaultKeys = array('label' => array(), 'is_multiple' => false, 'attributes' => array('select' => array('size' => 1, 'autofocusNew' => null, 'multiple' => null, 'required' => null,), 'optgroup' => array(), 'option' => array(),),);
    protected function getStyles() {
        return ".task-scheduler-field-select .task-scheduler-input-label-container {vertical-align: top; }.task-scheduler-field-select .task-scheduler-input-label-container {padding-right: 1em;}";
    }
    protected function getField($aField) {
        $_oSelectInput = new TaskScheduler_AdminPageFramework_Input_select($aField['attributes']);
        if ($aField['is_multiple']) {
            $_oSelectInput->setAttribute(array('select', 'multiple'), 'multiple');
        }
        return $aField['before_label'] . "<div " . $this->getLabelContainerAttributes($aField, 'task-scheduler-input-label-container task-scheduler-select-label') . ">" . "<label for='{$aField['input_id']}'>" . $aField['before_input'] . $_oSelectInput->get($aField['label']) . $aField['after_input'] . "<div class='repeatable-field-buttons'></div>" . "</label>" . "</div>" . $aField['after_label'];
    }
    }
    
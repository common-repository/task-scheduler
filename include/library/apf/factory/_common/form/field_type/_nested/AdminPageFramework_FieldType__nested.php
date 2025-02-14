<?php 
/**
	Admin Page Framework v3.9.0b10 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/task-scheduler>
	Copyright (c) 2013-2021, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class TaskScheduler_AdminPageFramework_FieldType__nested extends TaskScheduler_AdminPageFramework_FieldType {
    public $aFieldTypeSlugs = array('_nested');
    protected $aDefaultKeys = array();
    protected function getStyles() {
        return ".task-scheduler-fieldset > .task-scheduler-fields > .task-scheduler-field.with-nested-fields > .task-scheduler-fieldset.multiple-nesting {margin-left: 2em;}.task-scheduler-fieldset > .task-scheduler-fields > .task-scheduler-field.with-nested-fields > .task-scheduler-fieldset {margin-bottom: 1em;}.with-nested-fields > .task-scheduler-fieldset.child-fieldset > .task-scheduler-child-field-title {display: inline-block;padding: 0 0 0.4em 0;}.task-scheduler-fieldset.child-fieldset > label.task-scheduler-child-field-title {display: table-row; white-space: nowrap;}";
    }
    protected function getField($aField) {
        $_oCallerForm = $aField['_caller_object'];
        $_aInlineMixedOutput = array();
        foreach ($this->getAsArray($aField['content']) as $_aChildFieldset) {
            if (is_scalar($_aChildFieldset)) {
                continue;
            }
            if (!$this->isNormalPlacement($_aChildFieldset)) {
                continue;
            }
            $_aChildFieldset = $this->getFieldsetReformattedBySubFieldIndex($_aChildFieldset, ( integer )$aField['_index'], $aField['_is_multiple_fields'], $aField);
            $_oFieldset = new TaskScheduler_AdminPageFramework_Form_View___Fieldset($_aChildFieldset, $_oCallerForm->aSavedData, $_oCallerForm->getFieldErrors(), $_oCallerForm->aFieldTypeDefinitions, $_oCallerForm->oMsg, $_oCallerForm->aCallbacks);
            $_aInlineMixedOutput[] = $_oFieldset->get();
        }
        return implode('', $_aInlineMixedOutput);
    }
    }
    
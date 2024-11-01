<?php 
/**
	Admin Page Framework v3.9.0b10 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/task-scheduler>
	Copyright (c) 2013-2021, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class TaskScheduler_AdminPageFramework_PostType extends TaskScheduler_AdminPageFramework_PostType_Controller {
    protected $_sStructureType = 'post_type';
    public function __construct($sPostType, $aArguments = array(), $sCallerPath = null, $sTextDomain = 'task-scheduler') {
        if (empty($sPostType)) {
            return;
        }
        $_sPropertyClassName = isset($this->aSubClassNames['oProp']) ? $this->aSubClassNames['oProp'] : 'TaskScheduler_AdminPageFramework_Property_' . $this->_sStructureType;
        $this->oProp = new $_sPropertyClassName($this, $this->_getCallerScriptPath($sCallerPath), get_class($this), 'publish_posts', $sTextDomain, $this->_sStructureType);
        $this->oProp->sPostType = TaskScheduler_AdminPageFramework_WPUtility::sanitizeSlug($sPostType);
        $this->oProp->aPostTypeArgs = $aArguments;
        parent::__construct($this->oProp);
    }
    private function _getCallerScriptPath($sCallerPath) {
        $sCallerPath = trim($sCallerPath);
        if ($sCallerPath) {
            return $sCallerPath;
        }
        if (!is_admin()) {
            return null;
        }
        $_sPageNow = TaskScheduler_AdminPageFramework_Utility::getElement($GLOBALS, 'pagenow');
        if (in_array($_sPageNow, array('edit.php', 'post.php', 'post-new.php', 'plugins.php', 'tags.php', 'edit-tags.php', 'term.php'))) {
            return TaskScheduler_AdminPageFramework_Utility::getCallerScriptPath(__FILE__);
        }
        return null;
    }
    }
    
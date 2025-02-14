<?php 
/**
	Admin Page Framework v3.9.0b10 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/task-scheduler>
	Copyright (c) 2013-2021, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class TaskScheduler_AdminPageFramework_FormEmail extends TaskScheduler_AdminPageFramework_FrameworkUtility {
    public $aEmailOptions = array();
    public $aInput = array();
    public $sSubmitSectionID;
    private $_aPathsToDelete = array();
    private $_sEmailSenderAddress;
    private $_sEmailSenderName;
    public function __construct(array $aEmailOptions, array $aInput, $sSubmitSectionID) {
        $this->aEmailOptions = $aEmailOptions;
        $this->aInput = $aInput;
        $this->sSubmitSectionID = $sSubmitSectionID;
        $this->_aPathsToDelete = array();
    }
    public function send() {
        $_aEmailOptions = $this->aEmailOptions;
        $_aInputs = $this->aInput;
        $_sSubmitSectionID = $this->sSubmitSectionID;
        do_action('task-scheduler_action_before_sending_form_email', $_aEmailOptions, $_aInputs, $_sSubmitSectionID);
        if ($_bIsHTML = $this->___getEmailArgument($_aInputs, $_aEmailOptions, 'is_html', $_sSubmitSectionID)) {
            add_filter('wp_mail_content_type', array($this, '_replyToSetMailContentTypeToHTML'));
        }
        if ($this->_sEmailSenderAddress = $this->___getEmailArgument($_aInputs, $_aEmailOptions, 'from', $_sSubmitSectionID)) {
            add_filter('wp_mail_from', array($this, '_replyToSetEmailSenderAddress'));
        }
        if ($this->_sEmailSenderName = $this->___getEmailArgument($_aInputs, $_aEmailOptions, 'name', $_sSubmitSectionID)) {
            add_filter('wp_mail_from_name', array($this, '_replyToSetEmailSenderName'));
        }
        $_bSent = wp_mail($this->___getEmailArgument($_aInputs, $_aEmailOptions, 'to', $_sSubmitSectionID), $this->___getEmailArgument($_aInputs, $_aEmailOptions, 'subject', $_sSubmitSectionID), $this->___getMessage($_aInputs, $_aEmailOptions, $_sSubmitSectionID, $_bIsHTML), $this->___getEmailArgument($_aInputs, $_aEmailOptions, 'headers', $_sSubmitSectionID), $this->___getAttachmentsFormatted($this->___getEmailArgument($_aInputs, $_aEmailOptions, 'attachments', $_sSubmitSectionID)));
        foreach ($this->_aPathsToDelete as $_sPath) {
            unlink($_sPath);
        }
        do_action('task-scheduler_action_after_sending_form_email', $_bSent, $_aEmailOptions);
        remove_filter('wp_mail_content_type', array($this, '_replyToSetMailContentTypeToHTML'));
        remove_filter('wp_mail_from', array($this, '_replyToSetEmailSenderAddress'));
        remove_filter('wp_mail_from_name', array($this, '_replyToSetEmailSenderAddress'));
        return $_bSent;
    }
    private function ___getMessage($aInputs, $aEmailOptions, $sSubmitSectionID, $bIsHTML) {
        if (!$bIsHTML) {
            return $this->getReadableListOfArray(( array )$this->___getEmailArgument($aInputs, $aEmailOptions, 'message', $sSubmitSectionID)) . $this->getReadableListOfArray($this->getElementAsArray($aEmailOptions, array('data')));
        }
        $_aAttributes = array('td' => array(array('style' => 'vertical-align: top; width: 12%;',), array('style' => 'vertical-align: top; white-space: pre;',),),);
        $_aExtraData = $this->getElementAsArray($aEmailOptions, array('data'));
        return $this->getTableOfArray(( array )$this->___getEmailArgument($aInputs, $aEmailOptions, 'message', $sSubmitSectionID), $_aAttributes, array(), array(), false) . (empty($_aExtraData) ? '' : $this->getTableOfArray($_aExtraData, $_aAttributes));
    }
    private function ___getAttachmentsFormatted($asAttachments) {
        if (empty($asAttachments)) {
            return '';
        }
        $_aAttachments = $this->getAsArray($asAttachments);
        foreach ($_aAttachments as $_iIndex => $_sPathORURL) {
            if (is_file($_sPathORURL)) {
                continue;
            }
            if (false !== filter_var($_sPathORURL, FILTER_VALIDATE_URL)) {
                if ($_sPath = $this->___getPathFromURL($_sPathORURL)) {
                    $_aAttachments[$_iIndex] = $_sPath;
                    continue;
                }
            }
            unset($_aAttachments[$_iIndex]);
        }
        return $_aAttachments;
    }
    private function ___getPathFromURL($sURL) {
        $_sPath = $this->___getPathFromURLWithinSite($sURL);
        if ($_sPath) {
            return $_sPath;
        }
        $_sPath = $this->download($sURL, 10);
        if (is_string($_sPath)) {
            $this->_aPathsToDelete[$_sPath] = $_sPath;
            return $_sPath;
        }
        return '';
    }
    private function ___getPathFromURLWithinSite($sURL) {
        $_sPath = realpath(str_replace(content_url(), WP_CONTENT_DIR, $sURL));
        if ($_sPath) {
            return $_sPath;
        }
        return realpath(str_replace(get_bloginfo('url'), ABSPATH, $sURL));
    }
    public function _replyToSetMailContentTypeToHTML($sContentType) {
        return 'text/html';
    }
    function _replyToSetEmailSenderAddress($sEmailSenderAddress) {
        return $this->_sEmailSenderAddress;
    }
    function _replyToSetEmailSenderName($sEmailSenderAddress) {
        return $this->_sEmailSenderName;
    }
    private function ___getEmailArgument($aInput, array $aEmailOptions, $sKey, $sSectionID) {
        if (is_array($aEmailOptions[$sKey])) {
            return $this->getArrayValueByArrayKeys($aInput, $aEmailOptions[$sKey]);
        }
        if (!$aEmailOptions[$sKey]) {
            return $this->getArrayValueByArrayKeys($aInput, array($sSectionID, $sKey));
        }
        return $aEmailOptions[$sKey];
    }
    }
    
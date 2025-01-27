<?php 
/**
	Admin Page Framework v3.9.0b10 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/task-scheduler>
	Copyright (c) 2013-2021, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class TaskScheduler_AdminPageFramework_Utility_String extends TaskScheduler_AdminPageFramework_Utility_VariableType {
    static public function getLengthSanitized($sLength, $sUnit = 'px') {
        $sLength = $sLength ? $sLength : 0;
        return is_numeric($sLength) ? $sLength . $sUnit : $sLength;
    }
    public static function sanitizeSlug($sSlug) {
        return is_null($sSlug) ? null : preg_replace('/[^a-zA-Z0-9_\x7f-\xff]/', '_', trim($sSlug));
    }
    public static function sanitizeString($sString) {
        return is_null($sString) ? null : preg_replace('/[^a-zA-Z0-9_\x7f-\xff\-]/', '_', $sString);
    }
    static public function getNumberFixed($nToFix, $nDefault, $nMin = '', $nMax = '') {
        if (!is_numeric(trim($nToFix))) {
            return $nDefault;
        }
        if ($nMin !== '' && $nToFix < $nMin) {
            return $nMin;
        }
        if ($nMax !== '' && $nToFix > $nMax) {
            return $nMax;
        }
        return $nToFix;
    }
    static public function fixNumber($nToFix, $nDefault, $nMin = '', $nMax = '') {
        return self::getNumberFixed($nToFix, $nDefault, $nMin, $nMax);
    }
    static public function getCSSMinified($sCSSRules) {
        return str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $sCSSRules));
    }
    static public function getStringLength($sString) {
        return function_exists('mb_strlen') ? mb_strlen($sString) : strlen($sString);
    }
    static public function getNumberOfReadableSize($nSize) {
        $_nReturn = substr($nSize, 0, -1);
        switch (strtoupper(substr($nSize, -1))) {
            case 'P':
                $_nReturn*= 1024;
            case 'T':
                $_nReturn*= 1024;
            case 'G':
                $_nReturn*= 1024;
            case 'M':
                $_nReturn*= 1024;
            case 'K':
                $_nReturn*= 1024;
        }
        return $_nReturn;
    }
    static public function getReadableBytes($nBytes, $iRoundPrecision = 2) {
        $_aUnits = array(0 => 'B', 1 => 'kB', 2 => 'MB', 3 => 'GB');
        $_nLog = log($nBytes, 1024);
        $_iPower = ( int )$_nLog;
        $_ifSize = pow(1024, $_nLog - $_iPower);
        $_ifSize = round($_ifSize, $iRoundPrecision);
        return $_ifSize . $_aUnits[$_iPower];
    }
    static public function getPrefixRemoved($sString, $sPrefix) {
        return self::hasPrefix($sPrefix, $sString) ? substr($sString, strlen($sPrefix)) : $sString;
    }
    static public function getSuffixRemoved($sString, $sSuffix) {
        return self::hasSuffix($sSuffix, $sString) ? substr($sString, 0, strlen($sSuffix) * -1) : $sString;
    }
    static public function hasPrefix($sNeedle, $sHaystack) {
        return ( string )$sNeedle === substr($sHaystack, 0, strlen(( string )$sNeedle));
    }
    static public function hasSuffix($sNeedle, $sHaystack) {
        $_iLength = strlen(( string )$sNeedle);
        if (0 === $_iLength) {
            return true;
        }
        return substr($sHaystack, -$_iLength) === $sNeedle;
    }
    static public function hasSlash($sString) {
        $sString = str_replace('\\', '/', $sString);
        return (false !== strpos($sString, '/'));
    }
    }
    
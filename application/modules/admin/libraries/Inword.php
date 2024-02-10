<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inword {
    
    private static $CI;

    public function __construct()
    {
        self::$CI = & get_instance();
    }
    
    public function numberToStr($number) {
    	if (($number < 0) || ($number > 999999999)) {
    		throw new Exception("Number is out of range");
    	}
    	$Gn = floor($number / 1000000);
    	/* Millions (giga) */
    	$number -= $Gn * 1000000;
    	$kn = floor($number / 1000);
    	/* Thousands (kilo) */
    	$number -= $kn * 1000;
    	$Hn = floor($number / 100);
    	/* Hundreds (hecto) */
    	$number -= $Hn * 100;
    	$Dn = floor($number / 10);
    	/* Tens (deca) */
    	$n = $number % 10;
    	/* Ones */
    	$res = "";
    	if ($Gn) {
    		$res .= self::numberToStr($Gn) .  "Million";
    	}
    	if ($kn) {
    		$res .= (empty($res) ? "" : " ") . self::numberToStr($kn) . " Thousand";
    	}
    	if ($Hn) {
    		$res .= (empty($res) ? "" : " ") . self::numberToStr($Hn) . " Hundred";
    	}
    	$ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
    	$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
    	if ($Dn || $n) {
    		if (!empty($res)) {
    			$res .= " and ";
    		}
    		if ($Dn < 2) {
    			$res .= $ones[$Dn * 10 + $n];
    		} else {
    			$res .= $tens[$Dn];
    			if ($n) {
    				$res .= "-" . $ones[$n];
    			}
    		}
    	}
    	if (empty($res)) {
    		$res = "Zero";
    	}
    	return $res;
    }
}
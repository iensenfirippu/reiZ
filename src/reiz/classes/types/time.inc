<?php
//------------------------------------------------------------
// Project:		reiZ CMS
// License:		GPL v2
//
// Contents:		Extention of basic PHP functionality
// Created by:		Philip Jensen (me@iensenfirippu.dk)
// Class version:	0.1
// Date:				2015/08/10
//------------------------------------------------------------

// Make sure to check if the script is being run inside "reiZ"
if (defined('reiZ') or exit(1))
{
	/**
	 * description
	 * @param param, description.
	 */
	function TimestampToHumanTime($timestamp, $format = false)
	{
		// TODO: ineffective, enhance using halfing technique. (N becomes N/2)
		
		$string = EMPTYSTRING;
		
		if ($timestamp != null)
		{
			$diff = STARTTIME - $timestamp;
			
			if (is_string($format) !== false) { $string .= date($format, $timestamp); }
			elseif ($diff > (ONEDAY + TODAYSTIME)) { $string .= date('\t\h\e jS \o\f F, Y \a\t H:i', $timestamp); }
			elseif ($diff > TODAYSTIME) { $string .= 'yesterday at '.date('H:i', $timestamp); }
			elseif ($diff > ONEHOUR) { $string .= 'today at '.date('H:i', $timestamp); }
			elseif ($diff > ONEMINUTE) {
				$plural = ''; if(intval($diff / ONEMINUTE) != 1) { $plural = 's';}
				$string .= intval($diff / ONEMINUTE).' minute'.$plural.' ago';
			}
			elseif ($diff > 0) {
				$plural = ''; if($diff != 1) { $plural = 's';}
				$string .= round($diff).' second'.$plural.' ago';
			}
			elseif ($diff == 0) { $string .= 'just now!'; }
			elseif ($diff > -ONEMINUTE) {
				$plural = ''; if($diff != -1) { $plural = 's';}
				$string .= 'in '.abs($diff).' second'.$plural;
			}
			elseif ($diff > -ONEHOUR) {
				$plural = ''; if(abs(intval($diff / ONEMINUTE)) != 1) { $plural = 's';}
				$string .= 'in '.abs(intval($diff / ONEMINUTE)).' minute'.$plural;
			}
			elseif ($diff > -ONEDAY) {
				$plural = ''; if(abs(intval($diff / ONEHOUR)) != 1) { $plural = 's';}
				$string .= 'in '.abs(intval($diff / ONEHOUR)).' hour'.$plural;
			}
			else {
				$plural = ''; if(abs(intval($diff / ONEDAY)) != 1) { $plural = 's';}
				$string .= 'in '.abs(intval($diff / ONEDAY)).' day'.$plural;
			}
		}
		
		return $string;
	}
		
	abstract class TimeFormat
	{
		const HumanTime	= false;
		const Date			= 'D-M-Y';
		const DateTime	= 'd-m-y H:i';
		const Time			= 'H:i';
	}
	abstract class TF extends TimeFormat {}
}
?>
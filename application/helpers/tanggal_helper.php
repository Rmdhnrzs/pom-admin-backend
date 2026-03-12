<?php

function tanggal1($tanggal)
{
	// "01-09-2022 07:04:18" >> "1/9/2020"
	$date=explode(" ", $tanggal);
	$date = $date[0];
	$date = str_replace("-","/",$date);
    return $date;
}

function tanggal2($tanggal)
{
	// "2022-09-01 07:04" >> "1/9/2020"
	$date 	= explode(" ", $tanggal);
	$dates 	= $date[0];
	$date 	= explode("-", $dates);
	$day 	= $date[2];
	$month 	= $date[1];
	$year 	= $date[0];

	$date 	= $day."/".$month."/".$year;
    return $date;
}

function tanggal3($tanggal = "02 Mei 2022")
{
	// "02 Mei 2022" >> "1/9/2020"
	$date 	= explode(" ", $tanggal);
	$day 	= $date[0];
	$month 	= $date[1];
	$year 	= $date[2];

	if ($month == "Jan") {
		$month = 1;
	} elseif($month == "Feb") {
		$month = 2;
	}elseif($month == "Mar") {
		$month = 3;
	}elseif($month == "Apr") {
		$month = 4;
	}elseif($month == "Mei") {
		$month = 5;
	}elseif($month == "Jun") {
		$month = 6;
	}elseif($month == "Jul") {
		$month = 7;
	}elseif($month == "Agu") {
		$month = 8;
	}elseif($month == "Sep") {
		$month = 9;
	}elseif($month == "Okt") {
		$month = 10;
	}elseif($month == "Nov") {
		$month = 11;
	}elseif($month == "Des") {
		$month = 12;
	} else {
		$month = 0;
	}
	

	$date 	= $day."/".$month."/".$year;
    return $date;
}

function tanggal4($tanggal = "02-Mei-2022")
{
	// "02 Mei 2022" >> "1/9/2020"
	$date 	= explode("-", $tanggal);
	$day 	= ltrim($date[0],'0');
	$month 	= $date[1];
	$year 	= '20'.$date[2];

	if ($month == "JAN") {
		$month = 1;
	} elseif($month == "FEB") {
		$month = 2;
	}elseif($month == "MAR") {
		$month = 3;
	}elseif($month == "APR") {
		$month = 4;
	}elseif($month == "MAY" OR $month == "MEI") {
		$month = 5;
	}elseif($month == "JUN") {
		$month = 6;
	}elseif($month == "JUL") {
		$month = 7;
	}elseif($month == "AUG" OR $month == "AGU") {
		$month = 8;
	}elseif($month == "SEP") {
		$month = 9;
	}elseif($month == "OCT" OR $month == "OKT") {
		$month = 10;
	}elseif($month == "NOV") {
		$month = 11;
	}elseif($month == "DES") {
		$month = 12;
	} else {
		$month = 0;
	}
	

	$date 	= $day."/".$month."/".$year;
    return $date;
}

function tanggal_besok()
{
	if (date("D") == "Sat") {
		$tgl_besok    = date('Y-m-d', strtotime("+2 day", strtotime(date("Y-m-d"))));
	} else {
		$tgl_besok    = date('Y-m-d', strtotime("+1 day", strtotime(date("Y-m-d"))));
	}
	$tgl_besok 	= explode("-", $tgl_besok);

	$day 	= ltrim($tgl_besok[2],'0');
	$month 	= ltrim($tgl_besok[1],'0');
	$year 	= $tgl_besok[0];

	$tgl_besok = $day."/".$month."/".$year;

    return $tgl_besok;
}

function tanggal_lusa()
{
	if (date("D") == "Fri") {
		$tgl_besok    = date('Y-m-d', strtotime("+3 day", strtotime(date("Y-m-d"))));
	} elseif (date("D") == "Sat") {
		$tgl_besok    = date('Y-m-d', strtotime("+3 day", strtotime(date("Y-m-d"))));
	} else {
		$tgl_besok    = date('Y-m-d', strtotime("+2 day", strtotime(date("Y-m-d"))));
	}

	
	$tgl_besok 	= explode("-", $tgl_besok);

	$day 	= ltrim($tgl_besok[2],'0');
	$month 	= ltrim($tgl_besok[1],'0');
	$year 	= $tgl_besok[0];

	$tgl_besok = $day."/".$month."/".$year;

    return $tgl_besok;
}

?>
<?php 

$datecsv = "2016-03-01";

$date_time_csv = "1999/12/30 11:58:00";

$PATTERN_DATE_CSV='/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/';

if (preg_match($PATTERN_DATE_CSV, $datecsv, $matches)){
	print_r($matches);
} else {
	echo "pas de correspondance trouvée pour la date";
}

$PATTERN_DATETIME_CSV = '/^([0-9]{4}\/[0-9]{2}\/[0-9]{2})\s+([0-9]{2}:[0-9]{2}:[0-9]{2})$/';

if (preg_match($PATTERN_DATETIME_CSV, $date_time_csv, $matches)){
	print_r($matches);
} else {
	echo "pas de correspondance trouvée pour l'heures csv";
}

?>

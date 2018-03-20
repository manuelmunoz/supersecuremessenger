<?php

// Start the session
session_start();

$DBUSER="root";
$DBPASSWD="tomalacasitos";
$DATABASE="supersecuremessenger";

$filename = "backup-" . date("d-m-Y") . ".sql.gz";
$mime = "application/x-gzip";

header( "Content-Type: " . $mime );
header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

$cmd = "mysqldump -u $DBUSER --password=$DBPASSWD $DATABASE | gzip --best";

passthru( $cmd );

exit(0);

?>

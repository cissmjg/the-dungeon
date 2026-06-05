<?php
    const ERROR_LOG_FILE_NAME = 'error_log';
    $log_contents = file_get_contents(ERROR_LOG_FILE_NAME);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Cache-Control" content="no-store">

    <title>Error Log</title>
</head>
<body>
    <pre>
<?= $log_contents ?>
    </pre>
</body>
</html>
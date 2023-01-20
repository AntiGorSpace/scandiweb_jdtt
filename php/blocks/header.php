<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/static/style.css" />
    <script src="/static/libs/jquerry.3.6.1.js"></script>
    <title><?=$title?></title>
</head>
<body>
    <div class="header">
        <h1><?=$title?></h1>
        <div>
            <?php
                foreach ($head_buttons as $button) {
                    echo "<button id='$button[id]' class='head-button' >$button[name]</button>";
                }
            ?>
        </div> 
    </div> 
    <div class="split-line"></div>


<?php require_once "code/start_connection.php" ?>
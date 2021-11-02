<?php
$mysqli = mysqli_connect(
    getenv('db_hostname'),
    getenv('db_username'),
    getenv('db_password'),
    getenv('db_name')
);

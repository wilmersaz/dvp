<?php

//Base URL
define("BASE_URL", isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https://' : 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
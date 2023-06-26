<?php

// $isFolder = is_dir("\\\\192.168.110.37\applications$\meubelopslag\meubelfoto");
$isFolder = is_dir("\\\\192.168.110.37\applications$");
var_dump($isFolder); //TRUE

$isFolder = is_dir("//192.168.110.37/applications$");
var_dump($isFolder); //TRUE
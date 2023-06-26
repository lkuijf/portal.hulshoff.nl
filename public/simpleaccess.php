<?php

$isFolder = is_dir("\\\\192.168.110.37\applications$\meubelopslag\meubelfoto");
var_dump($isFolder); //TRUE

// $isFolder = is_dir("//NAS/Main Disk");
// var_dump($isFolder); //TRUE
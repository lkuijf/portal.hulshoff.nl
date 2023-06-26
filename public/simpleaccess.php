<?php
$username = 'opslag-user';
$password = 'casa123!';
// $remoteFilePath = '\\\\network_drive\\folder\\file.txt';
$remoteFilePath = '\\\\192.168.110.37\\applications$\\MeubelOpslag\\Meubelfoto\\50246\\00010.jpg';

$handle = fopen($remoteFilePath, 'r', false, stream_context_create([
    'smb2' => [
        'username' => $username,
        'password' => $password
    ]
]));

if ($handle === false) {
    // Unable to open the file, handle the error
    die('Unable to access the file.');
}

// Read the contents of the file
$fileContents = fread($handle, filesize($remoteFilePath));

// Close the file handle
fclose($handle);

// Process the file contents as needed
echo $fileContents;
?>
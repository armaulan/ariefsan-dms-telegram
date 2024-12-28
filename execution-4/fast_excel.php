<?php #https://ariefsan.crewbasproject.my.id/telegram/execution-4/fast_excel.php

//require_once 'zip_archive.php';

if (function_exists('zip_open')) {
  echo "ZipArchive extension is loaded!";
} else {
  echo "ZipArchive extension is not loaded.";
}

$excel_file = '../execution-3/report/download/O2CX_monthly_amount.xlsx';

$zip = new ZipArchive;
if ($zip->open('test_new4.zip', ZipArchive::CREATE) === TRUE)
{
    $zip->setPassword('secret');
    // Add files to the zip file
    $zip->addFile($excel_file, "test.xlsx");
    $zip->setEncryptionName('text.txt', ZipArchive::EM_AES_256);

    // All files are added, so close the zip file.
    
    $zip->close();
}

#if (!$zip_open_result) {
#    die('Error: Could not create ZIP archive.');
#}


#$password = '123';
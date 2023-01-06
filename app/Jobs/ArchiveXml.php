<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Job;

class ArchiveXml implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $startedAt = date("Y-m-d H:i:s");
            $totalZippedFiles = 0;
            $types = ['klant','artikel','order','vrdstand'];
            $filesToArchive = array_fill_keys($types, []);

            foreach($types as $type) {
                foreach(Storage::disk('local_xml_' . $type)->files() as $file) {
                    if(date('U') - Storage::disk('local_xml_' . $type)->lastModified($file) > config('hulshoff.archiveXmlWhenOlderThanXDays')*60*60*24) {
                        $filesToArchive[$type][] = Storage::disk('local_xml_' . $type)->path('') . $file;
                    }
                }
            }

            foreach($filesToArchive as $type => $files) {
                if(count($files)) {
                    $zip = new \ZipArchive();
                    $filename = $type . '-' . date("Ymd-His") . '.zip';
                    $fileSystemPathToFile = Storage::disk('local_xml_' . $type . '_archived')->path('') . $filename;
                    if(file_exists($fileSystemPathToFile)) throw new \Exception('File already exists: ' . $fileSystemPathToFile);
                    
                    $created = $zip->open($fileSystemPathToFile, \ZipArchive::CREATE);
                    if($created === TRUE) {
                        foreach($files as $file){
                            $zip->addFile($file, basename($file));
                            $totalZippedFiles++;
                        }
                        $zip->close();
                    } else {
                        $message = match ($created) {
                            \ZipArchive::ER_MULTIDISK   => 'Multi-disk zip archives not supported',
                            \ZipArchive::ER_RENAME      => 'Renaming temporary file failed',
                            \ZipArchive::ER_CLOSE       => 'Closing zip archive failed',
                            \ZipArchive::ER_SEEK        => 'Seek error',
                            \ZipArchive::ER_READ        => 'Read error',
                            \ZipArchive::ER_WRITE       => 'Write error',
                            \ZipArchive::ER_CRC         => 'CRC error',
                            \ZipArchive::ER_ZIPCLOSED   => 'Containing zip archive was closed',
                            \ZipArchive::ER_NOENT       => 'No such file',
                            \ZipArchive::ER_EXISTS      => 'File already exists',
                            \ZipArchive::ER_OPEN        => 'Can\'t open file',
                            \ZipArchive::ER_TMPOPEN     => 'Failure to create temporary file',
                            \ZipArchive::ER_ZLIB        => 'Zlib error',
                            \ZipArchive::ER_MEMORY      => 'Malloc failure',
                            \ZipArchive::ER_CHANGED     => 'Entry has been changed',
                            \ZipArchive::ER_COMPNOTSUPP => 'Compression method not supported',
                            \ZipArchive::ER_EOF         => 'Premature EOF',
                            \ZipArchive::ER_INVAL       => 'Invalid argument',
                            \ZipArchive::ER_NOZIP       => 'Not a zip archive',
                            \ZipArchive::ER_INTERNAL    => 'Internal error',
                            \ZipArchive::ER_INCONS      => 'Zip archive inconsistent',
                            \ZipArchive::ER_REMOVE      => 'Can\'t remove file',
                            \ZipArchive::ER_DELETED     => 'Entry has been deleted',
                            // \ZipArchive::ER_OK          => 'No error',
                            default                     => 'No error',
                        };
                        throw new \Exception('ZipArchive Error about file ' . $fileSystemPathToFile . ': ' . $message);
                    }
                }
            }

            $endedAt = date("Y-m-d H:i:s");
            $job = new Job;
            $results = [
                'total' => $totalZippedFiles,
                'processed' => $totalZippedFiles,
                'skipped' => 0,
            ];
            $job->newEntry(get_class($this), $startedAt, $endedAt, $results);
        } catch (\Exception $e) {
            // $data->error = $e->getMessage();
            Mail::raw($e->getMessage(), function ($message) {
                $message
                  ->to('leon@wtmedia-events.nl')
                  ->subject('ArchiveXml job failed!');
              });
        }

    }
}

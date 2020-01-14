<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class UpdateSiren extends Command
{
    protected static $defaultName = 'app:siren:update';

    protected function execute(InputInterface $input, OutputInterface $output) {
        echo 'Import data to update siren information'.PHP_EOL;

        $dataGouv = 'http://files.data.gouv.fr/sirene/sirene_2018088_E_Q.zip';
        $request = new \GuzzleHttp\Psr7\Request('GET', $dataGouv);
        $httpClient = new \GuzzleHttp\Client();
        $folderPath =  __DIR__ . '/../../storage/uploads/';
        $fileName = 'sirene_2018088_E_Q';
        $filePath = $folderPath . $fileName;

        try
        {
            echo 'Try import zip file from data.gouv.fr'.PHP_EOL;

            $promise = $httpClient->sendAsync($request)->then(function ($response) use ($folderPath, $fileName, $filePath) {
                echo 'Zip import SUCCESS'.PHP_EOL;
                
                if (file_exists($folderPath)) {
                    file_put_contents($filePath, $response->getBody());

                    $archive = new \ZipArchive;
                    if ($archive->open($filePath)) {
                        echo 'unzip SUCCESS'.PHP_EOL;
                        $archive->extractTo($folderPath);
                        $extractedFileName = $archive->getNameIndex(0);

                        $extractedFilePath_cp1252 = $folderPath . $extractedFileName;
                        $extractedFilePath_utf8 = $folderPath . 'utf8_' . $extractedFileName;

                        echo 'convert CP1252 to UTF8'.PHP_EOL;
                        file_put_contents(
                            $extractedFilePath_utf8,
                            iconv("CP1252", "UTF-8", file_get_contents($extractedFilePath_cp1252))
                        );

                        echo 'delete CP1252 file'.PHP_EOL;
                        unlink($extractedFilePath_cp1252);

                        $archive->close();
                    } else {
                        echo "unzip Failed".PHP_EOL;
                    }
                } else {
                   throw new FileNotFoundException($folderPath);
                }
            });
            $promise->wait();
        }
        catch(\Throwable $throwable)
        {
            echo 'ERROR : Something wrong happened'.PHP_EOL;
        }
    }
}
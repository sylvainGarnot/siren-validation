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

        try
        {
            echo 'Try import zip file from data.gouv.fr'.PHP_EOL;

            $dataGouv = 'http://files.data.gouv.fr/sirene/sirene_2018088_E_Q.zip';
            $request = new \GuzzleHttp\Psr7\Request('GET', $dataGouv);

            $httpClient = new \GuzzleHttp\Client();
            
            $promise = $httpClient->sendAsync($request)->then(function ($response) {
                echo 'Zip import SUCCESS'.PHP_EOL;
                
                $folderPath =  __DIR__ . '/../../storage/uploads/';
                if (!file_exists($folderPath)){
                   throw new FileNotFoundException($folderPath);
                } else {
                    $fileName = 'sirene_2018088_E_Q';
                    $filePath = $folderPath . $fileName;
                    file_put_contents($filePath, $response->getBody());

                    $archive = new \ZipArchive;
                    if ($archive->open($filePath)) {
                        echo "unzip SUCCESS".PHP_EOL;
                        $archive->extractTo($folderPath);
                        $archive->close();
                    } else {
                        echo "unzip Failed".PHP_EOL;
                    }
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
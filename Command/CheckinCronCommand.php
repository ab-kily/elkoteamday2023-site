<?php

namespace GT\Site\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use GT\Site\Model\Sparepart;
use GT\Site\Model\SparepartSku;
use GT\Site\Model\SparepartVendor;
use GT\Site\Component\Logger;
use GT\Site\Component\GApiClient;

class CheckinCronCommand extends Command
{
    const SPREADSHEET_ID = '1HKJNbob4DkyiLs16FmHvk03tn0_uRkBoOQeZ3pwqdj8';
    const MAIN_COL_CNT = 5;
    protected static $defaultName = 'checkin:cron';
    protected static $defaultDescription = 'Upload to db command';
    protected $f3 = null;

    protected function configure(): void
    {
        $this->f3 = \Base::instance();
        $this->setDescription(self::$defaultDescription)
             ->setHelp('Use it to import sparepart data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $api = GApiClient::getGApiClient($this->f3);
        $service = new \Google_Service_Sheets($api);
        $spreadsheetId = self::SPREADSHEET_ID;

        $list = $this->f3->get('DB')->exec("SELECT * FROM Checkin");
        $rows = [];
        foreach($list as $row) {
            $rows[] = array_values($row);
        }
        $values_requests[] = new \Google_Service_Sheets_ValueRange([
            'range'=>"'Записи'!A2",
            'values'=>$rows,
        ]);

        $body = new \Google_Service_Sheets_BatchUpdateValuesRequest([
            'valueInputOption' => 'USER_ENTERED',
            'data' => $values_requests,
        ]);
        $result = $service->spreadsheets_values->batchUpdate($spreadsheetId, $body);
        $output->writeln("Успешно!");
        return Command::SUCCESS;
    }

}

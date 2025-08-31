<?php

namespace App\Command;

use App\Services\SearchService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:search:run',
    description: 'Launch search criteria',
)]
class SearchCommand extends Command
{
    public function __construct(
        private readonly SearchService $searchService
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $results = $this->searchService->run();
        if (empty($results)) {
            $symfonyStyle->warning('No results found');
            return Command::SUCCESS;
        }
        $tableRows = [];
        foreach ($results as $item) {
            $tableRows[] = [$item['criteria']->getId(), count($item['results'])];
        }
        $symfonyStyle->table(
            ['Criteria-id', 'Result found'],
            $tableRows,
        );
        $output->write('<info>Search launched</info>');
        return Command::SUCCESS;
    }
}

<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\DBAL\Connection;

#[AsCommand(
    name: 'crud:test1',
    description: 'Find average assessment by group',
)]
class CrudTest1Command extends Command
{
    private $connection;

    public function __construct(Connection $connection)
    {
        parent::__construct();
        $this->connection = $connection;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Execute the raw SQL query
        $sql = 'SELECT group_name, AVG(assessment_score) AS average_score_by_group
        FROM student_performance
        GROUP BY group_name';

        $rows = $this->connection->executeQuery($sql)->fetchAllAssociative();

        // Output the results
        $output->writeln("Find average assessment by group");
        foreach ($rows as $row) {
            $output->writeln(sprintf('%s: %s', $row['group_name'], $row['average_score_by_group']));
        }

        $io = new SymfonyStyle($input, $output);
        $io->success('DONE');
        return Command::SUCCESS;
    }
}

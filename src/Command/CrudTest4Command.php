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
    name: 'crud:test4',
    description: 'Find the score above which the academic performance of 70% of students is assessed by group',
)]
class CrudTest4Command extends Command
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
        $sql = 'SELECT sp1.group_name, MAX(sp1.assessment_score) AS score_70_percent_by_group
        FROM student_performance sp1
        WHERE (
            SELECT COUNT(DISTINCT sp2.assessment_score)
            FROM student_performance sp2
            WHERE sp2.group_name = sp1.group_name AND sp2.assessment_score <= sp1.assessment_score
        ) >= 0.7 * (
            SELECT COUNT(DISTINCT sp3.assessment_score)
            FROM student_performance sp3
            WHERE sp3.group_name = sp1.group_name
        )
        GROUP BY sp1.group_name;';

        $rows = $this->connection->executeQuery($sql)->fetchAllAssociative();

        // Output the results
        $output->writeln("Find the score above which the academic performance of 70% of students is assessed by group");
        foreach ($rows as $row) {
            $output->writeln(sprintf('%s: %s', $row['group_name'], $row['score_70_percent_by_group']));
        }

        $io = new SymfonyStyle($input, $output);
        $io->success('DONE');
        return Command::SUCCESS;
    }
}

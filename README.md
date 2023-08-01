# PHP_TASK

# CREATE THE MYSQL SERVER AND TABLES/DATA

## Linux: Install mysql-server.
sudo apt install mysql-server

## Linux: Secure the installation.
sudo mysql_secure_installation

## Start the mysql console.
sudo mysql -u root -p

## Create the database.
CREATE DATABASE IF NOT EXISTS STUDENTDB;

## Make sure that the database is there.
SHOW DATABASES;

## Create a new user.
CREATE USER 'user'@'localhost' IDENTIFIED BY 'shouldHaveUsedABetterPassword42';
GRANT ALL PRIVILEGES ON `STUDENTDB`.* TO `user`@`localhost` 
FLUSH PRIVILEGES;

## Use the database.
use STUDENTDB;

## Create the table: student_performance.
CREATE TABLE IF NOT EXISTS student_performance (
    id INT PRIMARY KEY,
    name VARCHAR(50),
    group_name VARCHAR(50),
    subject VARCHAR(50),
    date DATE,
    assessment_score INT
);

## Insert test data.
INSERT INTO student_performance (id, name, group_name, subject, date, assessment_score)
VALUES
    (1 , 'John Doe'   , 'Group A', 'Math'      , '2023-07-01', 8),
    (2 , 'Jane Doe'   , 'Group B', 'Science'   , '2023-07-03', 7),
    (3 , 'Bob Doe'    , 'Group A', 'Technology', '2023-07-03', 9),
    (4 , 'Alice Doe'  , 'Group B', 'Music'     , '2023-07-04', 1),
    (5 , 'Mike Doe'   , 'Group A', 'Technology', '2023-07-04', 2),
    (6 , 'Tom Doe'    , 'Group B', 'Science'   , '2023-07-04', 3),
    (7 , 'Brad Doe'   , 'Group A', 'Technology', '2023-07-05', 4),
    (8 , 'Josh Doe'   , 'Group B', 'Music'     , '2023-07-08', 5),
    (9 , 'Paul Doe'   , 'Group A', 'Math'      , '2023-07-08', 6),

    (10, 'John Smith' , 'Group C', 'Math'      , '2023-08-01', 8),
    (11, 'Jane Smith' , 'Group D', 'Science'   , '2023-08-03', 7),
    (12, 'Bob Smith'  , 'Group C', 'Technology', '2023-08-03', 9),
    (13, 'Alice Smith', 'Group D', 'Music'     , '2023-08-04', 1),
    (14, 'Mike Smith' , 'Group C', 'Technology', '2023-08-04', 2),
    (15, 'Tom Smith'  , 'Group D', 'Science'   , '2023-08-04', 3),
    (16, 'Brad Smith' , 'Group C', 'Technology', '2023-08-05', 4),
    (17, 'Josh Smith' , 'Group D', 'Music'     , '2023-08-08', 5),
    (18, 'Paul Smith' , 'Group C', 'Math'      , '2023-08-08', 6),
    (19, 'John Smith' , 'Group D', 'Music'     , '2023-08-08', 8)
    ;

## Make sure that the data is there.
SELECT * FROM student_performance;

## QUERIES.
-- 1. Find average assessment by group
SELECT group_name, AVG(assessment_score) AS average_score_by_group
FROM student_performance
GROUP BY group_name;

-- 2. Find average assessment by subject
SELECT subject, AVG(assessment_score) AS average_score_by_subject
FROM student_performance
GROUP BY subject;

-- 3. Find average assessment by group and subject
SELECT group_name, subject, AVG(assessment_score) AS average_score_by_group_subject
FROM student_performance
GROUP BY group_name, subject;

-- 4. Find the score above which the academic performance of 70% of students is assessed by group
SELECT sp1.group_name, MAX(sp1.assessment_score) AS score_70_percent_by_group
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
GROUP BY sp1.group_name;

-- 5. Find the score above which the academic performance of 70% of students is assessed by subject
SELECT sp1.subject, MAX(sp1.assessment_score) AS score_70_percent_by_subject
FROM student_performance sp1
WHERE (
    SELECT COUNT(DISTINCT sp2.assessment_score)
    FROM student_performance sp2
    WHERE sp2.subject = sp1.subject AND sp2.assessment_score <= sp1.assessment_score
) >= 0.7 * (
    SELECT COUNT(DISTINCT sp3.assessment_score)
    FROM student_performance sp3
    WHERE sp3.subject = sp1.subject
)
GROUP BY sp1.subject;

-- 6. Find the score above which the academic performance of 70% of students is assessed by group and subject
SELECT sp1.group_name, sp1.subject, MAX(sp1.assessment_score) AS score_70_percent_by_group_subject
FROM student_performance sp1
WHERE (
    SELECT COUNT(DISTINCT sp2.assessment_score)
    FROM student_performance sp2
    WHERE sp2.group_name = sp1.group_name AND sp2.subject = sp1.subject AND sp2.assessment_score <= sp1.assessment_score
) >= 0.7 * (
    SELECT COUNT(DISTINCT sp3.assessment_score)
    FROM student_performance sp3
    WHERE sp3.group_name = sp1.group_name AND sp3.subject = sp1.subject
)
GROUP BY sp1.group_name, sp1.subject;

# CREATE THE SYMFONY APP

## Install the Symfony CLI
wget https://get.symfony.com/cli/installer -O - | bash

## Create the new Symfony app and change to the new app's directory.
symfony new student_performance_app
cd student_performance_app

## Edit the .env file for the database configuration.
DATABASE_URL=mysql://user:shouldHaveUsedABetterPassword42@localhost:3306/STUDENTDB

## Install packages.
composer require annotations doctrine orm symfony/orm-pack symfony/console

## Install the maker-bundle.
composer require symfony/maker-bundle --dev

## Make sure that the database can be reached by the app.
php bin/console doctrine:query:sql "SELECT VERSION()"

## Generate Entity classes for the tables of the database.
bin/console doctrine:mapping:convert annotation ./src/Entity --from-database --force

## Create a repository for the table: StudentPerformance.
bin/console make:repository StudentPerformance


## Create CRUD commands. (located here: src/Command/)

## 1. Find average assessment by group
symfony console make:command crud:test1

## 2. Find average assessment by subject
symfony console make:command crud:test2

## 3. Find average assessment by group and subject
symfony console make:command crud:test3

## 4. Find the score above which the academic performance of 70% of students is assessed by group
symfony console make:command crud:test4

## 5. Find the score above which the academic performance of 70% of students is assessed by subject
symfony console make:command crud:test5

## 6. Find the score above which the academic performance of 70% of students is assessed by group and subject
symfony console make:command crud:test6

## NOTE:
For each command:
Add these to bottom of the default use statements: 
use Doctrine\DBAL\Connection;
Edit the "description" under #[AsCommand.
Clear the contents of the class and replace with this:
    private $connection;

    public function __construct(Connection $connection)
    {
        parent::__construct();
        $this->connection = $connection;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Execute the raw SQL query (CUSTOMIZE THIS FOR EACH CRUD COMMAND.)
        $sql = 'SELECT group_name, AVG(assessment_score) AS average_score_by_group
        FROM student_performance
        GROUP BY group_name';

        $rows = $this->connection->executeQuery($sql)->fetchAllAssociative();

        // Output the results
        foreach ($rows as $row) {
            $output->writeln(sprintf('%s: %s', $row['group_name'], $row['average_score_by_group']));
        }

        $io = new SymfonyStyle($input, $output);
        $io->success('DONE');
        return Command::SUCCESS;
    }

## Run CRUD commands.

## 1. Find average assessment by group
symfony console crud:test1

## 2. Find average assessment by subject
symfony console crud:test2

## 3. Find average assessment by group and subject
symfony console crud:test3

## 4. Find the score above which the academic performance of 70% of students is assessed by group
symfony console crud:test4

## 5. Find the score above which the academic performance of 70% of students is assessed by subject
symfony console crud:test5

## 6. Find the score above which the academic performance of 70% of students is assessed by group and subject
symfony console crud:test6

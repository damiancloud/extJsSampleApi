<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231211223724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sample_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE history (id INT NOT NULL, sample_id INT NOT NULL, status VARCHAR(255) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_27BA704B1B1FEA20 ON history (sample_id)');
        $this->addSql('CREATE TABLE sample (id INT NOT NULL, name VARCHAR(255) NOT NULL, date_created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_last TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704B1B1FEA20 FOREIGN KEY (sample_id) REFERENCES sample (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $sampleData = [
            [
                'ID' => 254,
                'Name' => 'Sample 1',
                'DateCreated' => '2020-12-01',
                'Status' => 'Status three',
                'DateLast' => '2020-12-04T13:15:10',
                'History' => [
                    ['ID' => 123, 'DocID' => 254, 'Status' => 'Status one', 'Date' => '2020-12-01T11:00:00'],
                    ['ID' => 124, 'DocID' => 254, 'Status' => 'Status two', 'Date' => '2020-12-02T12:00:00'],
                    ['ID' => 125, 'DocID' => 254, 'Status' => 'Status three', 'Date' => '2020-12-04T13:15:10'],
                ],
            ],
            [
                'ID' => 255,
                'Name' => 'Sample 2',
                'DateCreated' => '2020-12-02',
                'Status' => 'Status one',
                'DateLast' => '2020-12-02T12:20:20',
                'History' => [
                    ['ID' => 126, 'DocID' => 255, 'Status' => 'Status one', 'Date' => '2020-12-02T12:20:20'],
                ],
            ],
        ];
    
        foreach ($sampleData as $sample) {
            $this->addSql('INSERT INTO sample (id, name, date_created, status, date_last) VALUES (?, ?, ?, ?, ?)', [
                $sample['ID'],
                $sample['Name'],
                $sample['DateCreated'],
                $sample['Status'],
                $sample['DateLast'],
            ]);
    
            foreach ($sample['History'] as $history) {
                $this->addSql('INSERT INTO history (id, sample_id, status, date) VALUES (?, ?, ?, ?)', [
                    $history['ID'],
                    $sample['ID'],
                    $history['Status'],
                    $history['Date'],
                ]);
            }
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE history_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sample_id_seq CASCADE');
        $this->addSql('ALTER TABLE history DROP CONSTRAINT FK_27BA704B1B1FEA20');
        $this->addSql('DROP TABLE history');
        $this->addSql('DROP TABLE sample');
    }
}

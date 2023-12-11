<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231211011844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704B1B1FEA20 FOREIGN KEY (sample_id) REFERENCES sample (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_27BA704B1B1FEA20 ON history (sample_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE history DROP CONSTRAINT FK_27BA704B1B1FEA20');
        $this->addSql('DROP INDEX IDX_27BA704B1B1FEA20');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210112125801 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE type_company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adherent ADD type_company_id INT DEFAULT NULL, ADD logo VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F0608A98CCF3 FOREIGN KEY (type_company_id) REFERENCES type_company (id)');
        $this->addSql('CREATE INDEX IDX_90D3F0608A98CCF3 ON adherent (type_company_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F0608A98CCF3');
        $this->addSql('DROP TABLE type_company');
        $this->addSql('DROP INDEX IDX_90D3F0608A98CCF3 ON adherent');
        $this->addSql('ALTER TABLE adherent DROP type_company_id, DROP logo');
    }
}

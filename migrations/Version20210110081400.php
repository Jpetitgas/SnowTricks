<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210110081400 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE figure DROP FOREIGN KEY FK_2F57B37AEE45BDBF');
        $this->addSql('DROP INDEX IDX_2F57B37AEE45BDBF ON figure');
        $this->addSql('ALTER TABLE figure DROP picture_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE figure ADD picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE figure ADD CONSTRAINT FK_2F57B37AEE45BDBF FOREIGN KEY (picture_id) REFERENCES image (id)');
        $this->addSql('CREATE INDEX IDX_2F57B37AEE45BDBF ON figure (picture_id)');
    }
}

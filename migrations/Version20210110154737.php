<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210110154737 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE portait_id portrait_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491226EBF3 FOREIGN KEY (portrait_id) REFERENCES portrait (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6491226EBF3 ON user (portrait_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491226EBF3');
        $this->addSql('DROP INDEX UNIQ_8D93D6491226EBF3 ON user');
        $this->addSql('ALTER TABLE user CHANGE portrait_id portait_id INT NOT NULL');
    }
}

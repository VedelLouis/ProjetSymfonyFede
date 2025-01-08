<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108151820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact_group (contact_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_40EA54CAE7A1254A (contact_id), INDEX IDX_40EA54CAFE54D947 (group_id), PRIMARY KEY(contact_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact_group ADD CONSTRAINT FK_40EA54CAE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contact_group ADD CONSTRAINT FK_40EA54CAFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact_group DROP FOREIGN KEY FK_40EA54CAE7A1254A');
        $this->addSql('ALTER TABLE contact_group DROP FOREIGN KEY FK_40EA54CAFE54D947');
        $this->addSql('DROP TABLE contact_group');
    }
}

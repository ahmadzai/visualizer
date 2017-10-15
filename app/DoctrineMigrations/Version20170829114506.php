<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170829114506 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE admin_data CHANGE cluster_name cluster_type TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD province INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494ADAD40B FOREIGN KEY (province) REFERENCES province (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6494ADAD40B ON user (province)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE admin_data CHANGE cluster_type cluster_name TEXT DEFAULT NULL COLLATE utf8mb4_general_ci');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494ADAD40B');
        $this->addSql('DROP INDEX IDX_8D93D6494ADAD40B ON user');
        $this->addSql('ALTER TABLE user DROP province');
    }
}

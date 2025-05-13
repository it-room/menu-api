<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250420173108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE ingrediant (id SERIAL NOT NULL, menu_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6CA6D0ACCCD7E912 ON ingrediant (menu_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingrediant ADD CONSTRAINT FK_6CA6D0ACCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ingrediant DROP CONSTRAINT FK_6CA6D0ACCCD7E912
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ingrediant
        SQL);
    }
}

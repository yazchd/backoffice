<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250424132926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE store (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, site_url VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category ADD store_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category ADD CONSTRAINT FK_64C19C1B092A811 FOREIGN KEY (store_id) REFERENCES store (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_64C19C1B092A811 ON category (store_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD store_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD CONSTRAINT FK_D34A04ADB092A811 FOREIGN KEY (store_id) REFERENCES store (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D34A04ADB092A811 ON product (store_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE category DROP FOREIGN KEY FK_64C19C1B092A811
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADB092A811
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE store
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D34A04ADB092A811 ON product
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP store_id
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_64C19C1B092A811 ON category
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category DROP store_id
        SQL);
    }
}

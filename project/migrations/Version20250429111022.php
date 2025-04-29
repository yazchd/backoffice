<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */

final class Version20250429111022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $insertStmt =  <<<SQL
        INSERT INTO user (username,roles, password, email)
        VALUES (?, ?, ?, ?)
        SQL;
        $this->connection->executeQuery($insertStmt,['Yazid','[]', '$2y$13$.fy1clANUAuFnxJIseMy5ubI4IfAVpByhPxABycbWldJt34l3vAZS', 'yazidterfas@yahoo.com']);
    }

    public function down(Schema $schema): void
    {
        
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250325145847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        // $this->addSql('ALTER TABLE appointment ALTER first_name SET NOT NULL');
        // $this->addSql('ALTER TABLE appointment ALTER last_name SET NOT NULL');
        // $this->addSql('ALTER TABLE appointment ALTER email SET NOT NULL');
        // $this->addSql('ALTER TABLE appointment ALTER duration_minutes SET NOT NULL');
        // $this->addSql('ALTER TABLE appointment_service ALTER price SET NOT NULL');
        // $this->addSql('ALTER TABLE service ALTER duration_minute DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('ALTER TABLE appointment ALTER first_name DROP NOT NULL');
        $this->addSql('ALTER TABLE appointment ALTER last_name DROP NOT NULL');
        $this->addSql('ALTER TABLE appointment ALTER email DROP NOT NULL');
        $this->addSql('ALTER TABLE appointment ALTER duration_minutes DROP NOT NULL');
        $this->addSql('ALTER TABLE service ALTER duration_minute SET NOT NULL');
        $this->addSql('ALTER TABLE appointment_service ALTER price DROP NOT NULL');
    }
}

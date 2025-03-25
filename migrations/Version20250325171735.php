<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250325171735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE appointment ALTER first_name SET NOT NULL');
        // $this->addSql('ALTER TABLE appointment ALTER last_name SET NOT NULL');
        // $this->addSql('ALTER TABLE appointment ALTER email SET NOT NULL');
        // $this->addSql('ALTER TABLE appointment ALTER duration_minutes SET NOT NULL');
        // $this->addSql('ALTER TABLE appointment_service ALTER price SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER password DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE appointment ALTER first_name DROP NOT NULL');
        $this->addSql('ALTER TABLE appointment ALTER last_name DROP NOT NULL');
        $this->addSql('ALTER TABLE appointment ALTER email DROP NOT NULL');
        $this->addSql('ALTER TABLE appointment ALTER duration_minutes DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER password SET NOT NULL');
        $this->addSql('ALTER TABLE appointment_service ALTER price DROP NOT NULL');
    }
}

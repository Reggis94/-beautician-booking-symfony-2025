<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250329113544 extends AbstractMigration
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
        $this->addSql('ALTER TABLE category ADD business_id INT NOT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1A89DB457 FOREIGN KEY (business_id) REFERENCES business (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_64C19C1A89DB457 ON category (business_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE appointment_service ALTER price DROP NOT NULL');
        $this->addSql('ALTER TABLE category DROP CONSTRAINT FK_64C19C1A89DB457');
        $this->addSql('DROP INDEX IDX_64C19C1A89DB457');
        $this->addSql('ALTER TABLE category DROP business_id');
        $this->addSql('ALTER TABLE appointment ALTER first_name DROP NOT NULL');
        $this->addSql('ALTER TABLE appointment ALTER last_name DROP NOT NULL');
        $this->addSql('ALTER TABLE appointment ALTER email DROP NOT NULL');
        $this->addSql('ALTER TABLE appointment ALTER duration_minutes DROP NOT NULL');
    }
}

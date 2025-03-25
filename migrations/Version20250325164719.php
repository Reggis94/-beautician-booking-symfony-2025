<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250325164719 extends AbstractMigration
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
        $this->addSql('ALTER TABLE business ADD business_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE business ADD CONSTRAINT FK_8D36E38FA5D68D8 FOREIGN KEY (business_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D36E38FA5D68D8 ON business (business_user_id)');
        $this->addSql('ALTER TABLE service ALTER duration_minute DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE service ALTER duration_minute SET NOT NULL');
        $this->addSql('ALTER TABLE appointment_service ALTER price DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP created_at');
        $this->addSql('ALTER TABLE business DROP CONSTRAINT FK_8D36E38FA5D68D8');
        $this->addSql('DROP INDEX IDX_8D36E38FA5D68D8');
        $this->addSql('ALTER TABLE business DROP business_user_id');
        $this->addSql('ALTER TABLE appointment ALTER first_name DROP NOT NULL');
        $this->addSql('ALTER TABLE appointment ALTER last_name DROP NOT NULL');
        $this->addSql('ALTER TABLE appointment ALTER email DROP NOT NULL');
        $this->addSql('ALTER TABLE appointment ALTER duration_minutes DROP NOT NULL');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250329111030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN category.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN category.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE extra (id SERIAL NOT NULL, service_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, duration_minutes INT DEFAULT NULL, price INT DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4D3F0D65ED5CA9E6 ON extra (service_id)');
        $this->addSql('COMMENT ON COLUMN extra.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN extra.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE extra ADD CONSTRAINT FK_4D3F0D65ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        // $this->addSql('ALTER TABLE appointment ALTER first_name SET NOT NULL');
        // $this->addSql('ALTER TABLE appointment ALTER last_name SET NOT NULL');
        // $this->addSql('ALTER TABLE appointment ALTER email SET NOT NULL');
        // $this->addSql('ALTER TABLE appointment ALTER duration_minutes SET NOT NULL');
        // $this->addSql('ALTER TABLE appointment_service ALTER price SET NOT NULL');
        $this->addSql('ALTER TABLE service ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD212469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E19D9AD212469DE2 ON service (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE service DROP CONSTRAINT FK_E19D9AD212469DE2');
        $this->addSql('ALTER TABLE extra DROP CONSTRAINT FK_4D3F0D65ED5CA9E6');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE extra');
        $this->addSql('DROP INDEX IDX_E19D9AD212469DE2');
        $this->addSql('ALTER TABLE service DROP category_id');
        $this->addSql('ALTER TABLE appointment ALTER first_name DROP NOT NULL');
        $this->addSql('ALTER TABLE appointment ALTER last_name DROP NOT NULL');
        $this->addSql('ALTER TABLE appointment ALTER email DROP NOT NULL');
        $this->addSql('ALTER TABLE appointment ALTER duration_minutes DROP NOT NULL');
        $this->addSql('ALTER TABLE appointment_service ALTER price DROP NOT NULL');
    }
}

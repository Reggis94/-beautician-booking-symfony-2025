<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250310155124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_service (id SERIAL NOT NULL, business_id INT DEFAULT NULL, original_id_id INT DEFAULT NULL, name VARCHAR(150) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2645DAACA89DB457 ON category_service (business_id)');
        $this->addSql('CREATE INDEX IDX_2645DAACB0F47EA5 ON category_service (original_id_id)');
        $this->addSql('COMMENT ON COLUMN category_service.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN category_service.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE category_service ADD CONSTRAINT FK_2645DAACA89DB457 FOREIGN KEY (business_id) REFERENCES business (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category_service ADD CONSTRAINT FK_2645DAACB0F47EA5 FOREIGN KEY (original_id_id) REFERENCES category_service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        // $this->addSql('CREATE SEQUENCE appointment_id_seq');
        $this->addSql('SELECT setval(\'appointment_id_seq\', (SELECT MAX(id) FROM appointment))');
        $this->addSql('ALTER TABLE appointment ALTER id SET DEFAULT nextval(\'appointment_id_seq\')');
        // $this->addSql('CREATE SEQUENCE appointment_service_id_seq');
        $this->addSql('SELECT setval(\'appointment_service_id_seq\', (SELECT MAX(id) FROM appointment_service))');
        $this->addSql('ALTER TABLE appointment_service ALTER id SET DEFAULT nextval(\'appointment_service_id_seq\')');
        // $this->addSql('CREATE SEQUENCE availability_id_seq');
        $this->addSql('SELECT setval(\'availability_id_seq\', (SELECT MAX(id) FROM availability))');
        $this->addSql('ALTER TABLE availability ALTER id SET DEFAULT nextval(\'availability_id_seq\')');
        // $this->addSql('CREATE SEQUENCE day_id_seq');
        $this->addSql('SELECT setval(\'day_id_seq\', (SELECT MAX(id) FROM day))');
        $this->addSql('ALTER TABLE day ALTER id SET DEFAULT nextval(\'day_id_seq\')');
        // $this->addSql('CREATE SEQUENCE invoice_id_seq');
        $this->addSql('SELECT setval(\'invoice_id_seq\', (SELECT MAX(id) FROM invoice))');
        $this->addSql('ALTER TABLE invoice ALTER id SET DEFAULT nextval(\'invoice_id_seq\')');
        // $this->addSql('CREATE SEQUENCE service_id_seq');
        $this->addSql('SELECT setval(\'service_id_seq\', (SELECT MAX(id) FROM service))');
        $this->addSql('ALTER TABLE service ALTER id SET DEFAULT nextval(\'service_id_seq\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE category_service DROP CONSTRAINT FK_2645DAACA89DB457');
        $this->addSql('ALTER TABLE category_service DROP CONSTRAINT FK_2645DAACB0F47EA5');
        $this->addSql('DROP TABLE category_service');
        $this->addSql('ALTER TABLE availability ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE invoice ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE day ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE appointment_service ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE service ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE appointment ALTER id DROP DEFAULT');
    }
}

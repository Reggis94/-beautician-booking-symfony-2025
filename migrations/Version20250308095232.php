<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250308095232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE appointment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE appointment_service_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE availability_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE business_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE day_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE invoice_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE service_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE appointment (id INT NOT NULL, start_date_time_utc TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date_time_utc TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, timezone VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, phone_country_code VARCHAR(7) DEFAULT NULL, phone_number VARCHAR(20) DEFAULT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, email VARCHAR(100) NOT NULL, duration_minutes INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN appointment.start_date_time_utc IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN appointment.end_date_time_utc IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE appointment_service (id INT NOT NULL, appointment_id INT NOT NULL, service_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_70BEA8FAE5B533F9 ON appointment_service (appointment_id)');
        $this->addSql('CREATE INDEX IDX_70BEA8FAED5CA9E6 ON appointment_service (service_id)');
        $this->addSql('CREATE TABLE availability (id INT NOT NULL, business_id INT NOT NULL, day_id INT DEFAULT NULL, start_time TIME(0) WITHOUT TIME ZONE NOT NULL, end_time TIME(0) WITHOUT TIME ZONE NOT NULL, interval_minutes INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3FB7A2BFA89DB457 ON availability (business_id)');
        $this->addSql('CREATE INDEX IDX_3FB7A2BF9C24126 ON availability (day_id)');
        $this->addSql('COMMENT ON COLUMN availability.start_time IS \'(DC2Type:time_immutable)\'');
        $this->addSql('COMMENT ON COLUMN availability.end_time IS \'(DC2Type:time_immutable)\'');
        $this->addSql('CREATE TABLE business (id INT NOT NULL, name VARCHAR(80) NOT NULL, username VARCHAR(40) NOT NULL, firstname VARCHAR(40) DEFAULT NULL, lastname VARCHAR(40) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, timezone_name VARCHAR(150) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE day (id INT NOT NULL, name VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE invoice (id INT NOT NULL, appointment_id INT NOT NULL, client_booking_fees DOUBLE PRECISION NOT NULL, payment_processing_fees DOUBLE PRECISION DEFAULT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_90651744E5B533F9 ON invoice (appointment_id)');
        $this->addSql('CREATE TABLE service (id INT NOT NULL, business_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, duration_minute INT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, is_active BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E19D9AD2A89DB457 ON service (business_id)');
        $this->addSql('COMMENT ON COLUMN service.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN service.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE appointment_service ADD CONSTRAINT FK_70BEA8FAE5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appointment_service ADD CONSTRAINT FK_70BEA8FAED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE availability ADD CONSTRAINT FK_3FB7A2BFA89DB457 FOREIGN KEY (business_id) REFERENCES business (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE availability ADD CONSTRAINT FK_3FB7A2BF9C24126 FOREIGN KEY (day_id) REFERENCES day (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744E5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2A89DB457 FOREIGN KEY (business_id) REFERENCES business (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE appointment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE appointment_service_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE availability_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE business_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE day_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE invoice_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE service_id_seq CASCADE');
        $this->addSql('ALTER TABLE appointment_service DROP CONSTRAINT FK_70BEA8FAE5B533F9');
        $this->addSql('ALTER TABLE appointment_service DROP CONSTRAINT FK_70BEA8FAED5CA9E6');
        $this->addSql('ALTER TABLE availability DROP CONSTRAINT FK_3FB7A2BFA89DB457');
        $this->addSql('ALTER TABLE availability DROP CONSTRAINT FK_3FB7A2BF9C24126');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_90651744E5B533F9');
        $this->addSql('ALTER TABLE service DROP CONSTRAINT FK_E19D9AD2A89DB457');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE appointment_service');
        $this->addSql('DROP TABLE availability');
        $this->addSql('DROP TABLE business');
        $this->addSql('DROP TABLE day');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE service');
    }
}

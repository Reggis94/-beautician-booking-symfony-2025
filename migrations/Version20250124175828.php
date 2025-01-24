<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250124175828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appointment_service (id INT AUTO_INCREMENT NOT NULL, appointment_id INT NOT NULL, service_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_70BEA8FAE5B533F9 (appointment_id), INDEX IDX_70BEA8FAED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appointment_service ADD CONSTRAINT FK_70BEA8FAE5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id)');
        $this->addSql('ALTER TABLE appointment_service ADD CONSTRAINT FK_70BEA8FAED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment_service DROP FOREIGN KEY FK_70BEA8FAE5B533F9');
        $this->addSql('ALTER TABLE appointment_service DROP FOREIGN KEY FK_70BEA8FAED5CA9E6');
        $this->addSql('DROP TABLE appointment_service');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250123184516 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE availability (id INT AUTO_INCREMENT NOT NULL, business_id INT NOT NULL, day_id INT DEFAULT NULL, start_time TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', end_time TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', interval_minutes INT DEFAULT NULL, INDEX IDX_3FB7A2BFA89DB457 (business_id), INDEX IDX_3FB7A2BF9C24126 (day_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE availability ADD CONSTRAINT FK_3FB7A2BFA89DB457 FOREIGN KEY (business_id) REFERENCES business (id)');
        $this->addSql('ALTER TABLE availability ADD CONSTRAINT FK_3FB7A2BF9C24126 FOREIGN KEY (day_id) REFERENCES day (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE availability DROP FOREIGN KEY FK_3FB7A2BFA89DB457');
        $this->addSql('ALTER TABLE availability DROP FOREIGN KEY FK_3FB7A2BF9C24126');
        $this->addSql('DROP TABLE availability');
    }
}

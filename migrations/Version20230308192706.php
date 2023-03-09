<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308192706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE athlete_titles_won (athlete_id INT NOT NULL, titles_won_id INT NOT NULL, INDEX IDX_1BB99625FE6BCB8B (athlete_id), INDEX IDX_1BB99625EBE849B7 (titles_won_id), PRIMARY KEY(athlete_id, titles_won_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE titles_won (id INT AUTO_INCREMENT NOT NULL, titles VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE athlete_titles_won ADD CONSTRAINT FK_1BB99625FE6BCB8B FOREIGN KEY (athlete_id) REFERENCES athlete (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE athlete_titles_won ADD CONSTRAINT FK_1BB99625EBE849B7 FOREIGN KEY (titles_won_id) REFERENCES titles_won (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE athlete_titles_won DROP FOREIGN KEY FK_1BB99625FE6BCB8B');
        $this->addSql('ALTER TABLE athlete_titles_won DROP FOREIGN KEY FK_1BB99625EBE849B7');
        $this->addSql('DROP TABLE athlete_titles_won');
        $this->addSql('DROP TABLE titles_won');
    }
}

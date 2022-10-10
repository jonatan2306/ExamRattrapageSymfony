<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221007113949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album ADD image VARCHAR(255) DEFAULT NULL, ADD published_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE song DROP FOREIGN KEY FK_33EDEEA11137ABCF');
        $this->addSql('DROP INDEX IDX_33EDEEA11137ABCF ON song');
        $this->addSql('ALTER TABLE song DROP album_id, CHANGE duration duration INT DEFAULT NULL, CHANGE name title VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album DROP image, DROP published_at');
        $this->addSql('ALTER TABLE song ADD album_id INT DEFAULT NULL, CHANGE duration duration INT NOT NULL, CHANGE title name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE song ADD CONSTRAINT FK_33EDEEA11137ABCF FOREIGN KEY (album_id) REFERENCES album (id)');
        $this->addSql('CREATE INDEX IDX_33EDEEA11137ABCF ON song (album_id)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210115093252 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE migration_versions');
        $this->addSql('ALTER TABLE task ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_527EDB25A76ED395 ON task (user_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL, CHANGE username username VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE migration_versions (version VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, PRIMARY KEY(version)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25A76ED395');
        $this->addSql('DROP INDEX UNIQ_527EDB25A76ED395 ON task');
        $this->addSql('ALTER TABLE task DROP user_id');
        $this->addSql('ALTER TABLE user DROP roles, CHANGE email email VARCHAR(60) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE password password VARCHAR(64) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE username username VARCHAR(25) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }
}

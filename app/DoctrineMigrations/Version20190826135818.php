<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190826135818 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE gems (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, info LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_D0F62C015E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jewelleries (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(10, 2) NOT NULL, image LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jewellery_gem (jewellery_id INT NOT NULL, gem_id INT NOT NULL, INDEX IDX_C834A38251C6F61 (jewellery_id), INDEX IDX_C834A382A5AD5580 (gem_id), PRIMARY KEY(jewellery_id, gem_id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jewellery_gem ADD CONSTRAINT FK_C834A38251C6F61 FOREIGN KEY (jewellery_id) REFERENCES jewelleries (id)');
        $this->addSql('ALTER TABLE jewellery_gem ADD CONSTRAINT FK_C834A382A5AD5580 FOREIGN KEY (gem_id) REFERENCES gems (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jewellery_gem DROP FOREIGN KEY FK_C834A382A5AD5580');
        $this->addSql('ALTER TABLE jewellery_gem DROP FOREIGN KEY FK_C834A38251C6F61');
        $this->addSql('DROP TABLE gems');
        $this->addSql('DROP TABLE jewelleries');
        $this->addSql('DROP TABLE jewellery_gem');
    }
}

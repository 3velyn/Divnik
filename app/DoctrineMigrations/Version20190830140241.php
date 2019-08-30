<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190830140241 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shipments ADD user_id INT DEFAULT NULL, ADD total_price NUMERIC(10, 2) NOT NULL, ADD jewellery_id INT NOT NULL, ADD quantity INT NOT NULL, ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE shipments ADD CONSTRAINT FK_94699AD4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_94699AD4A76ED395 ON shipments (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shipments DROP FOREIGN KEY FK_94699AD4A76ED395');
        $this->addSql('DROP INDEX IDX_94699AD4A76ED395 ON shipments');
        $this->addSql('ALTER TABLE shipments DROP user_id, DROP total_price, DROP jewellery_id, DROP quantity, DROP name');
    }
}

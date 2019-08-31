<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190830230646 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shipments_items CHANGE shipment_id shipment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shipments_items ADD CONSTRAINT FK_C50BCCFC7BE036FC FOREIGN KEY (shipment_id) REFERENCES shipments (id)');
        $this->addSql('CREATE INDEX IDX_C50BCCFC7BE036FC ON shipments_items (shipment_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shipments_items DROP FOREIGN KEY FK_C50BCCFC7BE036FC');
        $this->addSql('DROP INDEX IDX_C50BCCFC7BE036FC ON shipments_items');
        $this->addSql('ALTER TABLE shipments_items CHANGE shipment_id shipment_id INT NOT NULL');
    }
}

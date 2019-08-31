<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190831125602 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shipments_items CHANGE item_id item_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shipments_items ADD CONSTRAINT FK_C50BCCFC126F525E FOREIGN KEY (item_id) REFERENCES jewelleries (id)');
        $this->addSql('CREATE INDEX IDX_C50BCCFC126F525E ON shipments_items (item_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shipments_items DROP FOREIGN KEY FK_C50BCCFC126F525E');
        $this->addSql('DROP INDEX IDX_C50BCCFC126F525E ON shipments_items');
        $this->addSql('ALTER TABLE shipments_items CHANGE item_id item_id INT NOT NULL');
    }
}

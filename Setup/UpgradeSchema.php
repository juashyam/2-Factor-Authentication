<?php
/**
 * Upgrade schema of admin_user table
 *
 * @author     Shyam Kumar <kumar.30.shyam@gmail.com>
 */
namespace Neyamtux\Authenticator\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $tableAdmins = $installer->getTable('admin_user');

        if (version_compare($context->getVersion(), '2.0.0') < 0) {
            // Check if the table already exists
            if ($installer->getConnection()->isTableExists($tableAdmins) == true) {
                // Declare data
                $columns = [
                    'secret_key' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => false,
                        'comment' => 'Secret Key',
                    ],
                ];

                $connection = $installer->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableAdmins, $name, $definition);
                }
            }
        }
        $installer->endSetup();
    }
}

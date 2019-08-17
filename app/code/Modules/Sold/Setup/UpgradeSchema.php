<?php

namespace Modules\Sold\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    )
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $tableName = $setup->getTable('catalog_product_entity');
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $columns = [
                    'sold_quantity' => [
                        'type' => Table::TYPE_INTEGER,
                        'nullable' => true,
                        'comment' => 'Ordered quantity',
                    ],
                ];
                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }
            }
        }
        $setup->endSetup();
    }
}

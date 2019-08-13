<?php namespace Modules\CustomShippingAPI\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (version_compare($context->getVersion(), '0.0.1', '<')) {
            if (!$installer->tableExists('custom_shipping_method_orders')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('custom_shipping_method_orders')
                )
                    ->addColumn(
                        'id',
                        Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary' => true,
                            'unsigned' => true,
                        ],
                        'Order ID'
                    )
                    ->addColumn(
                        'order_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['nullable => false'],
                        'Order id real'
                    )
                    ->addColumn(
                        'order_id_response',
                        Table::TYPE_INTEGER,
                        null,
                        ['nullable => false'],
                        'Order id from api response'
                    )
                    ->setComment('Custom Shipping method orders');
                $installer->getConnection()->createTable($table);
            }
        }
        $installer->endSetup();
    }
}

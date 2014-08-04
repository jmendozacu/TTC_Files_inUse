<?php
/**
 * Cookie Ninja
 *
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     CookieNinja
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_CookieNinja_Model_Resource_Ninja */
$installer = $this;
$installer->startSetup();


$installer->getConnection()->beginTransaction();
try {
    $storeIds = Mage::getModel('core/store')
        ->getCollection()
        ->addFieldToFilter('code', array('neq' => Mage_Core_Model_Store::ADMIN_CODE))
        ->getColumnValues('store_id');

    $rewriteRedirectSelect = $installer->getConnection()->select()
        ->from(array('r' => $installer->getTable('enterprise_urlrewrite/redirect')), array('r.redirect_id'))
        ->where("r.identifier LIKE {$installer->getConnection()->quote('%tgc/professors/professor_details.aspx%')}");

    $installer->getConnection()->delete(
        $installer->getTable('enterprise_urlrewrite/redirect_rewrite'),
        "redirect_id IN (".((string)$rewriteRedirectSelect).")"
    );

    $installer->getConnection()->delete(
        $installer->getTable('enterprise_urlrewrite/redirect'),
        "identifier LIKE {$installer->getConnection()->quote('%tgc/professors/professor_details.aspx%')}"
    );

    $installer->getConnection()->delete(
        $installer->getTable('core/url_rewrite'),
        "id_path LIKE {$installer->getConnection()->quote('%tgc/professors/professor_details.aspx%')}"
    );


    $datas = array();
    foreach ($storeIds as $storeId) {
        $datas[] = array(
            'identifier'  => 'tgc/professors/professor_detail.aspx',
            'target_path' => 'professors/professor/view',
            'options'     => 'RP',
            'description' => 'Redirect legacy professor detail page to new professor detail page',
            'store_id'    => $storeId,
        );
    }

    foreach ($datas as $data) {

        $redirect = Mage::getModel('enterprise_urlrewrite/redirect');
        $redirect->addData($data);
        $redirect->setIdPath($data['identifier'] . '_' . $data['store_id']);
        $redirect->save();

        $rewrite = Mage::getModel('core/url_rewrite');
        $rewrite->setIsSystem(0)
            ->setStoreId($data['store_id'])
            ->setOptions('RP')
            ->setTargetPath($data['target_path'])
            ->setRequestPath($data['identifier'])
            ->setIdentifier($data['identifier'])
            ->setIdPath($data['identifier'] . '_' . $data['store_id'])
            ->setValueId($redirect->getId())
            ->setEntityType(1)
            ->save();
    }
    $installer->getConnection()->commit();
} catch (Exception $e) {
    $installer->getConnection()->rollBack();
    throw $e;
}

$installer->endSetup();

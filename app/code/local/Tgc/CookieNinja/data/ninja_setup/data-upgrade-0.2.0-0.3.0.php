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

$storeIds = Mage::getModel('core/store')
    ->getCollection()
    ->addFieldToFilter('code', array('neq' => Mage_Core_Model_Store::ADMIN_CODE))
    ->getColumnValues('store_id');

$datas = array();
foreach ($storeIds as $storeId) {
    $datas[] = array(
        'identifier'  => 'tgc/courses/courses.aspx',
        'target_path' => 'courses',
        'options'     => 'RP',
        'description' => 'Redirect legacy courses URL to All Courses Page',
        'store_id'    => $storeId,
    );
    $datas[] = array(
        'identifier'  => 'tgc/professors/professor_details.aspx',
        'target_path' => 'professors/professor/view',
        'options'     => 'RP',
        'description' => 'Redirect legacy professor detail page to new professor detail page',
        'store_id'    => $storeId,
    );
    $datas[] = array(
        'identifier'  => 'greatcourses.aspx',
        'target_path' => 'courses',
        'options'     => 'RP',
        'description' => 'Redirect legacy courses page to All Courses Page',
        'store_id'    => $storeId,
    );
    $datas[] = array(
        'identifier'  => 'tgc/courses/course_detail.aspx',
        'target_path' => 'catalog/product/view',
        'options'     => 'RP',
        'description' => 'Redirect legacy product page to new PDP',
        'store_id'    => $storeId,
    );
    $datas[] = array(
        'identifier'  => 'tgc/Courses/PodcastEpisode.aspx',
        'target_path' => 'podcasts/podcast/view',
        'options'     => 'RP',
        'description' => 'Redirect legacy podcast episode to new podcast episode',
        'store_id'    => $storeId,
    );
    $datas[] = array(
        'identifier'  => 'tgc/professors/professorsbytopic.aspx',
        'target_path' => 'professors',
        'options'     => 'RP',
        'description' => 'Redirect legacy professor page to new professor page',
        'store_id'    => $storeId,
    );
}

foreach ($datas as $data) {
    try {
        $redirect = Mage::getModel('enterprise_urlrewrite/redirect');
        $redirect->addData($data);
        $redirect->setIdPath($data['identifier'] . '_' . $data['store_id']);
        $redirect->save();
    } catch (Exception $e) {
        Mage::logException($e);
    }

    try {
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
    } catch (Exception $e) {
        Mage::logException($e);
    }
}

$installer->endSetup();

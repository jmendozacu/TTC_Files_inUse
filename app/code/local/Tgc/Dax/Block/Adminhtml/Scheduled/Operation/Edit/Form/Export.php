<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category
 * @package
 * @copyright   Copyright (c) 2013 Guidance Solutions (http://www.guidance.com)
 */
class Tgc_Dax_Block_Adminhtml_Scheduled_Operation_Edit_Form_Export
    extends Enterprise_ImportExport_Block_Adminhtml_Scheduled_Operation_Edit_Form_Export
{
    const ID_SFTP_HOST = 'sftp_host';
    const ID_SFTP_USER = 'sftp_user';
    const ID_SFTP_PASS = 'sftp_password';

    protected function _addFileSettings($form, $operation)
    {
        parent::_addFileSettings($form, $operation);

        $fieldset = $form->getElement('file_settings');

        $fieldset->addField(self::ID_SFTP_HOST, 'text', array(
            'name'      => 'file_info[host]',
            'title'     => Mage::helper('enterprise_importexport')->__('SFTP Host[:Port]'),
            'label'     => Mage::helper('enterprise_importexport')->__('SFTP Host[:Port]'),
            'class'     => 'sftp-server server-dependent'
        ));

        $fieldset->addField(self::ID_SFTP_USER, 'text', array(
            'name'      => 'file_info[user]',
            'title'     => Mage::helper('enterprise_importexport')->__('User Name'),
            'label'     => Mage::helper('enterprise_importexport')->__('User Name'),
            'class'     => 'sftp-server server-dependent'
        ));

        $fieldset->addField(self::ID_SFTP_PASS, 'password', array(
            'name'      => 'file_info[password]',
            'title'     => Mage::helper('enterprise_importexport')->__('Password'),
            'label'     => Mage::helper('enterprise_importexport')->__('Password'),
            'class'     => 'sftp-server server-dependent'
        ));
    }

    protected function _setFormValues(array $data)
    {
        parent::_setFormValues($data);

        $form = $this->getForm();
        $form->addValues(array(
            self::ID_SFTP_HOST => $form->getElement('host')->getValue(),
            self::ID_SFTP_USER => $form->getElement('user')->getValue(),
            self::ID_SFTP_PASS => $form->getElement('password')->getValue(),
        ));

        return $this;
    }
}
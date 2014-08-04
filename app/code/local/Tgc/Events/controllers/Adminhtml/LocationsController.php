<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     Events
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Tgc_Events_Adminhtml_LocationsController extends Mage_Adminhtml_Controller_Action
{
	
	protected function _initAction()
	{
		$this->loadLayout()
			->_setActiveMenu('events/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Events Manager'), Mage::helper('adminhtml')->__('Events Manager'));
		
		return $this;
	}   
 
	public function indexAction()
	{
		$this->_initAction()
			->renderLayout();
	}

	public function editAction()
	{
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('tgc_events/locations')->load($id);
		if ($model->getId() || $id == 0)
		{
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data))
			{
				$model->setData($data);
			}
			
			Mage::register('locations_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('events/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Location Manager'), Mage::helper('adminhtml')->__('Location Manager'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('tgc_events/adminhtml_locations_edit'));

			$this->renderLayout();
		}
		else
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('events')->__('Location does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction()
	{
		$this->_forward('edit');
	}

    public function saveAction()
	{
		if ($data = $this->getRequest()->getPost())
		{
			$id = $this->getRequest()->getParam('id');
			$isValid = true;
			$error = '';

			if (isset($data['location']))
			{
				$isDuplicate = Mage::helper('tgc_events')->checkDuplicate('location', $data['location'],'locations',$id);
				/* check for duplicate existence */
				if ($isDuplicate)
				{
					$isValid = false;
					$error .= Mage::helper('tgc_events')->__('The Location ').$data['event_url_prefix'].Mage::helper('tgc_events')->__(' already exists!');
				}
			}

            if (isset($data['location_code']))
            {
                $isDuplicate = Mage::helper('tgc_events')->checkDuplicate('location_code', $data['location_code'],'locations',$id);
                /* check for duplicate existence */
                if ($isDuplicate)
                {
                    $isValid = false;
                    $error .= Mage::helper('tgc_events')->__('The URL Code ').$data['location_code'].Mage::helper('tgc_events')->__(' already exists!');
                }
            }

			if (!$isValid)
			{
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('events')->__($error));
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				
                return;
			}

			$path = Mage::getBaseDir('media') . DS . 'events';
			if(isset($_FILES['location_image']['name']) && $_FILES['location_image']['name'] != '')
			{
				try
				{	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('location_image');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
					// We set media as the upload dir
					
					$uploader->save($path, $_FILES['location_image']['name'] );
					$varImg = new Varien_Image($path. DS .$_FILES['location_image']['name']);
					$varImg->constrainOnly(TRUE);
					$varImg->keepAspectRatio(TRUE);
					$varImg->keepFrame(TRUE);
					$varImg->keeptransparency(FALSE);
					$varImg->backgroundColor(array(255,255,255));// WHITE BACKGROUND
					$data['location_image'] = 'events'. DS .$_FILES['location_image']['name'];


				}
				catch (Exception $e)
				{
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('events')->__('Error: '.$e->getMessage()));
					Mage::getSingleton('adminhtml/session')->setFormData($data);
					$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				
					return;
		        }
		        //this way the name is saved in DB
			}
			elseif (isset($data['location_image']['delete']) AND $data['location_image']['delete'] == 1){
				$eventMediaPath = Mage::getBaseDir('media');
				$tImg = Mage::getModel('tgc_events/locations')->load($id)->getEveThumbImage();
				unlink($eventMediaPath. DS .$data['location_image']['value']);
				$data['location_image'] = '';
			}
	  		else{
				$data['location_image'] = $data['location_image']['value'];
			}
			$model = Mage::getModel('tgc_events/locations');
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try
			{
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('events')->__('Location was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back'))
				{
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				
				return;
            }
			catch (Exception $e)
			{
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('events')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction()
	{
        if( $this->getRequest()->getParam('id') > 0 )
		{
			try
			{
				$model = Mage::getModel('tgc_events/locations');
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Location was successfully deleted'));
				$this->_redirect('*/*/');
			}
			catch (Exception $e)
			{
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction()
	{
        $locationsIds = $this->getRequest()->getParam('locations');
        if(!is_array($locationsIds))
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        }
		else
		{
            try
			{
                foreach ($locationsIds as $locationsId)
				{
                    $location = Mage::getModel('tgc_events/locations')->load($locationsId);
                    $location->delete();
					
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($eventsIds)
                    )
                );
            }
			catch (Exception $e)
			{
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $locationsIds = $this->getRequest()->getParam('locations');
        if(!is_array($locationsIds))
		{
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        }
		else
		{
            try
			{
                foreach ($locationsIds as $locationsId)
				{
                    $location = Mage::getSingleton('tgc_events/locations')
                        ->load($locationsId)
                        ->setLocationStatus($this->getRequest()->getParam('location_status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($eventsIds))
                );
            }
			catch (Exception $e)
			{
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'locations.csv';
        $content    = $this->getLayout()->createBlock('tgc_events/adminhtml_locations_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'locations.xml';
        $content    = $this->getLayout()->createBlock('tgc_events/adminhtml_locations_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
	
	public function imageAction()
	{
		$result = array();
        try
		{
            $uploader = new FME_Events_Media_Uploader('image');
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save(
                    Mage::getSingleton('events/config')->getBaseMediaPath()
            );

            $result['url'] = Mage::getSingleton('events/config')->getMediaUrl($result['file']);
            $result['cookie'] = array(
                    'name'     => session_name(),
                    'value'    => $this->_getSession()->getSessionId(),
                    'lifetime' => $this->_getSession()->getCookieLifetime(),
                    'path'     => $this->_getSession()->getCookiePath(),
                    'domain'   => $this->_getSession()->getCookieDomain()
            );
        }
		catch (Exception $e)
		{
            $result = array('error' => $e->getMessage(), 'errorcode' => $e->getCode());
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
	}

}

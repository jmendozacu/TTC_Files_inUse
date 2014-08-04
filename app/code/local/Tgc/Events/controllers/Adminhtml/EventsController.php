<?php
require_once Mage::getModuleDir('controllers', 'FME_Events') . DS . 'Adminhtml' . DS . 'EventsController.php';

class Tgc_Events_Adminhtml_EventsController extends FME_Events_Adminhtml_EventsController
{
    public function editAction()
    {
        $id     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('events/events')->load($id);
        if ($model->getId() || $id == 0)
        {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data))
            {
                $model->setData($data);
            }

            Mage::register('events_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('events/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('events/adminhtml_events_edit'))
                ->_addLeft($this->getLayout()->createBlock('tgc_events/adminhtml_events_edit_tabs'));

            $this->renderLayout();
        }
        else
        {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('events')->__('Event does not exist'));
            $this->_redirect('*/*/');
        }
    }


    public function saveAction()
	{//Mage::helper('events')->removeGalleryByEventId($this->getRequest()->getParam('id'));
		if ($data = $this->getRequest()->getPost())
		{//echo '<pre>';print_r($data);exit;
			$data = $this->_filterDateTime($data, array('event_start_date', 'event_end_date'));
			
			$startDate = null;
			if (isset($data['event_start_date'])) {
				
				$startDate = strtotime($data['event_start_date']);
			}
			$endDate = null;
			if (isset($data['event_end_date'])) {
				
				$endDate = strtotime($data['event_end_date']);
			}
			
			$id = $this->getRequest()->getParam('id');
			$isValid = true;
			$error = '';
			$productId = 0;
			
			if (!is_null($startDate) && !is_null($endDate)) {
				if ($startDate > $endDate) {
					$isValid = false;
					$error = Mage::helper('events')->__('Event end date cannot be less than start date!');
				}
			}
			
			if (isset($data['links']['related']) AND $data['links']['related'] != 0)
			{
				$productId = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['links']['related']);
			}
			if (isset($data['event_url_prefix']))
			{
				$isDuplicate = Mage::helper('events')->checkDuplicate('event_url_prefix', $data['event_url_prefix'],'events',$id);
				$isReserved = Mage::helper('events')->isReserveWord($data['event_url_prefix']);
				/* check for duplicate existence */
				if ($isDuplicate || $isReserved)
				{
					$isValid = false;
					$error .= Mage::helper('events')->__('The Url Prefix ').$data['event_url_prefix'].Mage::helper('events')->__(' already exists or a reserve word!');
				}
			}
			
			/* Tgc override is to remove this 1 to 1 limitation */
			/*if (count($productId) > 1)
			{
				$isValid = false;
				$error .= Mage::helper('events')->__('Please attach only one product for an event');
			}*/
			
			if (!$isValid)
			{
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('events')->__($error));
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				
                return;
			}
			$thumb_img = '';
			$path = Mage::getBaseDir('media') . DS . 'events';
			if(isset($_FILES['event_image']['name']) && $_FILES['event_image']['name'] != '')
			{//echo '<pre>';print_r($_FILES['event_image']);exit;
				try
				{	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('event_image');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
					// We set media as the upload dir
					
					$uploader->save($path, $_FILES['event_image']['name'] );
					$varImg = new Varien_Image($path. DS .$_FILES['event_image']['name']);
					$varImg->constrainOnly(TRUE);
					$varImg->keepAspectRatio(FALSE);
					$varImg->keepFrame(TRUE);
					$varImg->keeptransparency(FALSE);
					$varImg->backgroundColor(array(255,255,255));// WHITE BACKGROUND
					$thumb_path = Mage::getBaseDir('media'). DS .'events'. DS .'thumbs';
					$thumb_img = 'thumb_'.str_shuffle($_FILES['event_image']['name']);//echo $thumb_img;exit;
					$varImg->resize(135,135);
					$varImg->save($thumb_path, $thumb_img);
					$data['event_image'] = 'events'. DS .$_FILES['event_image']['name'];
					$data['event_thumb_image'] = 'events'. DS .'thumbs'. DS .$thumb_img;
					

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
			elseif (isset($data['event_image']['delete']) AND $data['event_image']['delete'] == 1){
				$eventMediaPath = Mage::getBaseDir('media');
				$tImg = Mage::getModel('events/events')->load($id)->getEventThumbImage();
				unlink($eventMediaPath. DS .$data['event_image']['value']);
				unlink($eventMediaPath. DS .$tImg);
				$data['event_image'] = '';
				$data['event_thumb_image'] = '';
			}
	  		else{
				$data['event_image'] = $data['event_image']['value'];
			}
			$model = Mage::getModel('events/events');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try
			{
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL)
				{
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				}
				else
				{
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('events')->__('Event was successfully saved'));
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

    protected function _initEventsProfessors()
    {
        $events = Mage::getModel('events/events');
        $eventsId  = (int) $this->getRequest()->getParam('id');
        if ($eventsId)
        {
            $events->load($eventsId);
        }
        Mage::register('current_events_professors', $events);

        return $events;
    }

    public function professorsAction()
    {
        $this->_initEventsProfessors();
        $this->loadLayout();
        $this->getLayout()->getBlock('events.edit.tab.professors')
            ->setEventsProfessorsRelated($this->getRequest()->getPost('professors_related', null));
        $this->renderLayout();
    }

    /**
     * Get related professors grid
     */
    public function professorsGridAction()
    {
        $this->_initEventsProfessors();
        //Push Existing Values in Array
        $professorsarray = array();
        $eventsId  = (int) $this->getRequest()->getParam('id');
        foreach (Mage::registry('current_events_professors')->getEventsRelatedProfessors($eventsId) as $professors)
        {
            $professorsarray = $professors["professor_id"];
        }
        if (!empty($_POST['professors_related']))
            array_push($_POST["professors_related"],$professorsarray);
        Mage::registry('current_events_professors')->setEventsProfessorsRelated($professorsarray);

        $this->loadLayout();
        $this->getLayout()->getBlock('events.edit.tab.professors')
            ->setEventsProfessorsRelated($this->getRequest()->getPost('professors_related', null));
        $this->renderLayout();
    }


    public function gridAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('events/adminhtml_events_edit_tab_professors')->toHtml()
        );
    }

    /**
     * Get specified tab grid
     */
    public function gridOnlyAction()
    {
        echo 'Function ===> GridOnlyAction';
        $this->_initProfessor();
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('adminhtml/events_edit_tab_professors')
                ->toHtml()
        );
    }
}

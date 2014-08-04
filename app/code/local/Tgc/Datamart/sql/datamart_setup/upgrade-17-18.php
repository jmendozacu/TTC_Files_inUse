<?php
/**
 * @author      Guidance Magento Team <magento@guidance.com>
 * @category    Tgc
 * @package     DataMart
 * @copyright   Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */
/* @var $installer Tgc_Datamart_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

// Create Homepage Hero Carousel item about radio promotion
$heroCarouselItem = Mage::getModel('tgc_cms/heroCarousel');
if ($heroCarouselItem) {
    $description = <<<HTML
<div class="hero-img">
    <img src="{{skin url='images/tgc/home-hero-img.png'}}" alt="Image hero"/>
</div>
<div class="hero-desc">
    <h2>Special Radio Ipsum Offer.</h2>
    <p>Hear us on radio dolor?</p>
    <p>Simply enter your code for special savings lorem!</p>
    <a class="button media-code-popup-button" href="#">Enter Radio Code</a>
</div>
HTML;
    $mobileDescription = <<<HTML
<div class="hero-img">
    <img src="{{skin url='images/tgc/home-hero-mob-img.png'}}" alt="Image hero"/>
</div>
<div class="hero-desc">
    <h2>Special Radio Ipsum Offer.</h2>
    <p>Hear us on radio dolor?</p>
    <a class="button media-code-popup-button" href="#">Enter Radio Code</a>
</div>
HTML;
    $heroCarouselItem->setIsActive(1)
        ->setStore(0)
        ->setUserType(Tgc_Cms_Model_Source_UserType::ALL_USERS)
        ->setSortOrder(4)
        ->setTabTitle('Radio Offers')
        ->setTabDescription('Hear us on the radio? Enter your code for special savings!')
        ->setDescription($description)
        ->setMobileDescription($mobileDescription)
        ->save();
}

$installer->endSetup();

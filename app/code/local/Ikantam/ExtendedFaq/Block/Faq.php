<?php
/**
 * iKantam LLC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the iKantam EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://magento.ikantam.com/store/license-agreement
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * ExtendedFaq Module to newer versions in the future.
 *
 * @category   Ikantam
 * @package    Ikantam_ExtendedFaq
 * @author     iKantam Team <support@ikantam.com>
 * @copyright  Copyright (c) 2012 iKantam LLC (http://www.ikantam.com)
 * @license    http://magento.ikantam.com/store/license-agreement  iKantam EULA
 */

class Ikantam_ExtendedFaq_Block_Faq extends Mage_Core_Block_Template
{	

	protected $_collection;

	protected function getQuestionsCollection()
	{
		if (!$this->_collection) {
			$this->_collection = Mage::getModel('extendedfaq/faq')->getCollection()->setOrder('sort_order', 'asc')->addFieldToFilter('is_active', 1);
		}
		
		return $this->_collection;
	}
	
	public function getSearchUrl()
	{
		return Mage::helper('extendedfaq')->getSearchUrl();
	}


	public function getQuestions($categoryId = null)
	{
		if ($categoryId) {
			return Mage::getModel('extendedfaq/faq')->getCollection()->setOrder('sort_order', 'asc')->addFieldToFilter('is_active', 1)->addFieldToFilter('category_id', $categoryId);
		}
		
		return $this->getQuestionsCollection();
	}

	public function getCategories()
	{
		return Mage::getModel('extendedfaq/category')->getCollection()->setOrder('sort_order', 'asc')->addFieldToFilter('is_active', 1);
	}

	public function getAnchor($category)
	{
		$categoryTitle = strtolower($category->getTitle());
		$categoryTitle = preg_replace('/[^a-z0-9\s]/', '', $categoryTitle);
		$categoryTitle = preg_replace('/\s+/', '-', $categoryTitle);
		return $categoryTitle;
	}



	public function getActiveData()
	{
		$posts = Mage::getModel('extendedfaq/faq')
			->getCollection()
			->addFieldToFilter('is_active', 1);
		return $posts;
	}

	public function getActiveDataByCategory($idCategory)
	{
		$posts = Mage::getModel('extendedfaq/faq')
			->getCollection()
			->addFieldToFilter('is_active', 1)
			->addFieldToFilter('id_category', $idCategory);
		return $posts;
	}
	
	
	public function getAllCategories()
	{
		$posts = Mage::getModel('extendedfaq/category')
			->getCollection();
		return $posts;
	}
	
	public function getAnswer($question)
	{
		$posts = Mage::getModel('extendedfaq/faq')
			->getCollection()
			->addFieldToFilter('question',array('like'=>'%'.$question.'%'))
			->addFieldToFilter('is_active', 1);
		return $posts;
	}
	
	public function sendQuestion($question)
	{
		$posts = Mage::getModel('extendedfaq/faq');
		$posts->setQuestion($question);
		$posts->save();
	}
}

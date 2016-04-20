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

class Ikantam_ExtendedFaq_Block_Adminhtml_Category_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    
	protected function _construct()
    {
        $this->setEmptyText($this->__('No Category Found'));
        $this->setDefaultSort('sort_order');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('extendedfaq/category')
            ->getCollection();

       
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => $this->__('ID'),
            'align'  => 'center',
            'index'  => 'id',
            'width'  => '50px',
        ));
        
		$this->addColumn('title', array(
            'header' => $this->__('Title'),
            'width'  => '200px',
            'index'  => 'title',
            'align'  => 'left',
        ));
        
        $this->addColumn('sort_order', array(
            'header' => $this->__('Sort Order'),
            'type'   => 'text',
            'width'  => '200px',
            'index'  => 'sort_order',
            'align'  => 'left',
        ));
        
        $this->addColumn('is_active', array(
            'header'  => $this->__('Enabled'),
            'index'   => 'is_active',
            'width'   => '150',
            'type'    => 'options',
            'options' => array(
                '0' => $this->__('No'),
                '1' => $this->__('Yes'),
            ),
        ));

        $this->addColumn('action', array(
            'header'    => $this->__('Action'),
            'width'     => '50px',
            'type'      => 'action',
            'getter'    => 'getId',
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'stores',
            'is_system' => true,
            'actions'   => array(array(
                'caption' => $this->__('View'),
                'url'     => array('base'=>'*/*/edit'),
                'field'   => 'id'
            )),
        ));

        return $this;
    }
    
	protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('id');
		
		$this->getMassactionBlock()->addItem('enable', array(
            'label' => $this->__('Enable'),
            'url'   => $this->getUrl('*/*/massEnable')
        ));

		$this->getMassactionBlock()->addItem('disable', array(
            'label' => $this->__('Disable'),
            'url'   => $this->getUrl('*/*/massDisable')
        ));
        
        $this->getMassactionBlock()->addItem('delete', array(
            'label'   => $this->__('Delete'),
            'url'     => $this->getUrl('*/*/massDelete'),
            'confirm' => $this->__('Are you sure?')
        ));

        return $this;
    }
	
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}

<?php
class Fresh_Prodlabel_Adminhtml_ProdlabelController extends Mage_Adminhtml_Controller_Action
{
    public function viewAction() {
        $this->_title($this->__('Generate product labels'));
        $this->loadLayout();
        $this->renderLayout();
    }
    public function saveAction() {
        $this->_title($this->__('Generate product labels'));
        $order_id = $this->getRequest()->getParam('order_id');
        $data = $this->getRequest()->getPost();
		$data['order_id'] = $order_id;
//        var_dump($order_id);
//        echo "<pre>";
//        var_dump($data);
        $this->generatePdfFile($data);
        //exit;
//        $this->loadLayout();
//        $this->renderLayout();
    }
    public function generatePdfFile($data){
		$this->_isExport = true;
		$pdf = new Zend_Pdf(); 
		$pdf->pages[] = $pdf->newPage(Zend_Pdf_Page::SIZE_A4); 
		$page=$pdf->pages[0]; // this will get reference to the first page. 
		$style = new Zend_Pdf_Style(); 
		$style->setLineColor(new Zend_Pdf_Color_Rgb(0,0,0)); 
		$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES); 
		$style->setFont($font,12); 
		$page->setStyle($style);
		$y = $page->getHeight() - 100;
		$order = Mage::getModel('sales/order')->load($data['order_id']);
		//var_dump($order->getData()); die();
		$store = Mage::getModel('core/store')->load($order->getStoreId());
		
		//$image =  Mage::getStoreConfig('design/header/logo_src', $order->getStoreId());
		//$image = Mage::getSingleton('core/design_package')->getSkinBaseDir(array('_area' => 'frontend')) . '/' . $image;
		$image = '/home/freshtodommot/public_html/skin/frontend/freshdommot/default/images/logo.png';
		//Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . $image;
		//var_dump($image); die();
		//$image = 'http://freshtodommot.com/skin/frontend/freshdommot/default/images/logo.png';
		
		if (is_file($image)) {
			$image = Zend_Pdf_Image::imageWithPath($image);
			$page->drawImage($image, 350, $y+50, 350+200, $y+100);
		}
		$page->drawText('Order #' . $order->getIncrementId(), 10, $y+50);
		$page->drawText('Store name: ' . $store->getName(), 130, $y+50);
		$page->drawText('Name', 10, $y+15);
		$page->drawText('Qty', 350, $y+15);
		$page->drawText('Price', 400, $y+15);
		$page->drawText('Total', 450, $y+15);
		
		$page->drawLine(10, $y+12, 500, $y+12);
	   
	   $items = $order->getAllItems();
		foreach($items as $item):
			//var_dump($item->getData()); die();
		  //echo $_product = Mage::getModel('catalog/product')
			//        ->load($i->getProductId())->getSku();
			//$page->drawText($item->getName(),10,$y);
			$product = Mage::getModel('catalog/product')->load($item->getProductId());
			$name = $item->getName();
			if($meatType = $product->getMeatType()){
				$name = ' (' . $meatType . ')';
			}
				
			$page->drawText($name, 10, $y);
			$page->drawText(round($item->getQtyOrdered()), 350, $y);
			//var_dump(Mage::helper('core')->currency($item->getPrice(), true, false)); die();
			$page->drawText(round($item->getBasePrice(), 2), 400, $y, 'UTF-8');
			$page->drawText(round($item->getBaseRowTotal(), 2), 450, $y, 'UTF-8');
			$y -= 15;
		endforeach;
       /*foreach ($data as $k => $v) {           
            $page->drawText($k,100,$y); 
            $page->drawText($v,300,$y); 
            $y -= 15;
       }*/
       // Blank page and pdf headers 
       header('Content-type: application/pdf'); 
       //header('Content-Disposition: attachment; filename="downloaded.pdf"'); 

       // Browser watch 
       echo $pdf->render(); 
    }
}
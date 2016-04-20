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
	   //var_dump($order->getData());
	   $page->drawText($order->getIncrementId(),10, $y-90);
	   $items = $order->getAllItems();
foreach($items as $item):
	var_dump($item->getData());
  //echo $_product = Mage::getModel('catalog/product')
    //        ->load($i->getProductId())->getSku();
	//$page->drawText($item->getName(),10,$y);
	$page->drawText($item->getName(),10,$y);
	$page->drawText($item->getQtyOrdered(),50,$y);
	$page->drawText($item->getPrice(),100,$y);
	$y -= 15;
endforeach;
       /*foreach ($data as $k => $v) {           
            $page->drawText($k,100,$y); 
            $page->drawText($v,300,$y); 
            $y -= 15;
       }*/
       // Blank page and pdf headers 
       //header('Content-type: application/pdf'); 
       //header('Content-Disposition: attachment; filename="downloaded.pdf"'); 

       // Browser watch 
       //echo $pdf->render(); 
    }
}
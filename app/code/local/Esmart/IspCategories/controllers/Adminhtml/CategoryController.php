<?php
class Esmart_IspCategories_Adminhtml_CategoryController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction(){
    	$this->loadLayout();
    	$this->renderLayout();
    }
    
     public function dropcategoryAction(){
		$this->loadLayout();
		for($i = 4370;$i < 6909;$i++){
			try {
				if($i != 17 && $i != 66 && $i != 67 && $i != 68){
					$category = Mage::getModel('catalog/category')->load($i)->delete();
				}

			}
			catch (Exception $e){
			    echo $e->getMessage();
			}

		}
        $this->renderLayout();
    }
}
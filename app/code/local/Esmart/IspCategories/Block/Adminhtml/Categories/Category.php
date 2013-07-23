<?php

class Esmart_IspCategories_Block_Adminhtml_Categories_Category extends Mage_Core_Block_Template
{
    
   public $contSimple = 0;
    public $lastcategory = NULL;
    public $flag_configurable = 0;
    public $nc_categoryarray = Array(); //onde ficarão os arrays de sku simples para um configuravel
    public $nc_categorycont = 0;
    public $nc_category = 0; //onde ficará o sku do produto configurável
    public $ns_parent;
    public $final_simple_sku = Array();
    
    public $cont_success = 0;
    public $cont_fail = 0;
    
    public $ns_price;
    public $ns_name;

    public $ns_image;
    public $ns_smallImage;
    public $ns_thumbnail;

    public $lastprice;
    public $lastname;
    public $lastparent;

    public $lastimage;
    public $lastsmallImage;
    public $lastthumbnail;
    
    public function testRemove(){
        echo 'kkkk';
        die;
        //$parentId = 455;

        for($i = 6916;$i < 21291;$i++){
            try {
                
                    $category = Mage::getModel('catalog/category')->load($i)->delete();
                
            }
            catch (Exception $e){
                echo $e->getMessage();
            }

        }

    }

    public function testCategory()
    {        
        /* supply parent id */
        $parentId = '2';
         
        $category = new Mage_Catalog_Model_Category();
        $category->setName('Storybloks');
        $category->setUrlKey('new-category');
        $category->setIsActive(1);
        $category->setDisplayMode('PRODUCTS');
        $category->setIsAnchor(0);
         
        $parentCategory = Mage::getModel('catalog/category')->load($parentId);
        $category->setPath($parentCategory->getPath());               
         
        $category->save();
        unset($category);
    }

    public function importCsv()
    {
        echo '<div class="entry-edit-head">
                    <h4 class="icon-head head-edit-form fieldset-legend">Importando Categorias</h4>
                </div>';
        if (isset($_POST['submit'])) {
            echo '<fieldset id="my-fieldset">';
            $file = $_FILES['category']['tmp_name'];
            $postData = $this->getRequest()->getPost();
            $size = filesize($file) + 1;
            $file = fopen($file, 'r');
            $column = fgetcsv($file, $size, ";");
           
            while ($column = fgetcsv($file, $size, ";")) {
                //echo $column;
                //var_dump($column);
           
                $row = explode(',',$column[0]);
                 
                for ($i = 0; $i < count($row); $i++) {
                 
                    //se for sku                   
                    if ($i == 0) {
                            
                       // echo $row[$i] .'--'.$row[$i+1]. '<br /><br />';
                        
                        $ns_category = $row[$i];
                        $ns_parent = $row[$i+1];
                        //guarda o sku em array
                        $this->nc_category = $ns_category;
                        $this->ns_parent = $ns_parent;
                        if($this->lastcategory == NULL){
                            //iguala os prefixos para a próxima comparação
                            $this->lastcategory = $this->nc_category;
                            $this->lastparent = $this->ns_parent;
                        }
                        
                        if($this->lastcategory == $this->nc_category){
                            $this->nc_categoryarray[$this->nc_categorycont] = $ns_category;
                        }
                       //o prefixo do sku mudou

                       if ($this->lastcategory != $this->nc_category) {
                          //echo 'Config';
                            echo '<br>Inserindo categoria '.$this->lastcategory;
                            $this->insertCategory();
                            $this->cont_success++;
                            $this->lastcategory = $this->nc_category;
                            $this->lastparent = $this->ns_parent;
                       }    
                    }
                }
            }
            fclose($file);
            echo '</fieldset>';
            echo '<div class="entry-edit-head">
                    <h4 class="icon-head head-edit-form fieldset-legend">Relatório de Importação</h4>
                </div>';
            echo '<fieldset id="my-fieldset">';
            echo '<br>Categorias adicionadas: '.$this->cont_success;
            echo '</fieldset>';
        }
    }
    
   
    public function insertCategory()
    {        
        $parentId = $this->lastparent;
         
        $category = new Mage_Catalog_Model_Category();
        $category->setName($this->lastcategory);
        $category->setUrlKey($this->lastcategory);
        $category->setIsActive(1);
        $category->setDisplayMode('PRODUCTS');
        $category->setIsAnchor(1);
         
        $parentCategory = Mage::getModel('catalog/category')->load($parentId);
        $category->setPath($parentCategory->getPath());               
         
         try {
            $category->save();
            echo "<p>Nova categoria cadastrada: ".$this->lastcategory.' Id: '.$category->getId();
            unset($category);

        }
        catch (Exception $e){
            echo $e->getMessage();
        }
        
    }
}
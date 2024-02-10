<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Publish extends ADMIN_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'Products_model',
            'Languages_model',
            'Brands_model',
			'Vendor_model',
            'Categories_model'
        ));
    }

    public function index($id = 0)
    {
        $this->login_check();
        $is_update = false;
        $trans_load = null;
        if ($id > 0 && $_POST == null) {
            $_POST = $this->Products_model->getOneProduct($id);
            $trans_load = $this->Products_model->getTranslations($id);
        }
        if (isset($_POST['submit'])) {
            if (isset($_GET['to_lang'])) {
                $id = 0;
            }
            if(! empty($_FILES['userfile']['name']))
                $_POST['image'] = $this->uploadImage($_POST['in_slider']);
            $this->Products_model->setProduct($_POST, $id);
            $this->session->set_flashdata('result_publish', 'Product is published!');
            if ($id == 0) {
                $this->saveHistory('Success published product');
            } else {
                $this->saveHistory('Success updated product id = '. $id);
            }
            if (isset($_SESSION['filter']) && $id > 0) {
                $get = '';
                foreach ($_SESSION['filter'] as $key => $value) {
                    $get .= trim($key) . '=' . trim($value) . '&';
                }
                redirect(base_url('admin/products?' . $get));
            } else {
                redirect('admin/products');
            }
        }
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Publish Product';
        $head['description'] = '!';
        $head['keywords'] = '';
        $data['id'] = $id;
        $data['trans_load'] = $trans_load;
        $data['languages'] = $this->Languages_model->getLanguages();
        $data['shop_categories'] = $this->Categories_model->getShopCategories2();
        $data['brands'] = $this->Brands_model->getBrands();
        $data['vendor'] = $this->Vendor_model->getVendor();
        $data['otherImgs'] = $this->loadOthersImages();
        $this->load->view('_parts/header', $head);
        $this->load->view('ecommerce/publish', $data);
        $this->load->view('_parts/footer');
        $this->saveHistory('Go to publish product');
    }

    private function uploadImage($in_slider)
    {
        $config['upload_path'] = './attachments/'. SHOP_DIR .'/shop_images/';
        $config['allowed_types'] = $this->allowed_img_types;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if($this->load->get_var('template') == 'divisima'){
            $image_info = getimagesize($_FILES["userfile"]["tmp_name"]);
            $width = $image_info[0];
            $height = $image_info[1];
            if($width < 500 || $height < 775){
                $this->session->set_flashdata('result_publish', 'Image minimum width 500px and height 775px.');
                redirect('admin/products');
            }
        }
        
        if (!$this->upload->do_upload('userfile')) {
            log_message('error', 'Image Upload Error: ' . $this->upload->display_errors());
        }
        $img = $this->upload->data(); // get all info of uploaded file
        if(!empty($_FILES['userfile']['name']) && $in_slider == 0){
            //for image resize
            $this->load->library('image_lib');
            $config['image_library'] = 'gd2';
            $config['source_image'] = $img['full_path'];
            // $config['create_thumb'] = TRUE;
            $config['maintain_ratio'] = TRUE;
            $config['quality'] = '50%';
            $config['width'] = 350;
            $config['height'] = 400;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $this->image_lib->clear();
        }        
        return $img['file_name'];
    }

    /*
     * called from ajax
     */

    public function do_upload_others_images()
    {
        if ($this->input->is_ajax_request()) {
            $upath = '.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'shop_images' . DIRECTORY_SEPARATOR . $_POST['folder'] . DIRECTORY_SEPARATOR;
            if (!file_exists($upath)) {
                mkdir($upath, 0777);
            }

            $this->load->library('upload');

            $files = $_FILES;
            $cpt = count($_FILES['others']['name']);
            for ($i = 0; $i < $cpt; $i++) {
                unset($_FILES);
                $_FILES['others']['name'] = $files['others']['name'][$i];
                $_FILES['others']['type'] = $files['others']['type'][$i];
                $_FILES['others']['tmp_name'] = $files['others']['tmp_name'][$i];
                $_FILES['others']['error'] = $files['others']['error'][$i];
                $_FILES['others']['size'] = $files['others']['size'][$i];

                $this->upload->initialize(array(
                    'upload_path' => $upath,
                    'allowed_types' => $this->allowed_img_types
                ));
                $this->upload->do_upload('others');
            }
        }
    }

    public function loadOthersImages()
    {
        $output = '';
        if (isset($_POST['folder']) && $_POST['folder'] != null) {
            $dir = 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'shop_images' . DIRECTORY_SEPARATOR . $_POST['folder'] . DIRECTORY_SEPARATOR;
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    $i = 0;
                    while (($file = readdir($dh)) !== false) {
                        if (is_file($dir . $file)) {
                            $output .= '
                                <div class="other-img" id="image-container-' . $i . '">
                                    <img src="' . base_url('attachments/'. SHOP_DIR .'/shop_images/' . $_POST['folder'] . '/' . $file) . '" style="width:100px; height: 100px;">
                                    <a href="javascript:void(0);" onclick="removeSecondaryProductImage(\'' . $file . '\', \'' . $_POST['folder'] . '\', ' . $i . ')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </a>
                                </div>
                               ';
                        }
                        $i++;
                    }
                    closedir($dh);
                }
            }
        }
        if ($this->input->is_ajax_request()) {
            echo $output;
        } else {
            return $output;
        }
    }

    /*
     * called from ajax
     */

    public function removeSecondaryImage()
    {
        if ($this->input->is_ajax_request()) {
            $img = '.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'shop_images' . DIRECTORY_SEPARATOR . '' . $_POST['folder'] . DIRECTORY_SEPARATOR . $_POST['image'];
            unlink($img);
        }
    }

}

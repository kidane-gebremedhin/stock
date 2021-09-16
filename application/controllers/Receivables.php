<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Receivables extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'receivables';

        $this->load->model('model_users');
		$this->load->model('model_products');
		$this->load->model('model_brands');
		$this->load->model('model_category');
		$this->load->model('model_stores');
        $this->load->model('model_attributes');
		$this->load->model('model_date');
        $this->load->model('model_received_products');
        $this->load->model('model_company');
        $this->load->model('model_custom_attributes');

	}

    /* 
    * It only redirects to the manage receivable page
    */
	public function index()
	{
        //var_dump($this->model_date->getEthiopianDate()? 'Expired': 'Active');

        if(!in_array('viewReceivable', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->render_template('receivables/index', $this->data);	
	}

    /*
    * It Fetches the receivables data from the receivable table 
    * this function is called from the datatable ajax function
    */

    public function transactionsData()
    {
        $fromDate = $this->input->post('fromDate');
        $toDate = $this->input->post('toDate');
        $selected_product = $this->input->post('selected_product');

        $transactions=array();
        $in_data = $this->model_received_products->inTransactions($selected_product);
        $out_data = $this->model_received_products->outTransactions($selected_product);

        foreach($in_data as $obj) {
            if(($fromDate!=null && strtotime($obj['receivedDate']) < strtotime($fromDate)) || ($toDate!=null && strtotime($obj['receivedDate']) >= strtotime($toDate)))
                continue;

            $item=array('productId'=>strtotime($obj['receivedDate']), 'product'=>$this->model_products->getProductData($obj['productId'])['name'], 'date'=>$obj['receivedDate'], 'qty'=>$obj['qty'], 'type'=>'Received');
            array_push($transactions, $item);
        }

        foreach($out_data as $obj) {
            if(($fromDate!=null && $obj['date_time'] < strtotime($fromDate)) || ($toDate!=null && $obj['date_time'] >= strtotime($toDate)))
                continue;

            $item=array('productId'=>$obj['date_time'], 'product'=>$this->model_products->getProductData($obj['product_id'])['name'], 'date'=>date('Y-m-d', $obj['date_time']), 'qty'=>$obj['qty'], 'type'=>'Issued');
            array_push($transactions, $item);
        }

        sort($transactions, 0);

        /*var_dump($in_data);
        var_dump($out_data);*/
        //var_dump($transactions);
        //var_dump($selected_product);

        $this->data['transactions'] = $transactions;
        $this->data['products'] = $this->model_products->getProductData();
        $this->data['selected_product'] = $selected_product;
        $this->data['company_currency'] = $this->company_currency();
        $this->data['fromDate'] = $fromDate;
        $this->data['toDate'] = $toDate;

        $this->render_template('receivables/transactions', $this->data); 
    }
    
    public function fetchReceivableData()
    {
    	$result = array('data' => array());
        $user_id = $this->session->userdata('id');
        $user_data = $this->model_users->getUserData($user_id);

		$data = $this->model_received_products->getActiveReceivedProductData($user_data['store_id']);

		foreach ($data as $key => $value) {

            $product = $this->model_products->getProductData($value['productId']);

            $store_data = $this->model_stores->getStoresData($value['store_id']);
			// button
            $buttons = '';
            if(in_array('updateReceivable', $this->permission)) {
    			$buttons .= '<a href="'.base_url('receivables/update/'.$value['id']).'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
            }

            if(in_array('deleteReceivable', $this->permission)) { 
    			$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
            }
			

			$img = '<a href="'.base_url($product['image']).'" target="_blank"><img src="'.base_url($product['image']).'" alt="'.$product['name'].'" class="img-circle" width="50" height="50" /></a>';

            $availability = ($value['active'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

            $qty_status = '';
            /*if($value['qty'] <= 0) {
                $qty_status = '<span class="label label-danger">Out of stock !</span>';
            }
            else if($value['qty'] <= $value['minQty']) {
                $qty_status = '<span class="label label-warning">Low !</span>';
            }*/

            $date = date('Y-m-d',time());//date variable
            $expDate = strtotime($value['expDate']);
            $now = strtotime($date);

           $remained_days=0;
            $expiry_status = '';
            if($expDate<$now) {
                $expiry_status = '<span class="label label-danger">Expired !</span>';
            }else{
                $expDateTime = new DateTime($value['expDate']);
                $dateTimeNow = new DateTime();
                $dateDiff = $expDateTime->diff($dateTimeNow);    
                $remained_days=$dateDiff->d;        
            }
            
            $remained_days = 0;/*'<span class="label <?php echo '.($remained_days>$value['beforeExpDays']? 'label-primary': 'label-warning').' ?>">'.$remained_days.' days</label>';*/
            /*else if($value['qty'] <= $value['minQty']) {
                $expiry_status = '<span class="label label-warning">Remained x days !</span>';
            }*/

			$result['data'][$key] = array(
				$img,
                $value['refNumber'],
                $value['receivedDate'],
				'<a href="'.base_url('receivables/detail/'.$value['id']).'">'.$product['name'].'</a>',
                $value['cost'],
				$value['price'],
                $value['qty'] . ' ' . $qty_status,
                $value['defectedQty'],
                $value['expDate'] .$expiry_status,
                $remained_days,
                $store_data['name'],
				$availability,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}	

    /*
    * If the validation is not valid, then it redirects to the create page.
    * If the validation for each input field is valid then it inserts the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage receivable page
    */
	public function create()
	{
		if(!in_array('createReceivable', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('productId[]', 'Product', 'trim|required');
        $this->form_validation->set_rules('refNumber[]', 'Ref Number', 'trim|required');
        $this->form_validation->set_rules('cost[]', 'Cost', 'trim|required');
		$this->form_validation->set_rules('price[]', 'Price', 'trim|required');
        $this->form_validation->set_rules('qty[]', 'Qty', 'trim|required');
        $this->form_validation->set_rules('defectedQty[]', 'Defected Qty', 'trim|required');
        $this->form_validation->set_rules('receivedDate', 'Received Date', 'trim|required');
        $this->form_validation->set_rules('expDate[]', 'Expiry Date', 'trim');
        $this->form_validation->set_rules('store', 'Store', 'trim|required');
        $this->form_validation->set_rules('description[]', 'Description', 'trim');
	
        if ($this->form_validation->run() == TRUE) {
            // true case
        	$upload_image = $this->upload_image();

        	$data = array(
                'productId' => $this->input->post('productId'),
                'refNumber' => $this->input->post('refNumber'),
                'cost' => $this->input->post('cost'),
                'price' => $this->input->post('price'),
                'qty' => $this->input->post('qty'),
                'defectedQty' => $this->input->post('defectedQty'),
                'receivedDate' => $this->input->post('receivedDate'),
                'expDate' => $this->input->post('expDate'),
                'store_id' => $this->input->post('store'),
        		'description' => $this->input->post('description'),
        		'active' => 1,
        	);

            $create = $this->model_received_products->create($data);
            //$updateQty = $this->model_products->updateQty($this->input->post('productId'), $this->input->post('qty'));

        	if($create == true) {
        		$this->session->set_flashdata('success', 'Receivable saved successfully.');
        		redirect('receivables/', 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('receivables/create', 'refresh');
        	}
        }
        else {
            // false case

        	// attributes 
        	$attribute_data = $this->model_attributes->getActiveAttributeData();

        	$attributes_final_data = array();
        	foreach ($attribute_data as $k => $v) {
        		$attributes_final_data[$k]['attribute_data'] = $v;

        		$value = $this->model_attributes->getAttributeValueData($v['id']);

        		$attributes_final_data[$k]['attribute_value'] = $value;
        	}

        	$this->data['attributes'] = $attributes_final_data;
			$this->data['brands'] = $this->model_brands->getActiveBrands();        	
			$this->data['category'] = $this->model_category->getActiveCategroy();        	
            $this->data['stores'] = $this->model_stores->getActiveStore();          
			$this->data['products'] = $this->model_products->getProductData();
            $company=$this->model_company->getCompanyData(1);        	
            $this->data['company_data'] = $company;
            $this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
            $this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;
            $this->data['custom_attributes'] = $this->model_custom_attributes->getAttributeData();
            $user_id = $this->session->userdata('id');
            $this->data['user_data'] = $this->model_users->getUserData($user_id);

            $this->render_template('receivables/create', $this->data);
        }	
	}

    /*
    * This function is invoked from another function to upload the image into the assets folder
    * and returns the image path
    */
	public function upload_image()
    {
    	// assets/images/receivable_image
        $config['upload_path'] = 'assets/images/receivable_image';
        $config['file_name'] =  uniqid();
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1000';

        // $config['max_width']  = '1024';s
        // $config['max_height']  = '768';

        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('receivable_image'))
        {
            $error = $this->upload->display_errors();
            return $error;
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $type = explode('.', $_FILES['receivable_image']['name']);
            $type = $type[count($type) - 1];
            
            $path = $config['upload_path'].'/'.$config['file_name'].'.'.$type;
            return ($data == true) ? $path : false;            
        }
    }

    /*
    * If the validation is not valid, then it redirects to the edit receivable page 
    * If the validation is successfully then it updates the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage receivable page
    */
    public function detail($receivable_id)
    {      
        if(!in_array('viewReceivable', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        if(!$receivable_id) {
            redirect('dashboard', 'refresh');
        }


            // attributes 
            $attribute_data = $this->model_attributes->getActiveAttributeData();

            $attributes_final_data = array();
            foreach ($attribute_data as $k => $v) {
                $attributes_final_data[$k]['attribute_data'] = $v;

                $value = $this->model_attributes->getAttributeValueData($v['id']);

                $attributes_final_data[$k]['attribute_value'] = $value;
            }
            
            // false case
            $this->data['attributes'] = $attributes_final_data;
            $this->data['brands'] = $this->model_brands->getActiveBrands();         
            $this->data['category'] = $this->model_category->getActiveCategroy();           
            $this->data['stores'] = $this->model_stores->getActiveStore();          

            $receivable_data = $this->model_received_products->getReceivedProductData($receivable_id);
            $this->data['receivable_data'] = $receivable_data;

            $product = $this->model_products->getProductData($receivable_data['productId']);
            $this->data['receivable_data']['name'] = $product['name'];
            $this->data['receivable_data']['beforeExpDays'] = $product['beforeExpDays'];
            $this->data['receivable_data']['image'] = $product['image'];

            $this->render_template('receivables/detail', $this->data); 
    }

	public function update($receivable_id)
	{      
        if(!in_array('updateReceivable', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        if(!$receivable_id) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('productId[]', 'Product', 'trim|required');
        $this->form_validation->set_rules('refNumber[]', 'Ref Number', 'trim|required');
        $this->form_validation->set_rules('cost[]', 'Cost', 'trim|required');
        $this->form_validation->set_rules('price[]', 'Price', 'trim|required');
        $this->form_validation->set_rules('qty[]', 'Qty', 'trim|required');
        $this->form_validation->set_rules('defectedQty[]', 'Defected Qty', 'trim|required');
        $this->form_validation->set_rules('receivedDate', 'Received Date', 'trim|required');
        $this->form_validation->set_rules('expDate[]', 'Expiry Date', 'trim');
        $this->form_validation->set_rules('store', 'Store', 'trim|required');
        $this->form_validation->set_rules('description[]', 'Description', 'trim');

        if ($this->form_validation->run() == TRUE) {
            // true case
            
            $data = array(
                'productId' => $this->input->post('productId')[0],
                'refNumber' => $this->input->post('refNumber')[0],
                'cost' => $this->input->post('cost')[0],
                'price' => $this->input->post('price')[0],
                'qty' => $this->input->post('qty')[0],
                'defectedQty' => $this->input->post('defectedQty')[0],
                'receivedDate' => $this->input->post('receivedDate'),
                'expDate' => $this->input->post('expDate')[0],
                'store_id' => $this->input->post('store')[0],
                'description' => $this->input->post('description')[0],
            );

            $update = $this->model_received_products->update($data, $receivable_id);
            if($update == true) {
                $this->session->set_flashdata('success', 'Receivable successfully updated');
                redirect('receivables/', 'refresh');
            }
            else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('receivables/update/'.$receivable_id, 'refresh');
            }
        }
        else {
            // attributes 
            $attribute_data = $this->model_attributes->getActiveAttributeData();

            $attributes_final_data = array();
            foreach ($attribute_data as $k => $v) {
                $attributes_final_data[$k]['attribute_data'] = $v;

                $value = $this->model_attributes->getAttributeValueData($v['id']);

                $attributes_final_data[$k]['attribute_value'] = $value;
            }

            $receivable_data = $this->model_received_products->getReceivedProductData($receivable_id);
            $this->data['receivable_data'] = $receivable_data;
            $product = $this->model_products->getProductData($receivable_data['productId']);
            $this->data['receivable_data']['name'] = $product['name'];
            $this->data['receivable_data']['image'] = $product['image'];
            
            // false case
            $this->data['attributes'] = $attributes_final_data;
            $this->data['brands'] = $this->model_brands->getActiveBrands();         
            $this->data['category'] = $this->model_category->getActiveCategroy();           
            $this->data['stores'] = $this->model_stores->getActiveStore();          
            $this->data['products'] = $this->model_products->getProductData();
            
            $user_id = $this->session->userdata('id');
            $this->data['user_data'] = $this->model_users->getUserData($user_id);
            $this->render_template('receivables/edit', $this->data); 
        }   
	}

    /*
    * It removes the data from the database
    * and it returns the response into the json format
    */
	public function remove()
	{
        if(!in_array('deleteReceivable', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
        $receivable_id = $this->input->post('receivable_id');

        $response = array();
        if($receivable_id) {
            $delete = $this->model_products->remove($receivable_id);
            if($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed"; 
            }
            else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the receivable information";
            }
        }
        else {
            $response['success'] = false;
            $response['messages'] = "Refersh the page again!!";
        }

        echo json_encode($response);
	}

}
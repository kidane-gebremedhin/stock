<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Units extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Units';

		$this->load->model('model_units');
	}

	/* 
	* It only redirects to the manage product page and
	*/
	public function index()
	{
		if(!in_array('viewUnit', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$result = $this->model_units->getUnitData();

		$this->data['results'] = $result;

		$this->render_template('units/index', $this->data);
	}

	/*
	* Fetches the unit data from the unit table 
	* this function is called from the datatable ajax function
	*/
	public function fetchUnitData()
	{
		$result = array('data' => array());

		$data = $this->model_units->getUnitData();
		foreach ($data as $key => $value) {

			// button
			$buttons = '';

			if(in_array('viewUnit', $this->permission)) {
				$buttons .= '<button type="button" class="btn btn-default" onclick="editUnit('.$value['id'].')" data-toggle="modal" data-target="#editUnitModal"><i class="fa fa-pencil"></i></button>';	
			}
			
			if(in_array('deleteUnit', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeUnit('.$value['id'].')" data-toggle="modal" data-target="#removeUnitModal"><i class="fa fa-trash"></i></button>
				';
			}				

			$status = ($value['active'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

			$result['data'][$key] = array(
				$value['name'],
				$status,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	/*
	* It checks if it gets the unit id and retreives
	* the unit information from the unit model and 
	* returns the data into json format. 
	* This function is invoked from the view page.
	*/
	public function fetchUnitDataById($id)
	{
		if($id) {
			$data = $this->model_units->getUnitData($id);
			echo json_encode($data);
		}

		return false;
	}

	/*
	* Its checks the unit form validation 
	* and if the validation is successfully then it inserts the data into the database 
	* and returns the json format operation messages
	*/
	public function create()
	{

		if(!in_array('createUnit', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		$this->form_validation->set_rules('unit_name', 'Unit name', 'trim|required');
		$this->form_validation->set_rules('active', 'Active', 'trim|required');

		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

        if ($this->form_validation->run() == TRUE) {
        	$data = array(
        		'name' => $this->input->post('unit_name'),
        		'active' => $this->input->post('active'),	
        	);

        	$create = $this->model_units->create($data);
        	if($create == true) {
        		$response['success'] = true;
        		$response['messages'] = 'Succesfully created';
        	}
        	else {
        		$response['success'] = false;
        		$response['messages'] = 'Error in the database while creating the unit information';			
        	}
        }
        else {
        	$response['success'] = false;
        	foreach ($_POST as $key => $value) {
        		$response['messages'][$key] = form_error($key);
        	}
        }

        echo json_encode($response);

	}

	/*
	* Its checks the unit form validation 
	* and if the validation is successfully then it updates the data into the database 
	* and returns the json format operation messages
	*/
	public function update($id)
	{
		if(!in_array('updateUnit', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		if($id) {
			$this->form_validation->set_rules('edit_unit_name', 'Unit name', 'trim|required');
			$this->form_validation->set_rules('edit_active', 'Active', 'trim|required');

			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

	        if ($this->form_validation->run() == TRUE) {
	        	$data = array(
	        		'name' => $this->input->post('edit_unit_name'),
	        		'active' => $this->input->post('edit_active'),	
	        	);

	        	$update = $this->model_units->update($data, $id);
	        	if($update == true) {
	        		$response['success'] = true;
	        		$response['messages'] = 'Succesfully updated';
	        	}
	        	else {
	        		$response['success'] = false;
	        		$response['messages'] = 'Error in the database while updated the unit information';			
	        	}
	        }
	        else {
	        	$response['success'] = false;
	        	foreach ($_POST as $key => $value) {
	        		$response['messages'][$key] = form_error($key);
	        	}
	        }
		}
		else {
			$response['success'] = false;
    		$response['messages'] = 'Error please refresh the page again!!';
		}

		echo json_encode($response);
	}

	/*
	* It removes the unit information from the database 
	* and returns the json format operation messages
	*/
	public function remove()
	{
		if(!in_array('deleteUnit', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		
		$unit_id = $this->input->post('unit_id');
		$response = array();
		if($unit_id) {
			$delete = $this->model_units->remove($unit_id);

			if($delete == true) {
				$response['success'] = true;
				$response['messages'] = "Successfully removed";	
			}
			else {
				$response['success'] = false;
				$response['messages'] = "Error in the database while removing the unit information";
			}
		}
		else {
			$response['success'] = false;
			$response['messages'] = "Refersh the page again!!";
		}

		echo json_encode($response);
	}

}
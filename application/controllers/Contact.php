<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {

	public $validation_for = '';

	public function __construct()
	{
		parent::__construct();
        $this->load->model('contact_model','contact');
        $this->load->helper('form');
		$this->load->library('form_validation');
    }

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('contact_view');
	}

	public function list()
	{
		$list = $this->contact->get_datatables();
		$data = array();
		foreach ($list as $contact) {
			$row = array();
			$row[] = $contact->phone_number;
			$row[] = $contact->name;
			$row[] = $contact->gender;
			$row[] = $contact->email;
			$row[] = $contact->address;

			//add html for action
            $row[] = "<a class='btn btn-sm btn-primary' href='javascript:void(0)' title='Edit' onclick=\"edit_contact('{$contact->id}')\"><i class='fa fa-edit'></i> Edit</a>"
                    ." <a class='btn btn-sm btn-danger' href='javascript:void(0)' title='Hapus' onclick=\"delete_contact('{$contact->id}')\"><i class='fa fa-trash'></i> Delete</a>";
		
			$data[] = $row;
		}

        $output = array(
                    "draw" => @$_POST['draw'],
                    "recordsTotal" => $this->contact->count_all(),
                    "recordsFiltered" => $this->contact->count_filtered(),
                    "data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function edit($id)
	{
		$data = $this->contact->get_by_id($id);
		echo json_encode($data);
	}

	public function add()
	{
		$this->validation_for = 'add';
        $data = array();
		$data['status'] = TRUE;

		$this->_validate();

        if ($this->form_validation->run() == FALSE)
        {
            $errors = array(
                'phone_number' 	=> form_error('phone_number'),
                'name' 			=> form_error('name'),
                'gender' 		=> form_error('gender'),
                'email' 		=> form_error('email'),
                'address' 		=> form_error('address')
			);
            $data = array(
                'status' 		=> FALSE,
				'errors' 		=> $errors
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }else{
            $insert = array(
                    'phone_number' => $this->input->post('phone_number'),
                    'name' => $this->input->post('name'),
                    'gender' => $this->input->post('gender'),
                    'email' => $this->input->post('email'),
                    'address' => $this->input->post('address')
                );
            $insert = $this->contact->save($insert);
            $data['status'] = TRUE;
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
	}

	public function update()
	{
		$this->validation_for = 'update';
		$data = array();
		$data['status'] = TRUE;

		$this->_validate();

        if ($this->form_validation->run() == FALSE){
			$errors = array(
                'phone_number' 	=> form_error('phone_number'),
                'name' 			=> form_error('name'),
                'gender' 		=> form_error('gender'),
                'email' 		=> form_error('email'),
                'address' 		=> form_error('address')
			);
            $data = array(
                'status' 		=> FALSE,
				'errors' 		=> $errors
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
		}else{
			$update = array(
				'phone_number' 	=> $this->input->post('phone_number'),
                'name' 			=> $this->input->post('name'),
                'gender'	 	=> $this->input->post('gender'),
                'email' 		=> $this->input->post('email'),
                'address' 		=> $this->input->post('address')
			);
			$this->contact->update(array('id' => $this->input->post('id')), $update);
			$data['status'] = TRUE;
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
	}

	public function delete($id)
	{
		$this->contact->delete_by_id($id);
		$data['status'] = TRUE;
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	private function _validate()
	{
		$this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|max_length[30]');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|in_list[male,female]');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
		
		$email_unique = '';
		$phone_number_unique = '';
		$getData = $this->contact->get_by_id($this->input->post('id'));

		if($this->validation_for == 'add'){
			$email_unique = '|is_unique[contact_list.email]';
			$phone_number_unique = '|is_unique[contact_list.phone_number]';
		}else if($this->validation_for == 'update'){
			if($this->input->post('email') != $getData->email){
				$email_unique = '|is_unique[contact_list.email]';
			}
			if($this->input->post('phone_number') != $getData->phone_number){
				$phone_number_unique = '|is_unique[contact_list.phone_number]';
			}
		}
		
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email'.$email_unique);
		$this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required|numeric|min_length[11]|max_length[15]'.$phone_number_unique);
	}

}

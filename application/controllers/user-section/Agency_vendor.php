<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*
*/
class Agency_vendor extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        error_reporting(E_ALL ^ (E_NOTICE));
        $this->load->model('back/Agency_vendor_model');
        //$this->load->model(array('Login_model','backend/Beneficiary_model','backend/Event_model'));
        $this->load->library(array('form_validation'));
        $this->load->helper(array('url','form'));
        $this->load->database('default');
               
    }

    public function index()

    {    
     if($this->session->userdata('roles') == TRUE && $this->session->userdata('roles') == 'agency')
        {
            $id_agency = $this->session->userdata('id_agency');

            $id_vendor_arr = $this->Agency_vendor_model->get_id_vendors($id_agency);
            if (is_array($id_vendor_arr))
                {
                 foreach($id_vendor_arr as $c => $k)
                     {

                          $id_vendor = $id_vendor_arr[$c]->vendor_id;
                          $rowVendor[$c] = $this->Agency_vendor_model->get_vendor($id_vendor);
                     }

                }
            



            //$vendors = $this->Agency_vendor_model->get_vendors($id_agency);

            $data['vendor'] = $rowVendor;
            
           /*if (is_array($daycares)){
                foreach ($daycares as $k => $daycare) {

                    $rowCourse = $this->Clas_model->get_course($class->id_course);
                    $course[$k] = $rowCourse->name;

                   
                }

            }*/


        $data['active'] = 'vendor'; 
        $data['title'] = 'Vendor';
        $this->load->view('back/header_view', $data);
        $this->load->view('back/agency/agency_vendor_list_view', $data); 
        $this->load->view('back/footer_view', $data);
     }
    


    }
 function add_new()
    {

        if($this->session->userdata('roles') == TRUE && $this->session->userdata('roles') == 'agency')
        {
            $data['title'] = 'New Vendor';    
            $data['active'] = 'vendors';
            $data['option'] = 'no';
            $data['legend'] = 'New Vendor'; 
            $data['button'] = 'Create';
            $data['action'] = 'user-section/agency-vendor/create_vendor/'; 
           
            $this->load->view('back/header_view', $data);
            $this->load->view('back/agency/agency_vendor_view', $data);
            $this->load->view('back/footer_view', $data); 
        }
        else {
            redirect(base_url().'home');
        }

    }
function create_daycare()
    {   
    if($this->session->userdata('roles') == TRUE && $this->session->userdata('roles') == 'agency')
    {

        //echo "probando";
        if(isset($_POST['grabar']) and $_POST['grabar'] == 'si')
        {
            
            
            //SI EXISTE EL CAMPO OCULTO LLAMADO GRABAR CREAMOS LAS VALIDACIONES
                                    
            $this->form_validation->set_rules('name','Name','required|trim|max_length[250]');
             $this->form_validation->set_rules('phone','Phone Number','required|trim|max_length[45]');
              $this->form_validation->set_rules('address','Address','required|trim|max_length[250]');
                $this->form_validation->set_rules('children','Children Quantity','trim|is_natural_no_zero|max_length[11]');
                 $this->form_validation->set_rules('owner','Owner','required|trim|max_length[45]');
                  $this->form_validation->set_rules('dirname',"Director's Name",'required|trim|max_length[45]');
                  $this->form_validation->set_rules('email', 'E-mail', 'required|trim|valid_email|is_unique[users.email]');

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
          
          
            if($this->form_validation->run()==FALSE)
            {
                $this->add_new();
            }else{
                
                $name = $this->input->post('name');
                $phone = $this->input->post('phone');
                $address = $this->input->post('address');
                $children = $this->input->post('children');
                $owner = $this->input->post('owner');
                $dirname = $this->input->post('dirname');
                $email = $this->input->post('email');
                $id_agency = $this->session->userdata('id_agency');
                $password ='1234567';
                $pw = md5($password); $id_rol = 2;
                $type_emp = 1;


                                        
                //ENVÍAMOS LOS DATOS AL MODELO CON LA SIGUIENTE LÍNEA
                $id_user = $this->Agency_daycare_model->new_user($email,$pw,$id_rol);
                $id_daycare = $this->Agency_daycare_model->new_daycare($id_agency,$name,$phone,$address,$children,$owner);
                $id_administrator = $this->Agency_daycare_model->new_administrator($id_daycare,$id_user,$type_emp,$dirname);
                
                if ($id_daycare != Null) {

                    echo "<script> if (confirm('Do you want to continue?')){
                        window.location='".base_url()."user-section/agency-daycare/add-new"."'
                    } else {
                        window.location='".base_url()."user-section/agency-daycare"."'
                    }</script>";

                    
                }
                //redirect(base_url().'agency-daycare/add_new');

                
        }
    }
        
      
      } 
    }

 function edit($id_daycare)
    {
        if($this->session->userdata('roles') == TRUE && $this->session->userdata('roles') == 'agency')
        {
            $data['title'] = 'Edit Daycare';    
            $data['active'] = 'daycare';
            $data['legend'] = 'Edit Daycare';
            $data['daycare'] = $this->Agency_daycare_model->get_daycare($id_daycare);
            $data['button'] = 'Save';
            $data['option'] = 'yes';
            $data['action'] = 'user-section/agency-daycare/update_daycare/'.$id_daycare;

            $id_agency = $this->session->userdata('id_agency');
             $this->load->view('back/header_view', $data);
            $this->load->view('back/agency/agency_daycare_view', $data);
            $this->load->view('back/footer_view', $data); 
        }
    }

    function update_daycare()
{
if($this->session->userdata('roles') == TRUE && $this->session->userdata('roles') == 'agency')
{


   if(isset($_POST['grabar']) and $_POST['grabar'] == 'si')
        {
            $id_daycare = $this->input->post('id_daycare');
            //SI EXISTE EL CAMPO OCULTO LLAMADO GRABAR CREAMOS LAS VALIDACIONES
                                    
             $this->form_validation->set_rules('name','Name','required|trim|max_length[250]');
             $this->form_validation->set_rules('phone','Phone Number','required|trim|max_length[45]');
              $this->form_validation->set_rules('address','Address','required|trim|max_length[250]');
                $this->form_validation->set_rules('children','Children Quantity','trim|is_natural_no_zero|max_length[11]');
                 $this->form_validation->set_rules('owner','Owner','required|trim|max_length[45]');

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
          
          
            if($this->form_validation->run()==FALSE)
            {
                $this->edit();
            }else{
                
                $name = $this->input->post('name');
                $phone = $this->input->post('phone');
                $address = $this->input->post('address');
                $children = $this->input->post('children');
                $owner = $this->input->post('owner');

                $this->Agency_daycare_model->update_daycare($id_daycare,$name,$phone,$address,$children,$owner);
               
                               

                redirect(base_url().'user-section/agency-daycare');
            }
        }








}
}

}
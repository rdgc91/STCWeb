<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $this->load->model(array('front/Signup_model'));
        $this->load->library(array('form_validation'));
        $this->load->helper(array('url','form'));
        $this->load->database('default');
        //$this->load->helper('server_root');
        //$this->removeCache();
    }
    function index()
    {

        $data['title'] = 'Sign Up';    
        $data['active'] = 'signup';

        $this->load->view('front/header_view', $data);
        //$this->load->view('front/nav_view', $data);
        $this->load->view('front/signup_view', $data);
        $this->load->view('front/footer_view', $data); 
        

    }

    function create_subscription()

    {

        if(isset($_POST['grabar']) and $_POST['grabar'] == 'si')
        {

            //SI EXISTE EL CAMPO OCULTO LLAMADO GRABAR CREAMOS LAS VALIDACIONES
                                    
            $this->form_validation->set_rules('name','Name','required|trim|max_length[250]');
             $this->form_validation->set_rules('phone','Phone Number','required|trim|max_length[45]');
              $this->form_validation->set_rules('address','Address','required|trim|max_length[250]');
                $this->form_validation->set_rules('dirname','Director','required|trim|max_length[250]');
                $this->form_validation->set_rules('children','Number of Children','required|trim|is_natural_no_zero');
                $this->form_validation->set_rules('owner','Owner','required|trim|max_length[250]');
                 
                  $this->form_validation->set_rules('email', 'E-mail', 'required|trim|valid_email|is_unique[users.email]');

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

          
          
            if($this->form_validation->run()==FALSE)
            {
                $this->index();
            }else{
                
                $name = $this->input->post('name');
                $phone = $this->input->post('phone');
                $address = $this->input->post('address');
                $director = $this->input->post('dirname');
                $children = $this->input->post('children');
                $owner = $this->input->post('owner');
                $email = $this->input->post('email');
                $password ='1234567';
                $pw = md5($password); $id_rol = 3;
                


                                        
                //ENVÍAMOS LOS DATOS AL MODELO CON LA SIGUIENTE LÍNEA
                //$id_user = $this->Signup_model->new_user($email,$pw,$id_rol);
                //$id_agency = $this->Signup_model->new_agency($name,$phone,$address,$director,$id_user);

                
                //redirect(base_url().'signup');
                 $data['title'] = 'Sign Up';    
                    $data['name'] = $name;
                    $data['phone'] = $phone;
                     $data['address'] = $address;
                      $data['director'] = $director;
                       $data['children'] = $children;
                        $data['owner'] = $owner;
                       $data['email'] = $email;
                       $data['action'] = 'https://www.paypal.com/cgi-bin/webscr';
                



                    $this->load->view('front/header_view', $data);
                    //$this->load->view('front/nav_view', $data);
                    $this->load->view('front/signup_pp_view', $data);
                    $this->load->view('front/footer_view', $data); 

                
        }


        




         
       

    }
  }

     function create_pp_subscription()

    {

            //SI EXISTE EL CAMPO OCULTO LLAMADO GRABAR CREAMOS LAS VALIDACIONES
               
           if( $this->session->userdata('a_name') != null )
        {
                
               $name = $this->session->userdata('a_name');
                $phone = $this->session->userdata('a_phone');
                 $address = $this->session->userdata('a_address');
                 $director = $this->session->userdata('a_director');
                 $children = $this->session->userdata('a_children');
                 $owner = $this->session->userdata('a_owner');
                $email = $this->session->userdata('a_email');

                $type_emp = 1;
                

                $password ='1234567';
                $pw = md5($password); $id_rol = 3;
                


                                        
                //ENVÍAMOS LOS DATOS AL MODELO CON LA SIGUIENTE LÍNEA
                $id_user = $this->Signup_model->new_user($email,$pw,$id_rol);
                $id_daycare = $this->Signup_model->new_daycare($name,$phone,$address,$children,$owner);
                $id_administrator = $this->Signup_model->new_administrator($id_daycare,$id_user,$type_emp,$director);

                 if ($id_daycare != Null) {

                        echo '<script type="text/javascript">'; 
                        echo 'alert("Successful Registration!");'; 
                        echo 'window.location.href = "'.base_url().'signup";';
                        echo '</script>';
                                            
                }

                
        }

      }


        

    

    function check_default2($post_string)
    {
      return $post_string == '-1' ? FALSE : TRUE;
    }




}
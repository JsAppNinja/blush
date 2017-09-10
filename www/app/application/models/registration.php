<?php
class Registration extends MY_Model
{
    protected static $fields = array(
        'completed' => 'int',
        'step' => 'int',
        'username' => 'string',
        'password' => 'string'
    );

    protected static $data_fields = array(
        'firstname' => 'string',
        'lastname' => 'string',
        'email' => 'email',
        'gender' => 'string',
        'birthday' => 'date',
        'school_occupation' => 'string',
        'referral' => 'string',
        'mobile_phone' => 'phone',
        'referral_counselor' => 'string',
        'referral_other' => 'string',
        'address' => 'string',
        'city' => 'string',
        'state' => 'string',
        'zipcode' => 'string',
        'timezone' => 'string',
        'username' => 'string',
        'preferred_coach' => 'string',
        'preferred_coaching_time' => 'string',
        'coaching_qualifications' => 'string',
        'parent_email' => 'email',
        'parent_consent' => 'int',

        'overall_mood' => 'string',
        'physical_appearance' => 'string',
        'relationships' => 'string',
        'stress_level' => 'string',
        'future_optimistic' => 'string',

        'counselor_before' => 'string',
        'counselor_before_more' => 'string',
        'drugs_alcohol' => 'string',
        'drugs_alcohol_more' => 'string',
        'sleeping_changes' => 'string',
        'sleeping_changes_more' => 'string',
        'medical_diagnosis' => 'string',
        'medical_diagnosis_more' => 'string',
        'suicide_homicide' => 'string',
        'suicide_homicide_more' => 'string',

        'pop_culture' => 'string',
        'interest' => 'string',
        'dream' => 'string',
        'family' => 'string',
        'focus' => 'string',

        'watch_thought' => 'string',
        'practical_imaginative' => 'string',
        'objective_subjective' => 'string',
        'flow_opinions' => 'string'

    );

    function get_data_fields() {
        return static::$data_fields;
    }

    function get_scope()
    {
        return "registration";
    }


    function load_by_user_id($user_id = 0)
    {
        if (intval($user_id)) {
            $query = $this->db->get_where($this->get_scope(), array("user_id" => $user_id));
            $registration = $query->row();
            if($registration) {
                return $this->after_load($registration);
            }
        }
        return $this->blank();
    }
    /**
     * Update the password and created the salt
     * @param $data - data going into the add()
     */
    public function update_add_data($data) {
        $salt = $this->create_salt();
        $password = sha1($data['password'].$salt);
        $data['password'] = $password;
        $data['salt'] = $salt;

        $this->load->library('encrypt');
        if(isset($data['data']) && trim($data['data'])) {
            $data['data'] = $this->encrypt->encode($data['data']);
        }
        return $data;
    }

    /**
     * Update the password and created the salt
     * @param $data - data going into the add()
     */
    public function update_update_data($data) {
        if(isset($data['password'])) {
            $salt = $this->create_salt();
            $password = sha1($data['password'].$salt);
            $data['password'] = $password;
            $data['salt'] = $salt;
        }


        $this->load->library('encrypt');
        if(isset($data['data']) && trim($data['data'])) {
            $data['data'] = $this->encrypt->encode($data['data']);
        }

        return $data;
    }

    function add_data()
    {
        $this->load->library('uuid');

        $data = array(
            'uuid' => $this->uuid->v4(),
            'ip_address' => $_SERVER["REMOTE_ADDR"],
            'created' => timestamp_to_mysqldatetime(now()),
            'completed' => 0
        );
        return $data;
    }

    function blank()
    {
        $registration = parent::blank();
        $registration->step = 1;
        $registration->data = '{}';
        $registration->mobile_phone = '';
        $registration->firstname = '';
        $registration->lastname = '';
        $registration->email = '';
        $registration->birthday = '';
        $registration->gender = '';
        $registration->referral = '';
        $registration->referral_counselor = '';
        $registration->referral_other = '';
        $registration->address = '';
        $registration->state = '';
        $registration->zipcode = '';
        $registration->city = '';
        $registration->school_occupation = '';
        $registration->username = '';
        $registration->password = '';
        $registration->preferred_coach = '';
        $registration->preferred_coaching_time = '';
        $registration->coaching_qualifications = '';
        $registration->parent_email = '';
        $registration->parent_consent = 0;

        return $registration;
    }

    function after_load($object)
    {
        $this->load->library('encrypt');
        if(isset($object->data)) {
            $data = $this->encrypt->decode($object->data);
            if($data) {
                $object->data = $data;
            }
        }
        return $object;
    }
}
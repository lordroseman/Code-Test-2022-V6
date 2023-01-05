<?php

namespace App\Services\Job;

use App\Models\Job; 
use Illuminate\Validation\ValidationException;

class CreateJob {
    private $request;
    private $user;

    public function __construct($user, $request) {
        $this->request = $request;
    }

    public function save() {
        $immediatetime = 5;
        $consumer_type = $user->userMeta->consumer_type;
        if ($user->user_type == env('CUSTOMER_ROLE_ID')) {
            $cuser = $user; 
 
            $data['customer_phone_type']  = isset($data['customer_phone_type']) ? 'yes' : 'no';

            if (isset($data['customer_physical_type'])) {
                $data['customer_physical_type'] = 'yes';
                $response['customer_physical_type'] = 'yes';
            } else {
                $data['customer_physical_type'] = 'no';
                $response['customer_physical_type'] = 'no';
            }

            if ($data['immediate'] == 'yes') {
                $due_carbon =  now()->addMinute($immediatetime);
                $data['due'] = $due_carbon->format('Y-m-d H:i:s');
                // $data['immediate'] = 'yes';
                $data['customer_phone_type'] = 'yes';
                $response['type'] = 'immediate';

            } else {
                $due = $data['due_date'] . " " . $data['due_time'];
                $response['type'] = 'regular';
                $due_carbon = Carbon::createFromFormat('m/d/Y H:i', $due);
                $data['due'] = $due_carbon->format('Y-m-d H:i:s');

                abort_if($due_carbon->isPast(), 422,  ["status" => 'fail', "message" => "Can't create booking in past"]);
               
            }

            // what if male and female exists in $data['job_for']
            if (in_array('male', $data['job_for'])) {
                $data['gender'] = 'male';
            } else if (in_array('female', $data['job_for'])) {
                $data['gender'] = 'female';
            }

            if (in_array('normal', $data['job_for'])) {
                $data['certified'] = 'normal';
            } else if (in_array('certified', $data['job_for'])) {
                $data['certified'] = 'yes';
            } else if (in_array('certified_in_law', $data['job_for'])) {
                $data['certified'] = 'law';
            } else if (in_array('certified_in_helth', $data['job_for'])) {
                $data['certified'] = 'health';
            }

        
            if (in_array('normal', $data['job_for']) && in_array('certified', $data['job_for'])) {
                $data['certified'] = 'both';
            } else if(in_array('normal', $data['job_for']) && in_array('certified_in_law', $data['job_for']))  {
                $data['certified'] = 'n_law';
            } else if(in_array('normal', $data['job_for']) && in_array('certified_in_helth', $data['job_for']))  {
                $data['certified'] = 'n_health';
            }

            if ($consumer_type == 'rwsconsumer') {
                $data['job_type'] = 'rws';
            } else if ($consumer_type == 'ngo') {
                $data['job_type'] = 'unpaid';
            } else if ($consumer_type == 'paid') {
                $data['job_type'] = 'paid';
            }


            $data['b_created_at'] = date('Y-m-d H:i:s');
            if (isset($due)) {
                $data['will_expire_at'] = TeHelper::willExpireAt($due, $data['b_created_at']);
            }

            $data['by_admin'] = isset($data['by_admin']) ? $data['by_admin'] : 'no';

            $job = $cuser->jobs()->create($data);

            $response['status'] = 'success';
            $response['id'] = $job->id;
            $data['job_for'] = array();
            if ($job->gender != null) {
                if ($job->gender == 'male') {
                    $data['job_for'][] = 'Man';
                } else if ($job->gender == 'female') {
                    $data['job_for'][] = 'Kvinna';
                }
            }
            
            if ($job->certified != null) {
                if ($job->certified == 'both') {
                    $data['job_for'][] = 'normal';
                    $data['job_for'][] = 'certified';
                } else if ($job->certified == 'yes') {
                    $data['job_for'][] = 'certified';
                } else {
                    $data['job_for'][] = $job->certified;
                }
            }

            $data['customer_town'] = $cuser->userMeta->city;
            $data['customer_type'] = $cuser->userMeta->customer_type;

            //Event::fire(new JobWasCreated($job, $data, '*'));

//            $this->sendNotificationToSuitableTranslators($job->id, $data, '*');// send Push for New job posting
        } else {
            throw ValidationException::withMessages(['status' => 'fail', "message" => "Translator can not create booking"]);
            
        }

        return $response;
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        
        return [
            'from_language_id' => 'required',
            'duration' => 'required',
            'due_date' => 'required_if:immediate,no', 
            'due_time' => 'required_if:immediate,no', 
            'customer_phone_type' => 'required_if:immediate,no',
            'customer_physical_type' => 'required_if:immediate,no', 
            'job_for' => 'required', 
        ];
       
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'from_language_id.required' => 'Du måste fylla in alla fält',
            'duration.required' => 'Du måste fylla in alla fält' ,
            'due_date.required_if' => 'Du måste fylla in alla fält',
            'due_time.required_if' => 'Du måste fylla in alla fält',
            'customer_phone_type.required_if' => 'Du måste göra ett val här',
            'customer_physical_type.required_if' => 'Du måste göra ett val här',
        ];
    }
    
    
}

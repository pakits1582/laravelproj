<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentScheduleRequest extends FormRequest
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

    public function messages()
    {
        return [
            'period_id.unique' => 'All fields are duplicate entry!',
            'educational_level_id.unique' =>  'All fields are duplicate entry!',
            'year_level.unique' =>  'All fields are duplicate entry!',
            'payment_mode_id.unique' =>  'All fields are duplicate entry!',
            'description.unique' =>  'All fields are duplicate entry!',
            'tuition.unique' =>  'All fields are duplicate entry!',
            'miscellaneous.unique' => 'All fields are duplicate entry!',
            'others.unique' =>  'All fields are duplicate entry!',
            'payment_type.unique' =>  'All fields are duplicate entry!',


        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        return [
            'period_id' => ['required', Rule::unique('payment_schedules')->where(fn ($query) => $query
                                                                                ->where('educational_level_id', $this->educational_level_id)
                                                                                ->where('year_level', $this->year_level)
                                                                                ->where('payment_mode_id', $this->payment_mode_id)
                                                                                ->where('description', $this->description)
                                                                                ->where('tuition', $this->tuition)
                                                                                ->where('miscellaneous', $this->miscellaneous)
                                                                                ->where('others', $this->others)
                                                                                ->where('payment_type', $this->payment_type)
            )->ignore($this->paymentschedule->id)],
            'educational_level_id' => ['required', Rule::unique('payment_schedules')->where(fn ($query) => $query
                                                                                ->where('period_id', $this->period_id)
                                                                                ->where('year_level', $this->year_level)
                                                                                ->where('payment_mode_id', $this->payment_mode_id)
                                                                                ->where('description', $this->description)
                                                                                ->where('tuition', $this->tuition)
                                                                                ->where('miscellaneous', $this->miscellaneous)
                                                                                ->where('others', $this->others)
                                                                                ->where('payment_type', $this->payment_type)
            )->ignore($this->paymentschedule->id)],
            'year_level' => [Rule::unique('payment_schedules')->where(fn ($query) => $query
                                                                                ->where('period_id', $this->period_id)
                                                                                ->where('educational_level_id', $this->educational_level_id)
                                                                                ->where('payment_mode_id', $this->payment_mode_id)
                                                                                ->where('description', $this->description)
                                                                                ->where('tuition', $this->tuition)
                                                                                ->where('miscellaneous', $this->miscellaneous)
                                                                                ->where('others', $this->others)
                                                                                ->where('payment_type', $this->payment_type)
            )->ignore($this->paymentschedule->id)],
            'payment_mode_id' => ['required', Rule::unique('payment_schedules')->where(fn ($query) => $query
                                                                                ->where('period_id', $this->period_id)
                                                                                ->where('educational_level_id', $this->educational_level_id)
                                                                                ->where('year_level', $this->year_level)
                                                                                ->where('description', $this->description)
                                                                                ->where('tuition', $this->tuition)
                                                                                ->where('miscellaneous', $this->miscellaneous)
                                                                                ->where('others', $this->others)
                                                                                ->where('payment_type', $this->payment_type)
            )->ignore($this->paymentschedule->id)],
            'description' => ['required', Rule::unique('payment_schedules')->where(fn ($query) => $query
                                                                                ->where('period_id', $this->period_id)
                                                                                ->where('educational_level_id', $this->educational_level_id)
                                                                                ->where('year_level', $this->year_level)
                                                                                ->where('payment_mode_id', $this->payment_mode_id)
                                                                                ->where('tuition', $this->tuition)
                                                                                ->where('miscellaneous', $this->miscellaneous)
                                                                                ->where('others', $this->others)
                                                                                ->where('payment_type', $this->payment_type)
            )->ignore($this->paymentschedule->id)],
            'tuition' => ['sometimes','nullable','regex:/^\d*(\.\d{1,2})?$/', Rule::unique('payment_schedules')->where(fn ($query) => $query
                                                                                ->where('period_id', $this->period_id)
                                                                                ->where('educational_level_id', $this->educational_level_id)
                                                                                ->where('year_level', $this->year_level)
                                                                                ->where('payment_mode_id', $this->payment_mode_id)
                                                                                ->where('description', $this->description)
                                                                                ->where('miscellaneous', $this->miscellaneous)
                                                                                ->where('others', $this->others)
                                                                                ->where('payment_type', $this->payment_type)
            )->ignore($this->paymentschedule->id)],
            'miscellaneous' => ['sometimes','nullable','regex:/^\d*(\.\d{1,2})?$/', Rule::unique('payment_schedules')->where(fn ($query) => $query
                                                                                ->where('period_id', $this->period_id)
                                                                                ->where('educational_level_id', $this->educational_level_id)
                                                                                ->where('year_level', $this->year_level)
                                                                                ->where('payment_mode_id', $this->payment_mode_id)
                                                                                ->where('description', $this->description)
                                                                                ->where('tuition', $this->tuition)
                                                                                ->where('others', $this->others)
                                                                                ->where('payment_type', $this->payment_type)
            )->ignore($this->paymentschedule->id)],
            'others' => ['sometimes','nullable','regex:/^\d*(\.\d{1,2})?$/', Rule::unique('payment_schedules')->where(fn ($query) => $query
                                                                                ->where('period_id', $this->period_id)
                                                                                ->where('educational_level_id', $this->educational_level_id)
                                                                                ->where('year_level', $this->year_level)
                                                                                ->where('payment_mode_id', $this->payment_mode_id)
                                                                                ->where('description', $this->description)
                                                                                ->where('tuition', $this->tuition)
                                                                                ->where('miscellaneous', $this->miscellaneous)
                                                                                ->where('payment_type', $this->payment_type)
            )->ignore($this->paymentschedule->id)],
            'payment_type' => ['required', Rule::unique('payment_schedules')->where(fn ($query) => $query
                                                                                ->where('period_id', $this->period_id)
                                                                                ->where('educational_level_id', $this->educational_level_id)
                                                                                ->where('year_level', $this->year_level)
                                                                                ->where('payment_mode_id', $this->payment_mode_id)
                                                                                ->where('description', $this->description)
                                                                                ->where('tuition', $this->tuition)
                                                                                ->where('miscellaneous', $this->miscellaneous)
                                                                                ->where('others', $this->others)
            )->ignore($this->paymentschedule->id)],
            
        ];
        // return [
        //     'period_id' => 'required',
        //     'educational_level_id' => 'required',
        //     'year_level' => [],
        //     'payment_mode_id' => 'required',
        //     'description' => 'required',
        //     'tuition' => 'sometimes|nullable|regex:/^\d*(\.\d{1,2})?$/',
        //     'miscellaneous'=> 'sometimes|nullable|regex:/^\d*(\.\d{1,2})?$/',
        //     'others'       => 'sometimes|nullable|regex:/^\d*(\.\d{1,2})?$/',
        //     'payment_type' => 'required'
        // ];
    }
}

<?php

namespace Cryptounity\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Cryptounity\Rules\Stacking\StackingAmount;

class StackingCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:1'
        ];
    }

    public function validate() {

        $rules = $this->rules();

        return $this->validate($rules);

    }
}

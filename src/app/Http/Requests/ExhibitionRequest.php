<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:100',
            'description' => 'required|string|max:255',
            'image' => 'required|mimes:jpeg,png|max:2048',
            'categories' => 'required|array',
            'condition_id' => 'required',
            'price' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品名は255文字以内で入力してください',
            'image.required' => '商品画像をアップロードしてください',
            'image.mimes' => '商品画像は.jpegまたは.png形式でアップロードしてください',
            'categories.required' => 'カテゴリーを1つ以上選択してください',
            'categories.array' => 'カテゴリーを1つ以上選択してください',
            'condition_id.required' => '商品の状態を選択してください',
            'price.required' => '販売価格を入力してください',
            'price.integer' => '販売価格は数値で入力してください',
            'price.min' => '販売価格は0円以上で入力してください',
        ];
    }
}

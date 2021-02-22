<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'accepted' => 'Thông tin :attribute phải được chấp nhận.',
    'active_url' => 'Thông tin :attribute không phải là một URL hợp lệ.',
    'after' => 'Thông tin :attribute phải là một ngày sau :date.',
    'after_or_equal' => 'Thông tin :attribute phải là một ngày sau hoặc bằng :date.',
    'alpha' => 'Thông tin :attribute chỉ có thể chứa các chữ cái.',
    'alpha_dash' => 'Thông tin :attribute chỉ có thể chứa các chữ cái, số, dấu gạch ngang và dấu gạch dưới.',
    'alpha_num' => 'Thông tin :attribute chỉ có thể chứa các chữ cái và số.',
    'array' => 'Thông tin :attribute phải là một mảng.',
    'before' => 'Thông tin :attribute phải là một ngày trước :date.',
    'before_or_equal' => 'Thông tin :attribute phải là một ngày trước hoặc bằng :date.',
    'between' => [
        'numeric' => 'Thông tin :attribute phải ở giữa :min và :max.',
        'file' => 'Thông tin :attribute phải ở giữa :min và :max kilobytes.',
        'string' => 'Thông tin :attribute phải ở giữa :min và :max ký tự.',
        'array' => 'Thông tin :attribute phải ở giữa :min và :max.',
    ],
    'boolean' => 'Thông tin :attribute trường phải đúng hoặc sai.',
    'confirmed' => 'Thông tin :attribute nhận đinh không phù hợp.',
    'date' => 'Thông tin :attribute không phải là ngày hợp lệ.',
    'date_equals' => 'Thông tin :attribute phải là một ngày bằng :date.',
    'date_format' => 'Thông tin :attribute không phù hợp với định dạng :format.',
    'different' => 'Thông tin :attribute và :other phải khác nhau.',
    'digits' => 'Thông tin :attribute phải :digits chữ số.',
    'digits_between' => 'Thông tin :attribute phải ở giữa :min và :max chữ số.',
    'dimensions' => 'Thông tin :attribute có kích thước hình ảnh không hợp lệ.',
    'distinct' => 'Thông tin :attribute trường có giá trị trùng lặp.',
    'email' => 'Thông tin :attribute phải là một địa chỉ email hợp lệ.',
    'ends_with' => 'Thông tin :attribute phải kết thúc bằng một trong những điều sau đây: :values',
    'exists' => 'Thông tin :attribute không tồn tại.',
    'file' => 'Thông tin :attribute phải là một file.',
    'filled' => 'Thông tin :attribute trường phải có giá trị.',
    'gt' => [
        'numeric' => 'Thông tin :attribute phải lớn hơn :value.',
        'file' => 'Thông tin :attribute phải lớn hơn :value kilobytes.',
        'string' => 'Thông tin :attribute phải lớn hơn :value ký tự.',
        'array' => 'Thông tin :attribute phải có nhiều hơn :value thành phần.',
    ],
    'gte' => [
        'numeric' => 'Thông tin :attribute phải lớn hơn hoặc bằng :value.',
        'file' => 'Thông tin :attribute phải lớn hơn hoặc bằng :value kilobytes.',
        'string' => 'Thông tin :attribute phải lớn hơn hoặc bằng :value ký tự.',
        'array' => 'Thông tin :attribute phải có :value hoặc nhiều hơn.',
    ],
    'image' => 'Thông tin :attribute phải là một ảnh.',
    'in' => 'Thông tin :attribute sai.',
    'in_array' => 'Thông tin :attribute trường không có trong :other.',
    'integer' => 'Thông tin :attribute phải là một số.',
    'ip' => 'Thông tin :attribute phải là một giá trị IP địa chỉ.',
    'ipv4' => 'Thông tin :attribute phải là một giá trị IPv4 địa chỉ.',
    'ipv6' => 'Thông tin :attribute phải là một giá trị IPv6 địa chỉ.',
    'json' => 'Thông tin :attribute phải là một giá trị JSON chuỗi.',
    'lt' => [
        'numeric' => 'Thông tin :attribute phải nhỏ hơn :value.',
        'file' => 'Thông tin :attribute phải nhỏ hơn :value kilobytes.',
        'string' => 'Thông tin :attribute phải nhỏ hơn :value ký tự.',
        'array' => 'Thông tin :attribute phải ít hơn :value thành phần.',
    ],
    'lte' => [
        'numeric' => 'Thông tin :attribute phải nhỏ hơn hoặc bằng :value.',
        'file' => 'Thông tin :attribute phải nhỏ hơn hoặc bằng :value kilobytes.',
        'string' => 'Thông tin :attribute phải nhỏ hơn hoặc bằng :value ký tự.',
        'array' => 'Thông tin :attribute không có nhiều hơn :value thành phần.',
    ],
    'max' => [
        'numeric' => 'Thông tin :attribute không thể lớn hơn :max.',
        'file' => 'Thông tin :attribute không thể lớn hơn :max kilobytes.',
        'string' => 'Thông tin :attribute không thể lớn hơn :max ký tự.',
        'array' => 'Thông tin :attribute không thể có nhiều hơn :max thành phần.',
    ],
    'mimes' => 'Thông tin :attribute phải là một tập tin: :values.',
    'mimetypes' => 'Thông tin :attribute phải là một tập tin: :values.',
    'min' => [
        'numeric' => 'Thông tin :attribute ít nhất phải lớn hơn :min.',
        'file' => 'Thông tin :attribute ít nhất phải lớn hơn :min kilobytes.',
        'string' => 'Thông tin :attribute ít nhất phải lớn hơn :min ký tự.',
        'array' => 'Thông tin :attribute phải có ít nhất phải lớn hơn:min thành phần.',
    ],
    'not_in' => 'Thông tin selected :attribute phải hợp lệ.',
    'not_regex' => 'Thông tin :attribute định dạng phải hợp lệ.',
    'numeric' => 'Thông tin :attribute phải là một số.',
    'present' => 'Thông tin :attribute trường phải có mặt.',
    'regex' => 'Thông tin :attribute định dạng phải hợp lệ.',
    'required' => 'Thông tin :attribute không được để trống.',
    'required_if' => 'Thông tin :attribute trường phải có khi :other là :value.',
    'required_unless' => 'Thông tin :attribute trường phải có khi không có  :other trong :values.',
    'required_with' => 'Thông tin :attribute trường phải có khi :values là có mặt.',
    'required_with_all' => 'Thông tin :attribute trường phải có khi :values là có mặt.',
    'required_without' => 'Thông tin :attribute trường phải có khi :values không có mặt.',
    'required_without_all' => 'Thông tin :attribute trường phải có khi không :values có mặt.',
    'same' => 'Thông tin :attribute và :other must match.',
    'size' => [
        'numeric' => 'Thông tin :attribute phải là :size.',
        'file' => 'Thông tin :attribute phải là :size kilobytes.',
        'string' => 'Thông tin :attribute phải là :size ký tự.',
        'array' => 'Thông tin :attribute phải chứa :size thành phần.',
    ],
    'starts_with' => 'Thông tin :attribute phải bắt đầu với một trong những điều sau đây: :values',
    'string' => 'Thông tin :attribute phải là một chuỗi.',
    'timezone' => 'Thông tin :attribute phải là một vùng.',
    'unique' => 'Thông tin :attribute đã tồn tại .',
    'uploaded' => 'Thông tin :attribute lỗi khi tải lên',
    'url' => 'Thông tin :attribute định dạng phải hợp lệ.',
    'uuid' => 'Thông tin :attribute phải là một giá trị UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | the following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */
    'attributes' => [
        'password' => 'Mật khẩu',
    ],
];

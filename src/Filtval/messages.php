<?php

return array(

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

    "required"      => "The :attribute is required",
    "contains"      => "The :attribute value must be :value",
    "max"           => [
        "numeric" => "The :attribute may not be greater than :value.",
        "file"    => "The :attribute may not be greater than :value kilobytes.",
        "string"  => "The :attribute may not be greater than :value characters.",
        "array"   => "The :attribute may not have more than :value items.",
    ],
    "min"           => [
        "numeric" => "The :attribute must be at least :value.",
        "file"    => "The :attribute must be at least :value kilobytes.",
        "string"  => "The :attribute must be at least :value characters.",
        "array"   => "The :attribute must have at least :value items.",
    ],
    "exact_len"     => "The :attribute len must be :value",
    "valid_email"   => "The :attribute format is invalid.",
    "alpha"         => "The :attribute may only contain letters.",
    "alpha_space"   => "The :attribute may only contain letters and spaces.",
    "alpha_dash"    => "The :attribute may only contain letters, numbers, and dashes.",
    "alpha_numeric" => "The :attribute may only contain letters and numbers.",
    "numeric"       => "The :attribute must be a number.",
    "integer"       => "The :attribute must be an integer.",

);
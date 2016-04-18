<?php

namespace Filtval;


class Validation
{

    private $error = false;
    private $errors = [];
    private $apply_rules = [];
    private $data = [];

    public function __construct($rules, $messages)
    {
        $this->parseRules($rules);
        $this->messages = $messages;
    }

    public function validate($data)
    {
        $this->data = $data;
        foreach ($this->apply_rules as $name => $rules) {
            foreach ($rules as $filter_function => $params) {
                if (!$value = call_user_func(array($this, $filter_function), $name, $params)) {
                    $this->errors[$name][] = $this->setError($filter_function, $name, $params);
                    $this->error = true;
                }
            }
        }

        return ($this->error) ? false : true;
    }

    private function setError($fname, $name, $value)
    {
        $message_key = str_replace('validate_', '', $fname);

        $error = $this->messages[$message_key];
        $error = str_replace(':attribute', $name, $error);
        if ($value) {
            $error = str_replace(':value', implode(',', $value), $error);
        }
        return $error;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    protected function parseRules($rules)
    {
        foreach ($rules as $name => $rule) {
            $this->apply_rules[$name] = $this->getNameRules($rule);
        }
    }

    private function getNameRules($rules)
    {
        $rules = explode('|', $rules);
        $p_rules = [];
        foreach ($rules as $rule) {
            $params = null;
            $name = 'validate_' . $rule;
            if (strpos($rule, ':') !== false) {
                $params = explode(':', $rule, 2);
                $name = 'validate_' . $params[0];
                $params = (!empty($params[1])) ? explode(':', $params[1]) : null;
            }

            $p_rules[$name] = $params;
        }
        return $p_rules;
    }

    protected function validate_required($field)
    {
        if (!isset($this->data[$field])) {
            return false;
        } else {
            if (is_null($this->data[$field])) {
                return false;
            } elseif (is_string($this->data[$field]) && trim($this->data[$field]) === '') {
                return false;
            } elseif ($this->data[$field] instanceof \Symfony\Component\HttpFoundation\File\File) {
                return (string)$this->data[$field]->getPath() != '';
            }
        }

        return true;
    }

    protected function validate_contains($field, $param)
    {
        if (!isset($this->data[$field])) {
            return false;
        }

        $params = array_map(function ($element) {
            return trim(strtolower($element));
        }, $param);
        $val = trim(strtolower($this->data[$field]));

        if (!in_array($val, $params)) { // valid, return nothing
            return false;
        }

        return true;
    }

    protected function validate_valid_email($field)
    {
        if (!isset($this->data[$field])) {
            return false;
        }

        $val = $this->data[$field];

        if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    protected function validate_max_len($field, $param)
    {
        if (!isset($this->data[$field])) {
            return false;
        }
        $val = $this->data[$field];
        $maxlen = (int)implode('', $param);

        if (function_exists('mb_strlen')) {
            if (mb_strlen($val) > $maxlen) {
                return false;
            }
        } else {
            if (strlen($val) > $maxlen) {
                return false;
            }
        }

        return true;
    }

    protected function validate_min_len($field, $param)
    {
        if (!isset($this->data[$field])) {
            return false;
        }
        $val = $this->data[$field];
        $maxlen = (int)implode('', $param);

        if (function_exists('mb_strlen')) {
            if (mb_strlen($val) < $maxlen) {
                return false;
            }
        } else {
            if (strlen($val) < $maxlen) {
                return false;
            }
        }

        return true;
    }

    protected function validate_exact_len($field, $param)
    {
        if (!isset($this->data[$field])) {
            return false;
        }
        $val = $this->data[$field];
        $maxlen = (int)implode('', $param);

        if (function_exists('mb_strlen')) {
            var_dump(mb_strlen($val));
            if (mb_strlen($val) == $maxlen) {
                return false;
            }
        } else {

            if (strlen($val) == $maxlen) {
                return false;
            }
        }

        return true;
    }

    protected function validate_alpha($field)
    {
        if (!isset($this->data[$field]) || empty($this->data[$field])) {
            return false;
        }

        if (!preg_match('/^([a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])+$/i',
                $this->data[$field]) !== false
        ) {
            return false;
        }

        return true;
    }

    protected function validate_alpha_space($field)
    {
        if (!isset($this->data[$field]) || empty($this->data[$field])) {
            return false;
        }

        if (!preg_match("/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ\s])+$/i",
                $this->data[$field]) !== false
        ) {
            return false;
        }

        return true;
    }

    protected function validate_alpha_dash($field)
    {
        if (!isset($this->data[$field]) || empty($this->data[$field])) {
            return false;
        }

        if (!preg_match('/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ_-])+$/i',
                $this->data[$field]) !== false
        ) {
            return false;
        }

        return true;
    }

    protected function validate_alpha_numeric($field)
    {
        if (!isset($this->data[$field]) || empty($this->data[$field])) {
            return false;
        }

        if (!preg_match('/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])+$/i',
                $this->data[$field]) !== false
        ) {
            return false;
        }

        return true;
    }

    protected function validate_numeric($field)
    {

        if (!isset($this->data[$field]) || empty($this->data[$field])) {
            return false;
        }

        if (!is_numeric($this->data[$field])) {
            return false;
        }

        return true;
    }

    protected function validate_integer($field)
    {
        if (!isset($this->data[$field]) || empty($this->data[$field])) {
            return false;
        }

        if (filter_var($this->data[$field], FILTER_VALIDATE_INT) === false) {
            return false;
        }

        return true;
    }

}
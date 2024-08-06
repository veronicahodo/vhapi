<?php

// api.php
// version 1.0

// Class to inherit for API objects

// TODO: Add logging function whenever I get a chance.

require_once('vcrud.php');

abstract class Api
{
    protected $crud;
    protected $unit = '';
    protected $commands = [
        'test' => [
            ['echo', true]
        ]
    ];
    protected $input;
    protected $userId;

    function __construct(Vcrud $crud)
    {
        $this->crud = $crud;
    }

    protected function processInput($command)
    {
        foreach ($this->commands[$command] as $field) {
            $this->input[$field[0]] = htmlspecialchars($_REQUEST[$field[0]]) ?? null;
        }
    }

    protected function checkRequired($command)
    {
        foreach ($this->commands[$command] as $field) {
            if ($field[1]) {
                if (!isset($_REQUEST[$field[0]])) {
                    return false;
                }
            }
        }
        return true;
    }

    function process()
    {
        $command = htmlspecialchars(
            strtolower($_REQUEST['command'] ?? '')
        );
        if ($command == '') {
            return $this->errorResponse('command not specified');
        }

        if (!in_array($command, array_keys($this->commands))) {
            return $this->errorResponse('invalid command');
        }

        $token = htmlspecialchars($_REQUEST['token'] ?? '');
        if (!$token == '') {
            $this->userId = $this->validateToken($token);
        }

        $this->processInput($command);
        if (!$this->checkRequired($command)) {
            return $this->errorResponse('missing required input');
        }
        $function =  'do' . ucfirst($command);
        return $this->$function();
    }

    protected function validateToken($token)
    {
        $data = $this->crud->read('tokens', [
            ['token', '=', $token],
            ['expiration', '>', date('YmdHis')]
        ]);
        if (count($data) > 0) {
            return $data[0]['userId'];
        }
        return -1;
    }

    protected function errorResponse($message)
    {
        return [
            'status' => 'error',
            'message' => "[{$this->unit}] {$message}"
        ];
    }
}

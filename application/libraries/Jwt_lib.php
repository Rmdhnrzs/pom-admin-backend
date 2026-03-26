<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Jwt_lib {
    private $CI;
    private $secret;
    private $algorithm;
    private $expire;

    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->config->load('jwt');
        $this->secret = $this->CI->config->item('jwt_secret');
        $this->algorithm = $this->CI->config->item('jwt_algorithm');
        $this->expire = $this->CI->config->item('jwt_expire');
    }

    public function generate($payload) {
        $token = [
            'iat' => time(),
            'exp' => time() + $this->expire,
            'data' => $payload 
        ];

        return JWT::encode($token, $this->secret, $this->algorithm);
    }

    public function verify($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, $this->algorithm));
            return [
                'status' => true,
                'error' => null,
                'data' => $decoded->data
            ];
        } catch (Firebase\JWT\ExpiredException $e) {
            return [
                'status' => false,
                'error' => 'expired',
                'data' => null
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'error' => 'invalid',
                'data' => null
            ];
        }
    }
}
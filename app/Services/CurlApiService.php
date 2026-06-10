<?php

namespace App\Services;

class CurlApiService
{
    protected $baseUrl = 'https://jsonplaceholder.typicode.com';

    /**
     * Core request engine to execute cURL operations.
     */
    protected function request(string $method, string $endpoint, array $data = [])
    {
        $url = $this->baseUrl . $endpoint;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        if (!empty($data) && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 

        // Transport-level connection failure handling
        if (curl_errno($ch)) {
            $errorMsg = curl_error($ch);
            curl_close($ch);
            return ['error' => true, 'message' => "Transport Error: $errorMsg"];
        }

        curl_close($ch);

        if ($httpCode >= 400) {
            return ['error' => true, 'message' => "API Server returned HTTP Status Code: $httpCode"];
        }

        return json_decode($response, true);
    }

    public function get(string $endpoint)
    {
        return $this->request('GET', $endpoint);
    }

    public function post(string $endpoint, array $data = [])
    {
        return $this->request('POST', $endpoint, $data);
    }

    public function delete(string $endpoint)
    {
        return $this->request('DELETE', $endpoint);
    }
}

<?php

namespace App\Services;

class CurlApiService
{
    // Pointing directly to your live, permanently updatable MockAPI server
    protected $baseUrl = 'https://6a2912e8f59cb8f65f1c674f.mockapi.io/api/v1';

    /**
     * Core request engine to execute cURL operations safely.
     */
    protected function request(string $method, string $endpoint, array $data = [])
    {
        $base = rtrim($this->baseUrl, '/');
        $path = '/' . ltrim($endpoint, '/');
        $url = $base . $path;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        // Only bind postfields on writing states with payload content
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
            return [
                'error' => true,
                'message' => "API Server returned HTTP Status Code: $httpCode. Raw Response: " . substr($response, 0, 150)
            ];
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

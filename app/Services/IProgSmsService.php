<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IProgSmsService
{
    protected $apiKey;
    protected $senderId;
    protected $apiUrl = 'https://sms.iprogtech.com/api/v1/sms_messages';

    public function __construct()
    {
        $this->apiKey = config('services.iprogsms.api_key');
        $this->senderId = config('services.iprogsms.sender_id');
    }

    /**
     * Send DOST Scholarship status notification
     */
    public function sendDostStatus($phoneNumber, $applicantName, $status, $reason = null)
    {
        $messages = [
            'document_verified' => "Hi {name}, your DOST scholarship documents have been VERIFIED. Please wait for the approval process.",
            'approved' => "Congratulations {name}! Your DOST scholarship application has been APPROVED. Further instructions will follow.",
            'rejected' => "Hi {name}, your DOST scholarship application has been rejected due to non-compliance with document requirements. Please contact the scholarship staff for assistance.",
        ];

        $message = $messages[$status] ?? "Your DOST scholarship status has been updated.";
        
        $message = str_replace('{name}', $applicantName, $message);
        
        if ($status === 'rejected') {
            $defaultReason = "Reason: Failed to submit complete/corrected documents within the required deadline.";
            $message = str_replace('{reason}', $reason ?? $defaultReason, $message);
        }

        return $this->sendSms($phoneNumber, $message);
    }

    /**
     * Send CHED Scholarship status notification
     */
    public function sendChedStatus($phoneNumber, $applicantName, $status, $reason = null)
    {
        $messages = [
            'confirmed' => "Congratulations {name}! Your CHED scholarship application has been CONFIRMED.",
            'rejected' => "Hi {name}, we regret to inform you that your CHED scholarship application has been rejected as your name was not included in the official CHED scholarship list. Please contact the scholarship office for clarification.",
        ];

        $message = $messages[$status] ?? "Your CHED scholarship status has been updated.";
        
        $message = str_replace('{name}', $applicantName, $message);
        
        if ($status === 'rejected') {
            $defaultReason = "Reason: Your name was not found in the official CHED scholarship roster. Please verify your eligibility with CHED.";
            $message = str_replace('{reason}', $reason ?? $defaultReason, $message);
        }

        return $this->sendSms($phoneNumber, $message);
    }

    /**
     * Send SMS using correct iProgSMS API v1 endpoint
     */
    protected function sendSms($phoneNumber, $message)
    {
        try {
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);

            // ✅ Build the full URL with query parameters INCLUDING sender_id
            $params = [
                'api_token' => $this->apiKey,
                'message' => $message,
                'phone_number' => $phoneNumber,
            ];

            // ✅ Only add sender_id if it's configured
            if ($this->senderId) {
                $params['sender_id'] = $this->senderId;
            }

            $url = $this->apiUrl . '?' . http_build_query($params);

            // Send POST request
            $response = Http::timeout(30)->post($url);

            $result = $response->json();

            Log::info('SMS Sent', [
                'phone' => $phoneNumber,
                'sender_id' => $this->senderId, // ✅ Log sender ID
                'message' => $message,
                'response' => $result,
                'http_code' => $response->status()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $result,
                'message' => $result['message'] ?? 'SMS sent successfully',
                'sms_content' => $message
            ];

        } catch (\Exception $e) {
            Log::error('SMS Failed', [
                'phone' => $phoneNumber,
                'sender_id' => $this->senderId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Format phone number to 639xxxxxxxxx format (iProgSMS expects this)
     */
    protected function formatPhoneNumber($phoneNumber)
    {
        // Remove spaces, dashes, parentheses
        $phoneNumber = preg_replace('/[\s\-\(\)]/', '', $phoneNumber);

        // Convert 09xxxxxxxxx to 639xxxxxxxxx
        if (str_starts_with($phoneNumber, '09')) {
            $phoneNumber = '63' . substr($phoneNumber, 1);
        }

        // Convert +639xxxxxxxxx to 639xxxxxxxxx
        if (str_starts_with($phoneNumber, '+63')) {
            $phoneNumber = substr($phoneNumber, 1);
        }

        return $phoneNumber;
    }

    /**
     * Check SMS credits balance
     */
    public function checkBalance()
    {
        try {
            $response = Http::get('https://sms.iprogtech.com/api/v1/balance', [
                'api_token' => $this->apiKey
            ]);

            $balance = $response->json();
            
            Log::info('SMS Balance Check', ['balance' => $balance]);
            
            return $balance;
        } catch (\Exception $e) {
            Log::error('Balance Check Failed', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;

class ApplicationController extends Controller
{
    // Initialize Razorpay API
    private $razorpay;
    private $keyId;
    private $keySecret;

    public function __construct()
    {
        // Load API keys from .env
        $this->keyId = env('RAZORPAY_KEY_ID');
        $this->keySecret = env('RAZORPAY_KEY_SECRET');

        // >>> TEMPORARY DIAGNOSTIC LINE START
        if (empty($this->keyId) || empty($this->keySecret)) {
            // If the keys are empty, stop the script and display the problem.
            dd('ERROR: Keys are empty! Key ID:', $this->keyId, 'Key Secret:', $this->keySecret);
        }
        // >>> TEMPORARY DIAGNOSTIC LINE END

        if ($this->keyId && $this->keySecret) {
            $this->razorpay = new Api($this->keyId, $this->keySecret);
        } else {
            Log::error("Razorpay API keys are not configured in the .env file.");
        }
    }

    /**
     * Show the application form.
     */
    public function create()
    {
        // Pass the Razorpay Key ID to the view for the checkout script
        return view('application_form', ['keyId' => $this->keyId]);
    }

    /**
     * Handle form submission, validation, file upload, and create Razorpay order.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:applications,email|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10,15}$/',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:today',
            'short_bio' => 'required|string|max:500',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048', // Max 2MB
            // Temporary hidden field to hold order data after order creation
            'razorpay_order_id_temp' => 'nullable|string',
        ]);

        try {
            // 2. File Upload
            $resumePath = $request->file('resume')->store('resumes', 'public');

            // 3. Create Razorpay Order
            $amount = 5000; // Amount in paise (₹50.00)
            $order = $this->razorpay->order->create([
                'receipt' => 'rcpt_'.time(),
                'amount' => $amount,
                'currency' => 'INR',
                'payment_capture' => 1 // Auto capture
            ]);

            $razorpayOrderId = $order['id'];

            // 4. Save Application Data with Pending Status
            $application = Application::create([
                'full_name' => $validatedData['full_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'gender' => $validatedData['gender'],
                'date_of_birth' => $validatedData['date_of_birth'],
                'short_bio' => $validatedData['short_bio'],
                'resume_path' => $resumePath,
                'razorpay_order_id' => $razorpayOrderId,
                'status' => 'pending',
            ]);

            // 5. Return JSON response to the front-end to trigger Razorpay Checkout
            return response()->json([
                'success' => true,
                'message' => 'Order created successfully.',
                'order_id' => $razorpayOrderId,
                'amount' => $amount,
                'key_id' => $this->keyId,
                'name' => $validatedData['full_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone']
            ]);

        } catch (\Exception $e) {
            Log::error("Error creating application or Razorpay order: " . $e->getMessage());
            // If order fails, delete the uploaded file
            if (isset($resumePath)) {
                Storage::disk('public')->delete($resumePath);
            }
            return response()->json(['success' => false, 'message' => 'Error: Could not process application or payment order.'], 500);
        }
    }

    /**
     * Handle the Razorpay callback (Payment Verification).
     */
    public function callback(Request $request)
    {
        $input = $request->all();

        // 1. Check for success status and required fields
        if (!isset($input['razorpay_payment_id']) || !isset($input['razorpay_order_id']) || !isset($input['razorpay_signature'])) {
            Log::error("Razorpay Callback Missing Data: ", $input);
            return redirect()->route('application.create')->with('error', 'Payment verification failed: Missing data.');
        }

        // 2. Find the application record using the order ID
        $application = Application::where('razorpay_order_id', $input['razorpay_order_id'])->first();

        if (!$application) {
            Log::error("Razorpay Callback: Application not found for Order ID " . $input['razorpay_order_id']);
            return redirect()->route('application.create')->with('error', 'Payment verification failed: Application record not found.');
        }

        try {
            // 3. Verify Payment Signature
            $attributes = [
                'razorpay_order_id' => $input['razorpay_order_id'],
                'razorpay_payment_id' => $input['razorpay_payment_id'],
                'razorpay_signature' => $input['razorpay_signature']
            ];

            $this->razorpay->utility->verifyPaymentSignature($attributes);

            // 4. Verification Successful: Update Application Status
            $application->update([
                'status' => 'paid',
                'razorpay_payment_id' => $input['razorpay_payment_id'],
            ]);

            // Redirect user to the dashboard or a success page
            return redirect()->route('application.dashboard')->with('success', 'Application submitted and payment verified successfully!');

        } catch (\Exception $e) {
            Log::error("Razorpay Signature Verification Failed for Order ID " . $input['razorpay_order_id'] . ": " . $e->getMessage());
            // 5. Verification Failed: Update Application Status
            $application->update(['status' => 'failed']);

            return redirect()->route('application.create')->with('error', 'Payment verification failed: Invalid signature or payment.');
        }
    }

    /**
     * Display the dashboard with all applications and payment details.
     */
    public function dashboard()
    {
        // Fetch all applications, ordered by creation date
        $applications = Application::orderBy('created_at', 'desc')->get();

        // The dashboard is intentionally simple. In a real app, you would paginate this.
        return view('dashboard', compact('applications'));
    }
}

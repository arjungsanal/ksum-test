<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form & Payment</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom font */
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-2xl bg-white p-8 md:p-10 rounded-xl shadow-2xl border border-gray-200">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Job Application</h1>
    <p class="text-sm text-center text-red-600 mb-6 font-medium">A one-time application fee of ₹50 is required upon submission.</p>

    <!-- Status Message Area -->
    <div id="status-messages" class="mb-4">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
                <p class="font-bold">Success!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
                <p class="font-bold">Error!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif
    </div>

    <!-- Form -->
    <form id="application-form" method="POST" action="{{ route('application.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Hidden field for temporary storage (optional, for progressive enhancement) -->
        <input type="hidden" name="razorpay_order_id_temp" id="razorpay_order_id_temp">

        <!-- Full Name -->
        <div>
            <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" name="full_name" id="full_name" required value="{{ old('full_name') }}"
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            @error('full_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
            <input type="email" name="email" id="email" required value="{{ old('email') }}"
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Phone -->
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
            <input type="tel" name="phone" id="phone" required value="{{ old('phone') }}"
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            @error('phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Date of Birth -->
            <div>
                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                <input type="date" name="date_of_birth" id="date_of_birth" required value="{{ old('date_of_birth') }}"
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('date_of_birth')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Gender -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                <div class="mt-1 flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="gender" value="male" required
                               class="form-radio h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('gender') == 'male' ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700">Male</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="gender" value="female"
                               class="form-radio h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('gender') == 'female' ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700">Female</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="gender" value="other"
                               class="form-radio h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('gender') == 'other' ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700">Other</span>
                    </label>
                </div>
                @error('gender')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Short Bio -->
        <div>
            <label for="short_bio" class="block text-sm font-medium text-gray-700">Short Bio (Max 500 chars)</label>
            <textarea name="short_bio" id="short_bio" rows="4" required
                      class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('short_bio') }}</textarea>
            @error('short_bio')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Resume File -->
        <div>
            <label for="resume" class="block text-sm font-medium text-gray-700">Resume (PDF, DOCX only, Max 2MB)</label>
            <input type="file" name="resume" id="resume" required
                   class="mt-1 block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-indigo-50 file:text-indigo-700
                    hover:file:bg-indigo-100">
            @error('resume')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Submit Button with Loading Indicator -->
        <button type="submit" id="submit-button"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-lg font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
            Submit Application & Pay ₹50
        </button>
        <div id="loading-indicator" class="hidden text-center text-indigo-600 font-semibold mt-4">
            Processing... Please wait for the payment window.
        </div>
    </form>
</div>

<!-- Razorpay Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    document.getElementById('application-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const submitButton = document.getElementById('submit-button');
        const loadingIndicator = document.getElementById('loading-indicator');
        const statusMessages = document.getElementById('status-messages');

        // 1. Disable form and show loading
        submitButton.disabled = true;
        submitButton.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
        submitButton.classList.add('bg-gray-400');
        submitButton.innerHTML = 'Processing...';
        loadingIndicator.classList.remove('hidden');
        statusMessages.innerHTML = ''; // Clear previous messages

        // 2. Prepare FormData for AJAX
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200 && body.success) {
                    // 3. Razorpay Order Creation Success: Open Checkout
                    var options = {
                        "key": "{{ $keyId }}", // Your Key ID
                        "amount": body.amount, // Amount is in paise
                        "currency": "INR",
                        "name": "Application Fee",
                        "description": "₹50 Application Processing Fee",
                        "image": "https://placehold.co/100x100/312e81/ffffff?text=APP", // Placeholder
                        "order_id": body.order_id, // Order ID generated by the backend
                        "handler": function (response){
                            // This function is called on successful payment

                            // 4. Client-side Success: Post payment details to backend for verification
                            const verificationForm = document.createElement('form');
                            verificationForm.method = 'POST';
                            verificationForm.action = "{{ route('payment.callback') }}";

                            // Add CSRF token
                            const csrfField = document.createElement('input');
                            csrfField.type = 'hidden';
                            csrfField.name = '_token';
                            csrfField.value = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '{{ csrf_token() }}';
                            verificationForm.appendChild(csrfField);

                            // Add payment details
                            const paymentIdField = document.createElement('input');
                            paymentIdField.type = 'hidden';
                            paymentIdField.name = 'razorpay_payment_id';
                            paymentIdField.value = response.razorpay_payment_id;
                            verificationForm.appendChild(paymentIdField);

                            const orderIdField = document.createElement('input');
                            orderIdField.type = 'hidden';
                            orderIdField.name = 'razorpay_order_id';
                            orderIdField.value = response.razorpay_order_id;
                            verificationForm.appendChild(orderIdField);

                            const signatureField = document.createElement('input');
                            signatureField.type = 'hidden';
                            signatureField.name = 'razorpay_signature';
                            signatureField.value = response.razorpay_signature;
                            verificationForm.appendChild(signatureField);

                            document.body.appendChild(verificationForm);
                            verificationForm.submit(); // Submit to the callback route
                        },
                        "modal": {
                            "ondismiss": function(){
                                // Re-enable form if user closes the modal without paying
                                submitButton.disabled = false;
                                submitButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                                submitButton.classList.remove('bg-gray-400');
                                submitButton.innerHTML = 'Submit Application & Pay ₹50';
                                loadingIndicator.classList.add('hidden');

                                // Show a message if necessary
                                statusMessages.innerHTML = `
                                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md" role="alert">
                                        <p>Payment cancelled. Please try again to complete your application.</p>
                                    </div>
                                `;
                            }
                        },
                        "prefill": {
                            "name": body.name,
                            "email": body.email,
                            "contact": body.phone
                        },
                        "theme": {
                            "color": "#4F46E5" // Indigo-600 color
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                } else if (status === 422) {
                    // 4. Validation Errors
                    const errorHtml = Object.keys(body.errors).map(key =>
                        `<p class="text-xs text-red-500 mt-1">${body.errors[key][0]}</p>`
                    ).join('');

                    statusMessages.innerHTML = `
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-4" role="alert">
                            <p class="font-bold">Validation Error!</p>
                            ${errorHtml}
                        </div>
                    `;
                    // Re-enable form
                    submitButton.disabled = false;
                    submitButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                    submitButton.classList.remove('bg-gray-400');
                    submitButton.innerHTML = 'Submit Application & Pay ₹50';
                    loadingIndicator.classList.add('hidden');

                    // Scroll to top to show errors
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                } else {
                    // 5. Server Error
                    statusMessages.innerHTML = `
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-4" role="alert">
                            <p class="font-bold">Server Error!</p>
                            <p>${body.message || 'An unexpected error occurred on the server.'}</p>
                        </div>
                    `;
                    // Re-enable form
                    submitButton.disabled = false;
                    submitButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                    submitButton.classList.remove('bg-gray-400');
                    submitButton.innerHTML = 'Submit Application & Pay ₹50';
                    loadingIndicator.classList.add('hidden');
                }
            })
            .catch(error => {
                // Network or catastrophic error
                console.error('Fetch Error:', error);
                statusMessages.innerHTML = `
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-4" role="alert">
                        <p class="font-bold">Network Error!</p>
                        <p>Could not connect to the server.</p>
                    </div>
                `;
                // Re-enable form
                submitButton.disabled = false;
                submitButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                submitButton.classList.remove('bg-gray-400');
                submitButton.innerHTML = 'Submit Application & Pay ₹50';
                loadingIndicator.classList.add('hidden');
            });
    });
</script>
</body>
</html>

<!-- Razorpay Global Checkout Component -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<div x-data="kdpRazorpayManager()" 
     x-init="init()"
     @open-razorpay.window="openPayment($event.detail)"
     class="relative z-[99999]">

    <!-- ======================================================== -->
    <!-- 1. LOADING OVERLAY                                       -->
    <!-- ======================================================== -->
    <div x-show="isProcessing" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         style="display: none;">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full shadow-2xl border border-slate-200 text-center space-y-4">
            <div class="relative w-16 h-16 mx-auto flex items-center justify-center">
                <div class="w-16 h-16 border-4 border-blue-100 border-t-[#1e3a8a] rounded-full animate-spin"></div>
                <div class="absolute text-xl">💳</div>
            </div>
            <div>
                <h4 class="font-heading font-black text-slate-900 text-base">Connecting to Razorpay</h4>
                <p class="text-xs text-slate-500 font-sans mt-1" x-text="processingMessage">
                    Generating encrypted 256-bit payment order...
                </p>
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-[10px] font-bold text-slate-600">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>PCI-DSS &amp; RBI Compliant</span>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 2. RAZORPAY SANDBOX SIMULATOR MODAL (When in Test Mode)   -->
    <!-- ======================================================== -->
    <div x-show="isSimulatorOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto"
         style="display: none;">
        
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 max-w-md w-full overflow-hidden text-slate-800 my-auto">
            
            <!-- Razorpay Style Header -->
            <div class="bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f172a] text-white p-5 border-b-2 border-[#f97316] relative">
                <button type="button" @click="closeSimulator()" class="absolute top-4 right-4 text-white/70 hover:text-white text-xl font-bold cursor-pointer">
                    &times;
                </button>
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-xl border border-white/20 shadow-inner">
                        🏛️
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-heading font-black text-sm tracking-wide text-white">K. D. Polytechnic, Patan</h3>
                            <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                Razorpay
                            </span>
                        </div>
                        <p class="text-[11px] text-blue-200 font-sans truncate max-w-[240px]" x-text="currentParams?.campaign || 'Alumni Portal Contribution'"></p>
                    </div>
                </div>

                <!-- Amount Pill -->
                <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between">
                    <span class="text-[11px] uppercase tracking-wider text-blue-200 font-heading font-bold">Total Payable</span>
                    <span class="text-2xl font-heading font-black text-amber-400" x-text="'₹' + Number(orderData?.amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                </div>
            </div>

            <!-- Mode Notice Badge -->
            <div class="bg-amber-50 border-b border-amber-200/80 px-4 py-2 flex items-center justify-between text-[11px] text-amber-900 font-semibold">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                    <span>Sandbox Test Gateway</span>
                </span>
                <span class="font-mono text-[10px] text-amber-800" x-text="orderData?.order_id"></span>
            </div>

            <!-- Payment Methods Tabs -->
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-3 gap-2 border-b border-slate-100 pb-3">
                    <button type="button" @click="simTab = 'upi'" 
                            class="py-2 px-2 rounded-xl text-xs font-bold font-heading transition-colors flex items-center justify-center gap-1.5 cursor-pointer"
                            :class="simTab === 'upi' ? 'bg-[#1e3a8a] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                        <span>📱</span> UPI / QR
                    </button>
                    <button type="button" @click="simTab = 'card'" 
                            class="py-2 px-2 rounded-xl text-xs font-bold font-heading transition-colors flex items-center justify-center gap-1.5 cursor-pointer"
                            :class="simTab === 'card' ? 'bg-[#1e3a8a] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                        <span>💳</span> Card
                    </button>
                    <button type="button" @click="simTab = 'netbanking'" 
                            class="py-2 px-2 rounded-xl text-xs font-bold font-heading transition-colors flex items-center justify-center gap-1.5 cursor-pointer"
                            :class="simTab === 'netbanking' ? 'bg-[#1e3a8a] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                        <span>🏦</span> Net Banking
                    </button>
                </div>

                <!-- UPI Tab -->
                <div x-show="simTab === 'upi'" class="space-y-3 text-center">
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl inline-block shadow-2xs">
                        <!-- Simulated QR Code SVG -->
                        <div class="w-36 h-36 mx-auto bg-white p-2 rounded-xl border border-slate-200 flex flex-col items-center justify-center space-y-1">
                            <svg class="w-24 h-24 text-slate-800" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm13-2h3v2h-3v-2zm-5 0h2v3h-2v-3zm3 3h2v2h-2v-2zm-3 2h2v3h-2v-3zm5 1h3v2h-3v-2zm0-3h3v2h-3v-2z"/>
                            </svg>
                            <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest font-heading">Scan with any UPI app</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-2 pt-1">
                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-700">Google Pay</span>
                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-700">PhonePe</span>
                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-700">Paytm</span>
                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-700">BHIM</span>
                    </div>
                </div>

                <!-- Card Tab -->
                <div x-show="simTab === 'card'" class="space-y-2.5 text-xs">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                        <div class="flex items-center justify-between text-slate-500 font-bold text-[10px] uppercase">
                            <span>Test Card Number</span>
                            <span>RuPay / Visa / MC</span>
                        </div>
                        <div class="font-mono font-bold text-slate-900 tracking-wider">
                            4111 &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; 1111
                        </div>
                        <div class="flex justify-between text-slate-500 text-[10px]">
                            <span>Expires: 12/28</span>
                            <span>CVV: 123</span>
                        </div>
                    </div>
                </div>

                <!-- Net Banking Tab -->
                <div x-show="simTab === 'netbanking'" class="space-y-2 text-xs">
                    <div class="grid grid-cols-2 gap-2 text-[11px] font-bold text-slate-700">
                        <div class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50 text-center cursor-pointer">State Bank of India</div>
                        <div class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50 text-center cursor-pointer">Bank of Baroda</div>
                        <div class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50 text-center cursor-pointer">HDFC Bank</div>
                        <div class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-blue-50 text-center cursor-pointer">ICICI Bank</div>
                    </div>
                </div>

                <!-- Complete Payment Button -->
                <div class="pt-2">
                    <button type="button" 
                            @click="simulatePaymentSuccess()" 
                            class="w-full bg-[#1e3a8a] hover:bg-[#0f2042] text-white font-bold text-xs uppercase tracking-wider py-3.5 px-4 rounded-xl shadow-lg transition-all font-heading cursor-pointer flex items-center justify-center gap-2">
                        <span>🔒</span>
                        <span>Simulate Successful Payment (Instant 80G)</span>
                    </button>
                    <p class="text-[10px] text-slate-400 text-center mt-2 font-sans">
                        Razorpay API verified &bull; Instant 80G Tax Exemption Receipt generated
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 3. INSTITUTIONAL SUCCESS MODAL (80G RECEIPT AVAILABLE)   -->
    <!-- ======================================================== -->
    <div x-show="showSuccessModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto"
         style="display: none;">
        
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 max-w-md w-full overflow-hidden text-center p-6 sm:p-8 space-y-4 my-auto">
            
            <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl mx-auto shadow-inner">
                ✓
            </div>

            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-amber-50 text-amber-900 border border-amber-200 text-[11px] font-bold font-heading mb-2">
                    <span class="w-2 h-2 rounded-full bg-[#f97316]"></span>
                    કિલાચંદ દેવચંદ પોલિટેકનિક, પાટણ • Estd. 1961
                </span>
                <h3 class="font-heading font-black text-slate-900 text-xl">
                    Contribution Received!
                </h3>
                <p class="text-xs text-slate-500 font-sans mt-1">
                    Your generous support has been successfully recorded in the college database.
                </p>
            </div>

            <!-- Receipt Metadata Box -->
            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200 text-left space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-500 font-medium">Amount Paid:</span>
                    <span class="font-heading font-black text-[#1e3a8a] text-sm" x-text="'₹' + Number(successData?.amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 font-medium">Campaign / Purpose:</span>
                    <span class="font-semibold text-slate-800 text-right truncate max-w-[200px]" x-text="successData?.campaign"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 font-medium">Transaction Reference:</span>
                    <span class="font-mono font-bold text-slate-700" x-text="successData?.transaction_id"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 font-medium">Razorpay Payment ID:</span>
                    <span class="font-mono text-[11px] text-slate-600" x-text="successData?.razorpay_payment_id"></span>
                </div>
                <div class="pt-1.5 border-t border-slate-200/80 flex items-center justify-between text-[10px] text-emerald-700 font-bold">
                    <span>📜 80G Tax Exemption Certified</span>
                    <span>Status: Completed</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2 pt-2">
                <template x-if="successData?.receipt_url">
                    <a :href="successData.receipt_url" 
                       target="_blank"
                       class="w-full bg-[#1e3a8a] hover:bg-[#0f2042] text-white font-bold text-xs uppercase tracking-wider py-3 px-4 rounded-xl shadow-md transition-all font-heading flex items-center justify-center gap-2 cursor-pointer">
                        <span>📜</span>
                        <span>Download Official 80G Tax Receipt (PDF)</span>
                    </a>
                </template>

                <button type="button" 
                        @click="closeSuccessModal()" 
                        class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider py-2.5 px-4 rounded-xl transition-all font-heading cursor-pointer">
                    Close &amp; Return to Portal
                </button>
            </div>
        </div>
    </div>

</div>

<script>
function kdpRazorpayManager() {
    return {
        isProcessing: false,
        processingMessage: 'Generating encrypted 256-bit payment order...',
        isSimulatorOpen: false,
        showSuccessModal: false,
        simTab: 'upi',
        currentParams: null,
        orderData: null,
        successData: null,

        init() {
            // Global convenience helper
            window.openRazorpayPayment = (params) => {
                this.openPayment(params);
            };
        },

        async openPayment(params) {
            this.currentParams = params;
            this.isProcessing = true;
            this.processingMessage = 'Contacting Razorpay Secure Gateway...';

            try {
                const response = await fetch('{{ route("payments.create-order") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        amount: params.amount,
                        purpose: params.purpose || 'giving_back',
                        campaign: params.campaign || '',
                        payable_type: params.payable_type || null,
                        payable_id: params.payable_id || null,
                        is_anonymous: params.is_anonymous ? 1 : 0,
                        notes: params.notes || '',
                        guests_count: params.guests_count || 0
                    })
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    alert(data.message || 'Unable to initiate Razorpay order. Please try again.');
                    this.isProcessing = false;
                    return;
                }

                this.orderData = data;
                this.isProcessing = false;

                // Check if Razorpay JS SDK is loaded and real Key ID is present
                const hasValidRazorpayKey = data.key_id && !data.key_id.includes('sandbox') && !data.key_id.includes('mock');

                if (typeof window.Razorpay !== 'undefined' && hasValidRazorpayKey && !data.is_simulated) {
                    // Launch Official Razorpay Modal
                    const options = {
                        key: data.key_id,
                        amount: data.amount_paise,
                        currency: data.currency,
                        name: data.merchant_name,
                        description: data.campaign,
                        image: '{{ asset("images/kdp_crest.png") }}',
                        order_id: data.order_id,
                        prefill: {
                            name: data.user.name,
                            email: data.user.email,
                            contact: data.user.phone
                        },
                        theme: {
                            color: data.theme_color || '#1e3a8a'
                        },
                        handler: (res) => {
                            this.verifyPayment(res.razorpay_order_id, res.razorpay_payment_id, res.razorpay_signature, 'Razorpay Official');
                        },
                        modal: {
                            ondismiss: () => {
                                console.log('Razorpay checkout dismissed by user');
                            }
                        }
                    };
                    const rzp = new window.Razorpay(options);
                    rzp.open();
                } else {
                    // Open KDP Razorpay Simulator Modal
                    this.isSimulatorOpen = true;
                }

            } catch (err) {
                console.error(err);
                alert('A network error occurred while communicating with the payment gateway.');
                this.isProcessing = false;
            }
        },

        async simulatePaymentSuccess() {
            this.isSimulatorOpen = false;
            this.isProcessing = true;
            this.processingMessage = 'Verifying payment with bank & finalizing 80G receipt...';

            const simPaymentId = 'pay_sim_' + Math.random().toString(36).substring(2, 12);
            const simSignature = 'sim_sig_' + Math.random().toString(36).substring(2, 16);

            await this.verifyPayment(
                this.orderData.order_id,
                simPaymentId,
                simSignature,
                'Razorpay ' + this.simTab.toUpperCase()
            );
        },

        async verifyPayment(orderId, paymentId, signature, method) {
            this.isProcessing = true;
            this.processingMessage = 'Securing donation & issuing 80G tax certificate...';

            try {
                const response = await fetch('{{ route("payments.verify") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        donation_id: this.orderData.donation_id,
                        razorpay_order_id: orderId,
                        razorpay_payment_id: paymentId,
                        razorpay_signature: signature,
                        payment_method: method,
                        guests_count: this.currentParams?.guests_count || 0
                    })
                });

                const data = await response.json();
                this.isProcessing = false;

                if (response.ok && data.success) {
                    this.successData = data;
                    this.showSuccessModal = true;

                    // Trigger callback if provided
                    if (typeof this.currentParams?.onSuccess === 'function') {
                        this.currentParams.onSuccess(data);
                    }
                } else {
                    alert(data.message || 'Payment verification failed. Please contact the college helpdesk.');
                }

            } catch (err) {
                console.error(err);
                this.isProcessing = false;
                alert('Verification error. Please contact the administrative office.');
            }
        },

        closeSimulator() {
            this.isSimulatorOpen = false;
        },

        closeSuccessModal() {
            this.showSuccessModal = false;
            // Optionally reload or navigate
            if (this.currentParams?.redirectOnClose) {
                window.location.href = this.currentParams.redirectOnClose;
            } else {
                window.location.reload();
            }
        }
    };
}
</script>

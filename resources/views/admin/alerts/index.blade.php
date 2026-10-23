@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8" x-data="{ 
    audience: 'all',
    title: 'Urgent Campus Announcement',
    body: 'Greetings from K.D. Polytechnic Patan! We are pleased to share an important update regarding campus placement drives.',
    link: '{{ url('/') }}'
}">

    <!-- Header & Dashboard Link -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-[#f97316]">KDP Multi-Channel Alert Center</span>
            <h2 class="text-2xl sm:text-3xl font-serif font-bold text-[#1C3661] mt-0.5">
                Direct WhatsApp &amp; Email Alert Hub
            </h2>
            <p class="text-xs text-gray-500 mt-1">Monitor delivery channels, dispatch instant WhatsApp broadcasts, and test live outgoing emails.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-xl text-xs uppercase tracking-wider shadow-sm transition-colors">
                Admin Dashboard &rarr;
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-r shadow-sm text-sm space-y-2">
            <div>{{ session('success') }}</div>
            @if(session('sample_wa_url'))
                <div class="pt-1">
                    <a href="{{ session('sample_wa_url') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider hover:bg-emerald-700 shadow-sm">
                        <span>📱</span> Open WhatsApp Chat for {{ session('sample_wa_phone') }} &rarr;
                    </a>
                </div>
            @endif
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-r shadow-sm text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Channel Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- 1. Email Channel Status Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="text-2xl">📧</span>
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Email Delivery Channel</h3>
                        <p class="text-[11px] text-slate-500">Laravel Mailable &amp; SMTP Engine</p>
                    </div>
                </div>

                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $mailConfig['mailer'] === 'smtp' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                    Driver: {{ strtoupper($mailConfig['mailer']) }}
                </span>
            </div>

            <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100 text-xs space-y-1.5 font-mono">
                <div class="flex justify-between text-slate-600">
                    <span>Host:</span>
                    <strong class="text-slate-900">{{ $mailConfig['host'] ?? '127.0.0.1' }}</strong>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>From Address:</span>
                    <strong class="text-slate-900">{{ $mailConfig['from_address'] }}</strong>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Queue Mode:</span>
                    <strong class="text-indigo-700">{{ strtoupper($mailConfig['queue_connection']) }}</strong>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <form action="{{ route('admin.alerts.test-email') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-700 hover:bg-indigo-800 text-white font-bold text-xs uppercase tracking-wider shadow-sm transition-colors">
                        Send Test Email to {{ auth()->user()->email }}
                    </button>
                </form>

                <span class="text-[11px] text-slate-400">
                    {{ $stats['total_users_with_email'] }} registered emails
                </span>
            </div>
        </div>

        <!-- 2. WhatsApp Alert Channel Status Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="text-2xl">📱</span>
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Direct WhatsApp Dispatcher</h3>
                        <p class="text-[11px] text-slate-500">1-Click wa.me Direct + Cloud API Ready</p>
                    </div>
                </div>

                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                    ✓ WhatsApp Active
                </span>
            </div>

            <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100 text-xs space-y-1.5 font-mono">
                <div class="flex justify-between text-slate-600">
                    <span>Active Mode:</span>
                    <strong class="text-emerald-700 font-bold">1-Click Smart wa.me Direct Dispatch</strong>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Registered Phone Numbers:</span>
                    <strong class="text-slate-900">{{ $stats['total_users_with_phone'] }} Alumni &amp; Students</strong>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Total WhatsApp Alerts Logged:</span>
                    <strong class="text-slate-900">{{ $stats['total_whatsapp_alerts_sent'] }}</strong>
                </div>
            </div>

            <div class="text-[11px] text-slate-500 pt-2 flex items-center justify-between">
                <span>International dialing +91 automatically handled.</span>
                <span class="font-bold text-emerald-700">100% Free / No API costs</span>
            </div>
        </div>

    </div>

    <!-- Live Broadcast Composer & Live Phone Preview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Composer Form (2 Cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
            <div class="space-y-1">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span>📢</span> Compose Community WhatsApp Alert
                </h3>
                <p class="text-xs text-slate-500">Broadcast official placement news, emergency blood calls, campus events, or reunion updates to members.</p>
            </div>

            <form action="{{ route('admin.alerts.whatsapp-broadcast') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Target Audience Selector -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Target Recipient Audience *
                    </label>
                    <select name="target_audience" x-model="audience" required class="w-full rounded-xl border-slate-300 text-xs sm:text-sm font-semibold focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="all">👥 All Approved Members with Phone Numbers ({{ $stats['total_users_with_phone'] }} members)</option>
                        <option value="department">🎓 By Engineering Branch / Department</option>
                        <option value="batch_year">📅 By Graduation Batch Year</option>
                        <option value="single_phone">📲 Direct Single Phone Number (Instant Test)</option>
                    </select>
                </div>

                <!-- Conditional Department Selector -->
                <div x-show="audience === 'department'" x-cloak class="space-y-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Select Branch</label>
                    <select name="department" class="w-full rounded-xl border-slate-300 text-xs sm:text-sm">
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}">{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Conditional Batch Year Selector -->
                <div x-show="audience === 'batch_year'" x-cloak class="space-y-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Select Batch Year</label>
                    <select name="batch_year" class="w-full rounded-xl border-slate-300 text-xs sm:text-sm">
                        @foreach($batches as $yr)
                            <option value="{{ $yr }}">Class of {{ $yr }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Conditional Single Phone Input -->
                <div x-show="audience === 'single_phone'" x-cloak class="space-y-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Recipient Phone (10 digits or with +91)</label>
                    <input type="text" name="single_phone" placeholder="e.g. 9265105831" class="w-full rounded-xl border-slate-300 text-xs sm:text-sm">
                </div>

                <!-- Broadcast Title -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Alert Headline / Subject *
                    </label>
                    <input type="text" name="title" x-model="title" required placeholder="e.g. Adani Ports Apprenticeship Drive 2026" class="w-full rounded-xl border-slate-300 text-xs sm:text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Broadcast Message Body -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        WhatsApp Message Body *
                    </label>
                    <textarea name="message" x-model="body" rows="4" required class="w-full rounded-xl border-slate-300 text-xs sm:text-sm focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                </div>

                <!-- Optional Action Link -->
                <div class="space-y-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Action Link / Deep Link (Optional)
                    </label>
                    <input type="url" name="link" x-model="link" placeholder="https://example.com/jobs" class="w-full rounded-xl border-slate-300 text-xs sm:text-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Dispatch Button -->
                <div class="pt-3 flex items-center justify-between">
                    <p class="text-[11px] text-slate-400">
                        Dispatches direct alerts &amp; logs entries to database.
                    </p>

                    <button type="submit" class="px-8 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-emerald-600/20 transition-all flex items-center gap-2">
                        <span>📱</span> Dispatch WhatsApp Broadcast
                    </button>
                </div>
            </form>
        </div>

        <!-- Live WhatsApp Chat Bubble Preview (1 Col) -->
        <div class="space-y-4">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-600 flex items-center gap-1.5">
                <span>👁️</span> Live WhatsApp Preview
            </div>

            <!-- Mock Smartphone Container -->
            <div class="bg-[#0b141a] rounded-[36px] p-4 shadow-2xl border-4 border-slate-800 max-w-sm mx-auto">
                <!-- Phone Top Speaker/Camera -->
                <div class="w-20 h-3.5 bg-slate-800 rounded-full mx-auto mb-3"></div>

                <!-- WhatsApp Header Bar -->
                <div class="bg-[#202c33] rounded-t-2xl p-3 flex items-center gap-2.5 text-white">
                    <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center font-bold text-xs">
                        KDP
                    </div>
                    <div class="text-xs leading-tight">
                        <div class="font-bold">KDP Alumni Association</div>
                        <div class="text-[10px] text-slate-400">Official Channel</div>
                    </div>
                </div>

                <!-- Chat Wallpaper & Bubble -->
                <div class="bg-[#0b141a] bg-opacity-95 p-3 rounded-b-2xl min-h-[320px] flex flex-col justify-end text-xs space-y-2">
                    
                    <!-- Bubble -->
                    <div class="bg-[#005c4b] text-[#e9edef] rounded-2xl p-3.5 shadow space-y-2 self-start max-w-[90%] text-[11px] leading-relaxed">
                        <div class="text-[10px] text-emerald-200 font-bold uppercase tracking-wider">
                            🏛️ K.D. Polytechnic Patan — Alumni Broadcast
                        </div>

                        <div class="font-bold text-white text-xs" x-text="title"></div>

                        <div class="text-slate-200 whitespace-pre-line" x-text="body"></div>

                        <div class="pt-1 text-emerald-300 underline break-all font-mono text-[10px]" x-show="link" x-text="link"></div>

                        <div class="text-right text-[9px] text-slate-300 pt-1 flex items-center justify-end gap-1">
                            <span>{{ date('h:i A') }}</span>
                            <span class="text-emerald-300">✓✓</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Recent WhatsApp Alert Logs Table -->
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm space-y-4">
        <div class="p-5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <span class="font-bold text-sm text-slate-800">
                Recent WhatsApp Alert Dispatch Logs ({{ $logs->total() }})
            </span>
        </div>

        @if($logs->isEmpty())
            <div class="p-12 text-center text-slate-500 italic">
                No WhatsApp alerts have been dispatched yet. Use the composer above to send your first broadcast!
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs divide-y divide-slate-200">
                    <thead class="bg-[#1C3661] text-white">
                        <tr>
                            <th class="px-5 py-3 font-semibold uppercase">Recipient</th>
                            <th class="px-5 py-3 font-semibold uppercase">Alert Type</th>
                            <th class="px-5 py-3 font-semibold uppercase">Message Excerpt</th>
                            <th class="px-5 py-3 font-semibold uppercase">Channel</th>
                            <th class="px-5 py-3 font-semibold uppercase">Time &amp; Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($logs as $log)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="font-bold text-slate-900">{{ $log->recipient_name ?: 'Member' }}</div>
                                    <div class="text-[11px] font-mono text-slate-500">+{{ $log->recipient_phone }}</div>
                                </td>

                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-800">
                                        {{ str_replace('_', ' ', $log->alert_type) }}
                                    </span>
                                </td>

                                <td class="px-5 py-3 max-w-xs">
                                    <p class="text-slate-600 line-clamp-2" title="{{ $log->message }}">
                                        {{ $log->message }}
                                    </p>
                                </td>

                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        {{ $log->channel }} ({{ $log->status }})
                                    </span>
                                </td>

                                <td class="px-5 py-3 whitespace-nowrap flex items-center gap-2">
                                    <span class="text-slate-400 text-[10px]">{{ $log->created_at->diffForHumans() }}</span>
                                    
                                    <a href="{{ \App\Services\WhatsAppAlertService::generateClickToChatUrl($log->recipient_phone, $log->message) }}" target="_blank" class="px-2.5 py-1 rounded bg-emerald-600 text-white font-bold text-[10px] uppercase hover:bg-emerald-700 transition-colors">
                                        Chat
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="p-4 border-t border-slate-200">
                    {{ $logs->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection

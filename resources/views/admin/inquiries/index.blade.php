@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans pb-20">

    <!-- Institutional Hero Header -->
    <div class="relative bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f172a] text-white pt-10 pb-16 px-4 sm:px-6 lg:px-8 overflow-hidden shadow-lg">
        <div class="max-w-7xl mx-auto relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-orange-300 text-[11px] font-bold font-heading">
                        <span class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse"></span>
                        Admin Control Panel
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full bg-blue-500/20 text-blue-200 border border-blue-400/30 text-[11px] font-bold font-heading uppercase tracking-wider">
                        Secretariat Desk
                    </span>
                </div>
                <h1 class="font-heading text-2xl sm:text-3xl font-black uppercase tracking-wide text-white">
                    Contact Inquiries Management
                </h1>
                <p class="text-xs sm:text-sm text-blue-100 mt-1 font-sans">
                    Review and update correspondence received from students, alumni, and external visitors.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-heading font-bold text-xs uppercase tracking-wider backdrop-blur transition-all">
                    <span>&larr;</span> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-20">

        <!-- Flash Success Notification -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-r-2xl text-xs sm:text-sm font-semibold flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="text-base">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-bold text-lg cursor-pointer">&times;</button>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-3xl border border-slate-200/90 overflow-hidden">
            
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="text-lg">📬</span>
                    <h3 class="font-heading font-black text-sm uppercase tracking-wider text-slate-800">
                        Received Messages ({{ $inquiries->count() }})
                    </h3>
                </div>
            </div>

            @if($inquiries->isEmpty())
                <div class="p-12 text-center text-slate-400 text-sm font-sans space-y-2">
                    <span class="text-3xl block">📭</span>
                    <p class="font-bold text-slate-600">No contact inquiries found</p>
                    <p class="text-xs">Any messages sent via the public contact desk will appear here.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-xs font-sans">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#0f2042] via-[#1e3a8a] to-[#0f172a] text-white">
                                <th class="px-5 py-3.5 font-heading font-bold uppercase tracking-wider text-[11px]">Received Date</th>
                                <th class="px-5 py-3.5 font-heading font-bold uppercase tracking-wider text-[11px]">Sender Details</th>
                                <th class="px-5 py-3.5 font-heading font-bold uppercase tracking-wider text-[11px]">Subject &amp; Message</th>
                                <th class="px-5 py-3.5 font-heading font-bold uppercase tracking-wider text-[11px] w-48">Status Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($inquiries as $inquiry)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-5 py-4 whitespace-nowrap text-slate-600 font-mono text-[11px]">
                                    {{ $inquiry->created_at->format('d M, Y') }}<br>
                                    <span class="text-slate-400 text-[10px]">{{ $inquiry->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-heading font-bold text-slate-900 text-xs">{{ $inquiry->name }}</div>
                                    <div class="text-[11px] text-[#1e3a8a] mt-0.5">
                                        <a href="mailto:{{ $inquiry->email }}" class="hover:underline font-mono">{{ $inquiry->email }}</a>
                                    </div>
                                </td>
                                <td class="px-5 py-4 max-w-md">
                                    <div class="font-heading font-bold text-slate-800 text-xs">{{ $inquiry->subject }}</div>
                                    <div class="text-slate-600 mt-1 bg-slate-50 p-3 rounded-xl border border-slate-100 whitespace-pre-line leading-relaxed text-[11px]">
                                        {{ $inquiry->message }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 bg-slate-50/40 border-l border-slate-100 align-top">
                                    <form action="{{ route('admin.inquiries.update', $inquiry->id) }}" method="POST" class="space-y-2">
                                        @csrf
                                        @method('PUT')
                                        
                                        <select name="status" class="w-full text-xs py-2 px-3 rounded-xl border border-slate-200 shadow-2xs font-bold font-heading cursor-pointer
                                            {{ $inquiry->status === 'Unread' ? 'text-amber-800 bg-amber-50 border-amber-300' : '' }}
                                            {{ $inquiry->status === 'Read' ? 'text-blue-800 bg-blue-50 border-blue-300' : '' }}
                                            {{ $inquiry->status === 'Replied' ? 'text-emerald-800 bg-emerald-50 border-emerald-300' : '' }}">
                                            <option value="Unread" {{ $inquiry->status == 'Unread' ? 'selected' : '' }}>🟡 Unread</option>
                                            <option value="Read" {{ $inquiry->status == 'Read' ? 'selected' : '' }}>🔵 Read</option>
                                            <option value="Replied" {{ $inquiry->status == 'Replied' ? 'selected' : '' }}>🟢 Replied</option>
                                        </select>
                                        
                                        <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-[#0f2042] text-white text-[10px] font-bold font-heading py-2 px-3 rounded-xl shadow-2xs transition-colors uppercase tracking-wider cursor-pointer">
                                            Update Status
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection

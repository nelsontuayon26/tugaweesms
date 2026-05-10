<!-- resources/views/student/help/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Support - Tugawe Elementary School</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased" x-data="helpApp()">

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 lg:hidden"
         style="display: none;">
    </div>

    <!-- Mobile Toggle Button -->
    <button @click="mobileOpen = !mobileOpen" 
            class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all border border-slate-100">
        <i class="fas fa-bars text-lg"></i>    </button>

    <!-- Include Sidebar -->
    <?php echo $__env->make('student.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <main class="transition-all duration-300 ease-out min-h-screen p-4 lg:p-8"
          :class="mainContentClass">
        
        <!-- Toast Notification -->
        <div x-show="toast.show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             class="fixed top-4 right-4 z-50 flex items-center gap-2 px-4 py-3 rounded-xl shadow-lg"
             :class="toast.type === 'success' ? 'bg-emerald-500 text-white' : toast.type === 'error' ? 'bg-rose-500 text-white' : 'bg-blue-500 text-white'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      :d="toast.type === 'success' ? 'M5 13l4 4L19 7' : toast.type === 'error' ? 'M6 18L18 6M6 6l12 12' : 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'"/>
            </svg>
            <span class="font-medium text-sm" x-text="toast.message"></span>
        </div>

        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="lg:ml-0 ml-14">
                    <h1 class="text-2xl font-bold text-slate-900">Help & Support</h1>
                    <p class="text-slate-500 mt-1">Find answers or get assistance from our support team</p>
                </div>
                <button @click="showTicketModal = true" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl font-medium text-sm hover:bg-indigo-700 transition-all duration-200 shadow-lg shadow-indigo-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Submit Ticket
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column - Quick Help & Contact -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Search Help -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-900 mb-4">Search Help</h3>
                    <div class="relative">
                        <input type="text" 
                               x-model="searchQuery" 
                               @input="filterFAQs"
                               placeholder="Search for answers..." 
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                        <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-900 mb-4">Contact Us</h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900 text-sm">Email Support</p>
                                <a href="mailto:support@tugaweelementary.edu" class="text-sm text-indigo-600 hover:text-indigo-700 transition-colors">support@tugaweelementary.edu</a>
                                <p class="text-xs text-slate-400 mt-1">Response within 24 hours</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900 text-sm">Phone Support</p>
                                <p class="text-sm text-slate-600">(02) 8123-4567</p>
                                <p class="text-xs text-slate-400 mt-1">Mon-Fri, 8:00 AM - 5:00 PM</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-slate-900 text-sm">School Address</p>
                                <p class="text-sm text-slate-600">Tugawe Elementary School</p>
                                <p class="text-xs text-slate-400 mt-1">Main Campus, Building A</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-900 mb-4">Quick Links</h3>
                    <div class="space-y-2">
                        <a href="<?php echo e(route('student.dashboard')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-all duration-200 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </a>
                        <a href="<?php echo e(route('student.profile')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-all duration-200 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            My Profile
                        </a>
                        <a href="<?php echo e(route('student.grades')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-all duration-200 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            View Grades
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column - FAQs & Support -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- FAQs Section -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <h3 class="font-bold text-slate-900">Frequently Asked Questions</h3>
                        <p class="text-sm text-slate-500 mt-1">Find quick answers to common questions</p>
                    </div>
                    
                    <div class="divide-y divide-slate-100">
                        <template x-for="(faq, index) in filteredFAQs" :key="index">
                            <div class="group">
                                <button @click="toggleFaq(index)" 
                                        class="w-full flex items-center justify-between p-6 text-left hover:bg-slate-50 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center shrink-0 group-hover:bg-indigo-200 transition-colors">
                                            <span class="text-indigo-600 font-bold text-sm" x-text="index + 1"></span>
                                        </div>
                                        <span class="font-medium text-slate-900" x-text="faq.question"></span>
                                    </div>
                                    <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" 
                                         :class="{ 'rotate-180': faq.open }"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="faq.open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 -translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 -translate-y-2"
                                     class="px-6 pb-6 pl-18">
                                    <p class="text-slate-600 leading-relaxed ml-12" x-text="faq.answer"></p>
                                </div>
                            </div>
                        </template>
                        
                        <!-- No Results -->
                        <div x-show="filteredFAQs.length === 0" class="p-8 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 flex items-center justify-center">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-slate-500 font-medium">No results found</p>
                            <p class="text-sm text-slate-400 mt-1">Try adjusting your search terms</p>
                        </div>
                    </div>
                </div>

                <!-- My Support Tickets -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-slate-900">My Support Tickets</h3>
                            <p class="text-sm text-slate-500 mt-1">Track your support requests</p>
                        </div>
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full" x-text="tickets.length + ' tickets'"></span>
                    </div>
                    
                    <div class="divide-y divide-slate-100">
                        <template x-for="(ticket, index) in tickets" :key="ticket.id">
                            <div class="p-6 hover:bg-slate-50 transition-colors">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h4 class="font-medium text-slate-900" x-text="ticket.subject"></h4>
                                            <span class="px-2 py-0.5 text-xs font-bold rounded-full"
                                                  :class="{
                                                      'bg-emerald-100 text-emerald-700': ticket.status === 'resolved',
                                                      'bg-amber-100 text-amber-700': ticket.status === 'pending',
                                                      'bg-blue-100 text-blue-700': ticket.status === 'in_progress',
                                                      'bg-slate-100 text-slate-600': ticket.status === 'closed'
                                                  }"
                                                  x-text="ticket.status.replace('_', ' ')">
                                            </span>
                                        </div>
                                        <p class="text-sm text-slate-500 mb-3" x-text="ticket.description"></p>
                                        <div class="flex items-center gap-4 text-xs text-slate-400">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                                <span x-text="ticket.category"></span>
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span x-text="ticket.created_at"></span>
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                                </svg>
                                                <span x-text="ticket.replies + ' replies'"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <button @click="viewTicket(ticket)" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                        View
                                    </button>
                                </div>
                            </div>
                        </template>
                        
                        <!-- Empty State -->
                        <div x-show="tickets.length === 0" class="p-8 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 flex items-center justify-center">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <p class="text-slate-500 font-medium">No tickets yet</p>
                            <p class="text-sm text-slate-400 mt-1 mb-4">Submit your first support ticket</p>
                            <button @click="showTicketModal = true" class="text-indigo-600 hover:text-indigo-700 font-medium text-sm">
                                Create Ticket →
                            </button>
                        </div>
                    </div>
                </div>

                <!-- System Status -->
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl border border-emerald-200 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-emerald-900">All Systems Operational</h4>
                            <p class="text-sm text-emerald-700">Last updated: Just now</p>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-xs text-emerald-600 font-medium uppercase tracking-wider">Portal</p>
                            <p class="text-sm font-bold text-emerald-800 mt-1">Online</p>
                        </div>
                        <div>
                            <p class="text-xs text-emerald-600 font-medium uppercase tracking-wider">Grades</p>
                            <p class="text-sm font-bold text-emerald-800 mt-1">Online</p>
                        </div>
                        <div>
                            <p class="text-xs text-emerald-600 font-medium uppercase tracking-wider">Messages</p>
                            <p class="text-sm font-bold text-emerald-800 mt-1">Online</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Submit Ticket Modal -->
    <div x-show="showTicketModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="showTicketModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="showTicketModal = false"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        
        <div x-show="showTicketModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden max-h-[90vh] overflow-y-auto">
            
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 sticky top-0">
                <h3 class="font-bold text-slate-900">Submit Support Ticket</h3>
                <button @click="showTicketModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form @submit.prevent="submitTicket">
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Subject <span class="text-rose-500">*</span></label>
                        <input type="text" x-model="ticketForm.subject" required maxlength="255"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                               placeholder="Brief description of your issue">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Category <span class="text-rose-500">*</span></label>
                        <select x-model="ticketForm.category" required
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                            <option value="">Select a category</option>
                            <option value="technical">Technical Issue</option>
                            <option value="grades">Grades & Records</option>
                            <option value="account">Account Access</option>
                            <option value="enrollment">Enrollment</option>
                            <option value="general">General Inquiry</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Description <span class="text-rose-500">*</span></label>
                        <textarea x-model="ticketForm.description" required rows="4" maxlength="1000"
                                  class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all resize-none"
                                  placeholder="Please provide detailed information about your issue..."></textarea>
                        <p class="text-xs text-slate-400 mt-1 text-right" x-text="ticketForm.description.length + '/1000'"></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Attachment (Optional)</label>
                        <div class="border-2 border-dashed border-slate-300 rounded-xl p-4 text-center hover:border-indigo-400 hover:bg-indigo-50/50 transition-all cursor-pointer"
                             onclick="document.getElementById('ticket-attachment').click()">
                            <input type="file" id="ticket-attachment" class="hidden" @change="handleFileUpload">
                            <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            <p class="text-sm text-slate-600" x-text="ticketForm.attachment ? ticketForm.attachment.name : 'Click to upload file'"></p>
                            <p class="text-xs text-slate-400 mt-1">Max 5MB (PDF, JPG, PNG)</p>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-slate-50 flex gap-3 sticky bottom-0">
                    <button type="button" @click="showTicketModal = false" 
                            class="flex-1 px-4 py-2.5 border border-slate-300 text-slate-700 rounded-xl font-medium hover:bg-slate-100 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2.5 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                        Submit Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Ticket Modal -->
    <div x-show="viewingTicket" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="viewingTicket" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="viewingTicket = null"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        
        <div x-show="viewingTicket"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden max-h-[90vh] flex flex-col">
            
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 shrink-0">
                <div class="flex items-center gap-3">
                    <h3 class="font-bold text-slate-900" x-text="viewingTicket?.subject"></h3>
                    <span class="px-2 py-0.5 text-xs font-bold rounded-full"
                          :class="{
                              'bg-emerald-100 text-emerald-700': viewingTicket?.status === 'resolved',
                              'bg-amber-100 text-amber-700': viewingTicket?.status === 'pending',
                              'bg-blue-100 text-blue-700': viewingTicket?.status === 'in_progress',
                              'bg-slate-100 text-slate-600': viewingTicket?.status === 'closed'
                          }"
                          x-text="viewingTicket?.status?.replace('_', ' ')">
                    </span>
                </div>
                <button @click="viewingTicket = null" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto p-6">
                <!-- Original Message -->
                <div class="mb-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-indigo-600 font-bold text-xs">ME</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-900">You</p>
                            <p class="text-xs text-slate-400" x-text="viewingTicket?.created_at"></p>
                        </div>
                    </div>
                    <div class="ml-11 p-4 bg-slate-50 rounded-xl">
                        <p class="text-slate-700" x-text="viewingTicket?.description"></p>
                    </div>
                </div>

                <!-- Replies -->
                <template x-for="(reply, index) in viewingTicket?.replies_list || []" :key="index">
                    <div class="mb-6">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                                <span class="text-emerald-600 font-bold text-xs" x-text="reply.is_staff ? 'ST' : 'ME'"></span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900" x-text="reply.author"></p>
                                <p class="text-xs text-slate-400" x-text="reply.created_at"></p>
                            </div>
                        </div>
                        <div class="ml-11 p-4 rounded-xl" :class="reply.is_staff ? 'bg-emerald-50' : 'bg-slate-50'">
                            <p class="text-slate-700" x-text="reply.message"></p>
                        </div>
                    </div>
                </template>

                <!-- Reply Input -->
                <div x-show="viewingTicket?.status !== 'closed' && viewingTicket?.status !== 'resolved'" class="mt-6 pt-6 border-t border-slate-100">
                    <div class="flex gap-3">
                        <textarea x-model="replyMessage" 
                                  rows="3"
                                  class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all resize-none"
                                  placeholder="Type your reply..."></textarea>
                        <button @click="sendReply" 
                                :disabled="!replyMessage.trim()"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function helpApp() {
            return {
                searchQuery: '',
                showTicketModal: false,
                viewingTicket: null,
                replyMessage: '',
                toast: { show: false, message: '', type: 'success' },
                sidebarCollapsed: false,
                mobileOpen: false,
                isMobile: window.innerWidth < 1024,
                
                ticketForm: {
                    subject: '',
                    category: '',
                    description: '',
                    attachment: null
                },

                faqs: [
                    {
                        question: 'How do I view my grades?',
                        answer: 'You can view your grades by navigating to "My Grades" in the sidebar menu. Your grades are organized by subject and grading period. If you notice any discrepancies, please contact your teacher or submit a support ticket.',
                        open: false
                    },
                    {
                        question: 'What should I do if I forget my password?',
                        answer: 'Click on the "Forgot Password" link on the login page. Enter your registered email address, and we will send you a password reset link. If you do not receive the email within 5 minutes, check your spam folder or contact technical support.',
                        open: false
                    },
                    {
                        question: 'How can I update my profile information?',
                        answer: 'Go to "My Profile" in the sidebar menu. Click the "Edit Profile" button to update your personal information. Note that some fields like LRN and Grade Level can only be updated by school administrators.',
                        open: false
                    },
                    {
                        question: 'Where can I find my class schedule?',
                        answer: 'Your class schedule is available in the "My Subjects" section. Each subject card displays the schedule information including days and time slots. You can also download a PDF version of your schedule from there.',
                        open: false
                    },
                    {
                        question: 'How do I submit an assignment?',
                        answer: 'Navigate to "Assignments" in the Classroom section. Click on the assignment you want to submit, then click the "Submit" button. You can upload files in PDF, DOC, or image formats. Make sure to submit before the deadline.',
                        open: false
                    },
                    {
                        question: 'What should I do if I encounter a technical issue?',
                        answer: 'First, try refreshing the page or clearing your browser cache. If the problem persists, submit a support ticket with detailed information about the issue, including screenshots if possible. Our technical team will respond within 24 hours.',
                        open: false
                    },
                    {
                        question: 'How do I contact my teacher?',
                        answer: 'Use the "Messages" feature in the Communication section. You can send direct messages to your teachers and receive replies within the platform. For urgent matters, you may also contact the school office.',
                        open: false
                    },
                    {
                        question: 'Can I access the portal on my mobile phone?',
                        answer: 'Yes, the pupil portal is fully responsive and works on all devices including smartphones and tablets. Simply open your mobile browser and navigate to the portal URL. No app download is required.',
                        open: false
                    }
                ],

                tickets: [
                    // Example tickets - replace with actual data from your backend
                    // {
                    //     id: 1,
                    //     subject: 'Cannot view grades for Q2',
                    //     description: 'The grades page shows an error when I try to view my Q2 grades.',
                    //     category: 'grades',
                    //     status: 'in_progress',
                    //     created_at: '2024-01-15',
                    //     replies: 2,
                    //     replies_list: [
                    //         { author: 'Support Team', is_staff: true, message: 'We are looking into this issue.', created_at: '2024-01-15 10:30 AM' }
                    //     ]
                    // }
                ],

                get filteredFAQs() {
                    if (!this.searchQuery) return this.faqs;
                    const query = this.searchQuery.toLowerCase();
                    return this.faqs.filter(faq => 
                        faq.question.toLowerCase().includes(query) || 
                        faq.answer.toLowerCase().includes(query)
                    );
                },

                get mainContentClass() {
                    if (this.isMobile) {
                        return this.mobileOpen ? 'ml-72' : 'ml-0';
                    }
                    return 'lg:ml-72';
                },

                init() {
                    this.sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                    
                    window.addEventListener('sidebar-toggle', (e) => {
                        this.sidebarCollapsed = e.detail.collapsed;
                    });
                    
                    window.addEventListener('sidebar-mobile-toggle', (e) => {
                        this.mobileOpen = e.detail.open;
                    });

                    window.addEventListener('resize', () => {
                        this.isMobile = window.innerWidth < 1024;
                    });

                    window.addEventListener('storage', (e) => {
                        if (e.key === 'sidebarCollapsed') {
                            this.sidebarCollapsed = e.newValue === 'true';
                        }
                    });

                    this.$watch('toast.show', value => {
                        if (value) setTimeout(() => this.toast.show = false, 3000);
                    });

                    // Load tickets from backend
                    this.loadTickets();
                },

                toggleFaq(index) {
                    // Close all other FAQs
                    this.faqs.forEach((faq, i) => {
                        if (i !== index) faq.open = false;
                    });
                    // Toggle clicked FAQ
                    this.filteredFAQs[index].open = !this.filteredFAQs[index].open;
                },

                filterFAQs() {
                    // Search is handled by computed property
                },

                handleFileUpload(event) {
                    const file = event.target.files[0];
                    if (file && file.size > 5 * 1024 * 1024) {
                        this.showToast('File size must be less than 5MB', 'error');
                        event.target.value = '';
                        return;
                    }
                    this.ticketForm.attachment = file;
                },

                async submitTicket() {
                    // TODO: Submit to backend
                    // const formData = new FormData();
                    // formData.append('subject', this.ticketForm.subject);
                    // formData.append('category', this.ticketForm.category);
                    // formData.append('description', this.ticketForm.description);
                    // if (this.ticketForm.attachment) {
                    //     formData.append('attachment', this.ticketForm.attachment);
                    // }
                    
                    // Simulate submission
                    const newTicket = {
                        id: Date.now(),
                        subject: this.ticketForm.subject,
                        description: this.ticketForm.description,
                        category: this.ticketForm.category,
                        status: 'pending',
                        created_at: new Date().toLocaleDateString(),
                        replies: 0,
                        replies_list: []
                    };
                    
                    this.tickets.unshift(newTicket);
                    this.showTicketModal = false;
                    this.ticketForm = { subject: '', category: '', description: '', attachment: null };
                    this.showToast('Ticket submitted successfully!', 'success');
                },

                viewTicket(ticket) {
                    this.viewingTicket = ticket;
                    this.replyMessage = '';
                },

                sendReply() {
                    if (!this.replyMessage.trim()) return;
                    
                    // TODO: Send reply to backend
                    this.viewingTicket.replies_list.push({
                        author: 'You',
                        is_staff: false,
                        message: this.replyMessage,
                        created_at: new Date().toLocaleString()
                    });
                    this.viewingTicket.replies++;
                    this.replyMessage = '';
                    this.showToast('Reply sent', 'success');
                },

                async loadTickets() {
                    // TODO: Fetch from backend
                    // const response = await fetch('/api/student/tickets');
                    // this.tickets = await response.json();
                },

                showToast(message, type = 'success') {
                    this.toast = { show: true, message, type };
                }
            }
        }
    </script>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\student\help\index.blade.php ENDPATH**/ ?>
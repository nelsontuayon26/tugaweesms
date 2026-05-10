<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($message->subject); ?> - Communications</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f8fafc; }
        
        .scrollbar-thin::-webkit-scrollbar { width: 6px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
        
        /* Chat Bubbles */
        .chat-bubble {
            max-width: 75%;
            padding: 12px 16px;
            border-radius: 16px;
            position: relative;
            display: inline-block;
            text-align: left;
            word-wrap: break-word;
        }
        .chat-bubble.me {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border-bottom-right-radius: 4px;
        }
        .chat-bubble.them {
            background: white;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            border-bottom-left-radius: 4px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 lg:hidden bg-slate-900/30 backdrop-blur-sm"
         @click="mobileOpen = false"
         style="display: none;"></div>

    <!-- Mobile Hamburger -->
    <button @click="mobileOpen = !mobileOpen" 
            class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all border border-slate-100">
        <i class="fas fa-bars text-lg"></i>
    </button>

<div class="flex min-h-screen">
    <?php echo $__env->make('teacher.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php
        $otherPerson = $message->sender_id === auth()->id() ? $message->recipient : $message->sender;
    ?>

    <div class="flex-1 lg:ml-72 min-h-screen flex flex-col">
        
        
        <header class="sticky top-0 z-20 bg-white border-b border-slate-200 h-16 flex-shrink-0">
            <div class="flex items-center h-full px-4 lg:px-6">
                <a href="<?php echo e(route('teacher.communications.index')); ?>" class="p-2 -ml-2 text-slate-600 hover:text-indigo-600 rounded-lg hover:bg-slate-100 transition-all mr-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                
                
                <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold mr-3">
                    <?php echo e(strtoupper(substr($otherPerson->first_name ?? 'U', 0, 1))); ?>

                </div>
                
                <div class="flex-1 min-w-0">
                    <h1 class="font-semibold text-slate-800 truncate"><?php echo e($otherPerson->full_name ?? 'Unknown'); ?></h1>
                    <p class="text-xs text-slate-500"><?php echo e($message->is_read ? 'Active now' : 'Offline'); ?></p>
                </div>
                
                
                <form action="<?php echo e(route('teacher.communications.destroy', $message)); ?>" method="POST" onsubmit="return confirm('Delete this conversation?')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </header>

        
        <main class="flex-1 overflow-y-auto scrollbar-thin bg-slate-50 p-4 lg:p-6">
            <div class="max-w-3xl mx-auto space-y-4">
                <?php if(session('success')): ?>
                    <div class="text-center animate-fade-in">
                        <span class="inline-block bg-emerald-100 text-emerald-700 text-xs px-3 py-1 rounded-full"><?php echo e(session('success')); ?></span>
                    </div>
                <?php endif; ?>

                
                <div class="animate-fade-in <?php echo e($message->sender_id === auth()->id() ? 'text-right' : 'text-left'); ?>">
                    <div class="chat-bubble <?php echo e($message->sender_id === auth()->id() ? 'me shadow-md' : 'them shadow-sm'); ?>">
                        <?php if($message->subject): ?>
                            <p class="font-medium text-sm mb-1 <?php echo e($message->sender_id === auth()->id() ? 'text-white' : 'text-slate-800'); ?>"><?php echo e($message->subject); ?></p>
                        <?php endif; ?>
                        <p class="text-sm whitespace-pre-wrap"><?php echo e($message->body); ?></p>
                        <p class="text-xs <?php echo e($message->sender_id === auth()->id() ? 'text-indigo-200' : 'text-slate-400'); ?> mt-2"><?php echo e($message->created_at->format('M d, g:i A')); ?></p>
                    </div>
                    
                    
                    <?php if($message->attachments->count() > 0): ?>
                        <div class="mt-2 space-y-1 <?php echo e($message->sender_id === auth()->id() ? 'text-right' : 'text-left'); ?>">
                            <?php $__currentLoopData = $message->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('teacher.communications.attachment', $attachment)); ?>" 
                                   class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs hover:bg-indigo-50 hover:border-indigo-300 transition-all">
                                    <i class="fas fa-file text-slate-400"></i>
                                    <span class="text-slate-700"><?php echo e($attachment->file_name); ?></span>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>

                
                <?php $__currentLoopData = $message->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="animate-fade-in <?php echo e($reply->sender_id === auth()->id() ? 'text-right' : 'text-left'); ?>" style="animation-delay: <?php echo e($loop->index * 0.05); ?>s">
                        <div class="chat-bubble <?php echo e($reply->sender_id === auth()->id() ? 'me shadow-md' : 'them shadow-sm'); ?>">
                            <p class="text-sm whitespace-pre-wrap"><?php echo e($reply->body); ?></p>
                            <p class="text-xs <?php echo e($reply->sender_id === auth()->id() ? 'text-indigo-200' : 'text-slate-400'); ?> mt-2">
                                <?php echo e($reply->created_at->format('M d, g:i A')); ?>

                            </p>
                        </div>
                        
                        <?php if($reply->attachments->count() > 0): ?>
                            <div class="mt-1 <?php echo e($reply->sender_id === auth()->id() ? 'text-right' : 'text-left'); ?>">
                                <?php $__currentLoopData = $reply->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('teacher.communications.attachment', $attachment)); ?>" 
                                       class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs hover:bg-indigo-50 transition-all">
                                        <i class="fas fa-file text-slate-400"></i>
                                        <span class="text-slate-700"><?php echo e($attachment->file_name); ?></span>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </main>

        
        <div class="bg-white border-t border-slate-200 p-4">
            <div class="max-w-3xl mx-auto">
                <form action="<?php echo e(route('teacher.communications.reply', $message)); ?>" method="POST" enctype="multipart/form-data" class="flex items-end gap-2">
                    <?php echo csrf_field(); ?>
                    <div class="flex-1 relative">
                        <textarea name="body" rows="1" required placeholder="Type a message..."
                                  class="w-full px-4 py-3 pr-10 bg-slate-100 border-0 rounded-xl resize-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                                  oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"
                                  style="min-height: 44px; max-height: 120px;"></textarea>
                        <label class="absolute right-3 bottom-3 text-slate-400 hover:text-indigo-600 cursor-pointer transition-all">
                            <i class="fas fa-paperclip"></i>
                            <input type="file" name="attachments[]" multiple class="hidden" onchange="showFileNames(this)" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx,.txt">
                        </label>
                    </div>
                    <button type="submit" class="p-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-all flex-shrink-0">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
                <div id="replyFileNames" class="mt-2 text-xs text-slate-500"></div>
            </div>
        </div>
    </div>
</div>

<script>
function showFileNames(input) {
    const names = Array.from(input.files).map(f => f.name).join(', ');
    document.getElementById('replyFileNames').textContent = names ? 'Attached: ' + names : '';
}
</script>

</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\communications\show.blade.php ENDPATH**/ ?>
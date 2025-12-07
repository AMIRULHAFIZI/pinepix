</div> <footer class="bg-white border-t-2 border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                <div class="col-span-1 sm:col-span-2 lg:col-span-2">
                    <div class="flex items-center mb-4">
                        <img src="<?= BASE_URL ?>assets/images/logoblack.png" alt="PinePix Logo" class="h-20 sm:h-24 object-contain">
                    </div>
                    <p class="text-gray-600 text-sm mb-6 max-w-md leading-relaxed">
                        Pineapple Entrepreneur Information Management System - Connecting pineapple entrepreneurs, farms, and businesses in one unified platform.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="https://facebook.com/" target="_blank" rel="noopener" class="w-10 h-10 bg-gray-100 hover:bg-primary-400 rounded-lg flex items-center justify-center text-gray-600 hover:text-white transition-all duration-200 transform hover:scale-110">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://x.com/" target="_blank" rel="noopener" class="w-10 h-10 bg-gray-100 hover:bg-primary-400 rounded-lg flex items-center justify-center text-gray-600 hover:text-white transition-all duration-200 transform hover:scale-110">
                            <i class="fab fa-x"></i>
                        </a>
                        <a href="https://instagram.com/" target="_blank" rel="noopener" class="w-10 h-10 bg-gray-100 hover:bg-primary-400 rounded-lg flex items-center justify-center text-gray-600 hover:text-white transition-all duration-200 transform hover:scale-110">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://linkedin.com/" target="_blank" rel="noopener" class="w-10 h-10 bg-gray-100 hover:bg-primary-400 rounded-lg flex items-center justify-center text-gray-600 hover:text-white transition-all duration-200 transform hover:scale-110">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="<?= BASE_URL ?>" class="w-10 h-10 bg-gray-100 hover:bg-primary-400 rounded-lg flex items-center justify-center text-gray-600 hover:text-white transition-all duration-200 transform hover:scale-110" title="Website">
                            <i class="fas fa-link"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h6 class="font-bold text-gray-900 mb-4 text-lg">Quick Links</h6>
                    <ul class="space-y-3">
                        <li><a href="<?= BASE_URL ?>index.php#" class="text-sm text-gray-600 hover:text-primary-400 transition inline-flex items-center group">
                            <i class="fas fa-chevron-right text-xs mr-2 opacity-0 group-hover:opacity-100 transition text-primary-400"></i>
                            Home
                        </a></li>
                        <li><a href="<?= BASE_URL ?>index.php#announcements" class="text-sm text-gray-600 hover:text-primary-400 transition inline-flex items-center group">
                            <i class="fas fa-chevron-right text-xs mr-2 opacity-0 group-hover:opacity-100 transition text-primary-400"></i>
                            Announcements
                        </a></li>
                    </ul>
                </div>
                
                <div>
                    <h6 class="font-bold text-gray-900 mb-4 text-lg">Support</h6>
                    <ul class="space-y-3">              
                        <li><a href="<?= BASE_URL ?>contact.php" class="text-sm text-gray-600 hover:text-primary-400 transition inline-flex items-center group">
                            <i class="fas fa-chevron-right text-xs mr-2 opacity-0 group-hover:opacity-100 transition text-primary-400"></i>
                            Contact Us
                        </a></li>
                        <li><a href="<?= BASE_URL ?>privacy-policy.php" class="text-sm text-gray-600 hover:text-primary-400 transition inline-flex items-center group">
                            <i class="fas fa-chevron-right text-xs mr-2 opacity-0 group-hover:opacity-100 transition text-primary-400"></i>
                            Privacy Policy
                        </a></li>
                        <li><a href="<?= BASE_URL ?>terms-of-service.php" class="text-sm text-gray-600 hover:text-primary-400 transition inline-flex items-center group">
                            <i class="fas fa-chevron-right text-xs mr-2 opacity-0 group-hover:opacity-100 transition text-primary-400"></i>
                            Terms of Service
                        </a></li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-6 border-t-2 border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0">
                    <p class="text-sm text-gray-600">
                        © <?= date('Y') ?> PinePix. All rights reserved.
                    </p>
                    <p class="text-sm text-gray-600 flex items-center">
                        Made with <i class="fas fa-heart text-red-400 mx-2 animate-pulse"></i> for Pineapple Entrepreneurs
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <div id="chatbotWidget" class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-50" style="position: fixed;">
        <div style="position: relative;">
            <div id="chatbotBox" class="absolute bottom-16 right-0 w-[calc(100vw-3rem)] sm:w-80 h-96 bg-white rounded-2xl shadow-2xl border border-gray-200 transform transition-all duration-300" style="display: none; position: absolute;">
            <div class="bg-primary-600 text-white p-4 rounded-t-2xl flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-robot text-xl mr-2"></i>
                    <div>
                        <h3 class="font-bold text-sm">PinePixAi Chatbot</h3>
                        <p class="text-xs opacity-90">We're here to help</p>
                    </div>
                </div>
                <button id="chatbotClose" class="text-white hover:bg-white/20 rounded-full p-1 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="px-4 pt-3 pb-2 border-b border-gray-200">
                <div class="inline-flex rounded-lg border border-gray-300 overflow-hidden" role="group">
                    <input type="radio" class="hidden peer/faq" name="chatbotMode" id="chatbotModeFAQ" value="faq" checked>
                    <label for="chatbotModeFAQ" class="px-3 py-1.5 border-r border-gray-300 bg-white text-gray-700 cursor-pointer hover:bg-gray-50 peer-checked/faq:bg-primary-600 peer-checked/faq:text-white transition text-xs">
                        <i class="fas fa-question-circle mr-1"></i>FAQ
                    </label>
                    <input type="radio" class="hidden peer/ai" name="chatbotMode" id="chatbotModeAI" value="ai">
                    <label for="chatbotModeAI" class="px-3 py-1.5 bg-white text-gray-700 cursor-pointer hover:bg-gray-50 peer-checked/ai:bg-primary-600 peer-checked/ai:text-white transition text-xs">
                        <i class="fas fa-brain mr-1"></i>AI
                    </label>
                </div>
            </div>
            
            <div id="chatbotContainer" class="flex-1 overflow-y-auto p-4 bg-gray-50 space-y-4">
                <div id="chatbotMessages"></div>
                <div id="chatbotTyping" class="hidden">
                    <div class="flex items-start">
                        <div class="bg-white rounded-lg px-4 py-3 shadow-sm max-w-[80%]">
                            <span class="inline-block w-2 h-2 bg-gray-400 rounded-full animate-bounce mr-1"></span>
                            <span class="inline-block w-2 h-2 bg-gray-400 rounded-full animate-bounce mr-1" style="animation-delay: 0.2s;"></span>
                            <span class="inline-block w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s;"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-4 border-t border-gray-200 bg-white rounded-b-2xl">
                <div class="flex gap-2">
                    <input type="text" id="chatbotInput" placeholder="Type your message..." 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition text-sm">
                    <button id="chatbotSend" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-semibold shadow-lg">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
            </div>
            
            <button id="chatbotToggle" class="relative w-14 h-14 bg-primary-600 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110 flex items-center justify-center group">
                <i class="fas fa-comments text-xl group-hover:scale-110 transition-transform"></i>
                <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
            </button>
        </div>
    </div>
    
    <button id="scrollToTop" class="fixed bottom-6 left-6 sm:bottom-8 sm:left-8 w-12 h-12 bg-primary-400 text-white rounded-full shadow-lg hover:bg-primary-500 hover:shadow-xl transition-all duration-300 transform hover:scale-110 opacity-0 invisible z-40 flex items-center justify-center group" aria-label="Scroll to top">
        <i class="fas fa-arrow-up text-lg group-hover:-translate-y-1 transition-transform"></i>
    </button>
    
    <script>
        // Toast notification helper using SweetAlert2
        window.toast = {
            success: function(msg, opts) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: opts && opts.duration ? opts.duration : 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
                Toast.fire({
                    icon: 'success',
                    title: msg
                });
            },
            error: function(msg, opts) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: opts && opts.duration ? opts.duration : 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
                Toast.fire({
                    icon: 'error',
                    title: msg
                });
            },
            info: function(msg, opts) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: opts && opts.duration ? opts.duration : 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
                Toast.fire({
                    icon: 'info',
                    title: msg
                });
            },
            warning: function(msg, opts) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: opts && opts.duration ? opts.duration : 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
                Toast.fire({
                    icon: 'warning',
                    title: msg
                });
            }
        };
        
        // Ensure Swal is available globally
        window.Swal = Swal;
    </script>
    <?php if (isset($additionalJS)): ?>
        <?php foreach ($additionalJS as $js): ?>
            <script src="<?= $js ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <script src="<?= BASE_URL ?>assets/js/main.js"></script>
    
    <script>
        // Scroll to top button
        const scrollToTopBtn = document.getElementById('scrollToTop');
        
        // Show/hide button based on scroll position
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.remove('opacity-0', 'invisible');
                scrollToTopBtn.classList.add('opacity-100', 'visible');
            } else {
                scrollToTopBtn.classList.remove('opacity-100', 'visible');
                scrollToTopBtn.classList.add('opacity-0', 'invisible');
            }
        });
        
        // Smooth scroll to top when clicked
        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
    
    <script>
        (function() {
            const chatbotWidget = document.getElementById('chatbotWidget');
            const chatbotBox = document.getElementById('chatbotBox');
            const chatbotToggle = document.getElementById('chatbotToggle');
            const chatbotClose = document.getElementById('chatbotClose');
            const chatbotContainer = document.getElementById('chatbotContainer');
            const chatbotMessages = document.getElementById('chatbotMessages');
            const chatbotInput = document.getElementById('chatbotInput');
            const chatbotSend = document.getElementById('chatbotSend');
            const chatbotTyping = document.getElementById('chatbotTyping');
            let chatbotMode = 'faq';
            
            // Toggle chatbot
            chatbotToggle.addEventListener('click', function() {
                const isHidden = chatbotBox.style.display === 'none' || chatbotBox.classList.contains('hidden');
                if (isHidden) {
                    chatbotBox.style.display = 'flex';
                    chatbotBox.classList.remove('hidden');
                    chatbotBox.classList.add('flex', 'flex-col');
                } else {
                    chatbotBox.style.display = 'none';
                    chatbotBox.classList.add('hidden');
                    chatbotBox.classList.remove('flex', 'flex-col');
                }
            });
            
            // Close chatbot
            chatbotClose.addEventListener('click', function() {
                chatbotBox.style.display = 'none';
                chatbotBox.classList.add('hidden');
                chatbotBox.classList.remove('flex', 'flex-col');
            });
            
            // Update mode
            document.querySelectorAll('input[name="chatbotMode"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    chatbotMode = this.value;
                });
            });
            
            // Add message to chat
            function addMessage(message, isUser = true, mode = 'faq') {
                const messageDiv = document.createElement('div');
                messageDiv.className = `flex ${isUser ? 'justify-end' : 'justify-start'}`;
                
                const bubble = document.createElement('div');
                bubble.className = `rounded-lg px-4 py-3 max-w-[80%] ${
                    isUser 
                        ? 'bg-primary-600 text-white' 
                        : 'bg-white shadow-sm'
                }`;
                bubble.innerHTML = message.replace(/\n/g, '<br>');
                
                const time = document.createElement('div');
                time.className = `text-xs mt-2 ${isUser ? 'text-gray-500' : 'text-gray-500'}`;
                time.textContent = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                
                if (!isUser) {
                    const badge = document.createElement('span');
                    badge.className = 'ml-2 px-1.5 py-0.5 bg-gray-200 text-gray-700 rounded text-xs font-medium';
                    badge.textContent = mode.toUpperCase();
                    time.appendChild(badge);
                }
                
                const wrapper = document.createElement('div');
                wrapper.className = `flex flex-col ${isUser ? 'items-end' : 'items-start'}`;
                wrapper.appendChild(bubble);
                wrapper.appendChild(time);
                messageDiv.appendChild(wrapper);
                
                chatbotMessages.appendChild(messageDiv);
                scrollToBottom();
            }
            
            // Scroll to bottom
            function scrollToBottom() {
                chatbotContainer.scrollTop = chatbotContainer.scrollHeight;
            }
            
            // Show typing indicator
            function showTyping() {
                chatbotTyping.classList.remove('hidden');
                scrollToBottom();
            }
            
            // Hide typing indicator
            function hideTyping() {
                chatbotTyping.classList.add('hidden');
            }
            
            // Send message
            async function sendMessage() {
                const message = chatbotInput.value.trim();
                if (!message) return;
                
                addMessage(message, true);
                chatbotInput.value = '';
                showTyping();
                
                try {
                    const response = await fetch('<?= BASE_URL ?>api/chat.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            message: message,
                            mode: chatbotMode
                        })
                    });
                    
                    const data = await response.json();
                    hideTyping();
                    
                    if (data.success) {
                        addMessage(data.response, false, data.mode);
                    } else {
                        addMessage('Error: ' + (data.error || 'Something went wrong'), false);
                    }
                } catch (error) {
                    hideTyping();
                    addMessage('Error connecting to chat service. Please try again.', false);
                }
            }
            
            // Send button click
            chatbotSend.addEventListener('click', sendMessage);
            
            // Enter key to send
            chatbotInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        })();
    </script>
    
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('<?= BASE_URL ?>sw.js')
                    .then(function(registration) {
                        console.log('[PWA] Service Worker registered successfully:', registration.scope);
                        
                        // Check for updates
                        registration.addEventListener('updatefound', function() {
                            const newWorker = registration.installing;
                            newWorker.addEventListener('statechange', function() {
                                if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                    // New service worker available
                                    if (confirm('A new version of PinePix is available. Reload to update?')) {
                                        window.location.reload();
                                    }
                                }
                            });
                        });
                    })
                    .catch(function(error) {
                        console.error('[PWA] Service Worker registration failed:', error);
                    });
                
                // Listen for service worker updates
                let refreshing = false;
                navigator.serviceWorker.addEventListener('controllerchange', function() {
                    if (!refreshing) {
                        refreshing = true;
                        window.location.reload();
                    }
                });
            });
        }
        
        // PWA Install Prompt
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            deferredPrompt = e;
            console.log('[PWA] Install prompt available');
        });
        
        // Function to show install prompt (can be called from a button)
        window.showInstallPrompt = function() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(function(choiceResult) {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('[PWA] User accepted install prompt');
                    } else {
                        console.log('[PWA] User dismissed install prompt');
                    }
                    deferredPrompt = null;
                });
            }
        };
    </script>
    
    <script>
    let routingControl = null;

    function generateRoute() {
        // 1. Get all checked farms
        let selectedFarms = document.querySelectorAll('.farm-selector:checked');
        
        if (selectedFarms.length === 0) {
            Swal.fire({ icon: 'warning', title: 'No Farms Selected', text: 'Please select at least one farm to visit!' });
            return;
        }

        // Check if map is initialized (It must be initialized in index.php as 'map')
        if (typeof map === 'undefined') {
            console.error("Map variable not found. Make sure map is initialized as 'var map = ...'");
            return;
        }

        Swal.fire({
            title: 'Finding Location...',
            text: 'Please allow GPS access to plan your route.',
            didOpen: () => { Swal.showLoading(); }
        });

        // 2. Get User's Current Location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                Swal.close();
                
                let userLat = position.coords.latitude;
                let userLng = position.coords.longitude;
                
                // Define Waypoints: Start with User
                let waypoints = [
                    L.latLng(userLat, userLng)
                ];

                // Add selected farms
                selectedFarms.forEach(function(checkbox) {
                    let lat = parseFloat(checkbox.getAttribute('data-lat'));
                    let lng = parseFloat(checkbox.getAttribute('data-lng'));
                    if(lat && lng) {
                        waypoints.push(L.latLng(lat, lng));
                    }
                });

                // Remove old route
                if (routingControl !== null) {
                    map.removeControl(routingControl);
                }

                // Create Custom Icons
                const createIcon = function(i, wp, nWps) {
                    if (i === 0) {
                        // Start Point (User)
                        return L.divIcon({
                            className: 'custom-div-icon',
                            html: '<div style="background-color:blue; width:30px; height:30px; border-radius:50%; border:2px solid white; display:flex; justify-content:center; align-items:center; box-shadow:0 2px 5px rgba(0,0,0,0.3);"><i class="fas fa-user text-white"></i></div>',
                            iconSize: [30, 30],
                            iconAnchor: [15, 15]
                        });
                    } else {
                        // Destination (Farm)
                        return L.divIcon({
                            className: 'custom-div-icon',
                            html: '<div style="background-color:#f59e0b; width:30px; height:30px; border-radius:50%; border:2px solid white; display:flex; justify-content:center; align-items:center; box-shadow:0 2px 5px rgba(0,0,0,0.3);"><i class="fas fa-leaf text-white"></i></div>',
                            iconSize: [30, 30],
                            iconAnchor: [15, 15]
                        });
                    }
                };

                // 3. Draw Route
                routingControl = L.Routing.control({
                    waypoints: waypoints,
                    routeWhileDragging: false,
                    createMarker: createIcon,
                    lineOptions: {
                        styles: [{color: '#d97706', opacity: 0.8, weight: 6}]
                    },
                    show: true, // Show turn-by-turn directions
                    addWaypoints: false,
                    draggableWaypoints: false,
                    fitSelectedRoutes: true
                }).addTo(map);

                Swal.fire({ icon: 'success', title: 'Route Generated!', timer: 2000, showConfirmButton: false });

            }, function(error) {
                Swal.fire({ icon: 'error', title: 'GPS Error', text: 'Could not get your location. ' + error.message });
            });
        } else {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Geolocation is not supported by this browser.' });
        }
    }

    function clearRoute() {
        if (routingControl !== null && typeof map !== 'undefined') {
            map.removeControl(routingControl);
            routingControl = null;
            
            // Uncheck all boxes
            document.querySelectorAll('.farm-selector').forEach(el => el.checked = false);
            
            // Assuming the toast helper from footer is available
            if (window.toast) {
                window.toast.info('Route cleared');
            }
        }
    }
    </script>
</body>
</html>
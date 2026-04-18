<footer class="text-center mt-5 pb-5 animate__animated animate__fadeInUp" style="background: linear-gradient(to bottom, transparent, #fff0f5);">
        <div class="container">
            <hr class="mx-auto mb-4" style="width: 60px; border: 2px solid #db7093; border-radius: 10px; opacity: 0.6;">
            
            <div class="footer-content">
                <p class="mb-1" style="color: #4e4e4e; font-size: 0.95rem;">
                    &copy; 2026 <span class="fw-bold" style="color: #db7093;">PinkyPromise Booth</span>. 
                    All Rights Reserved.
                </p>
                
                <p class="text-muted small mb-3">
                    Crafted with 
                    <i class="fas fa-heart animate__animated animate__heartBeat animate__infinite text-danger mx-1"></i> 
                    for <span class="fw-bold" style="color: #db7093;">UBSI Karawang</span>
                </p>
                
                <div class="developer-info mb-4">
                    <span class="text-muted small d-block mb-2">Developed by</span>
                    <span class="badge rounded-pill px-4 py-2 shadow-sm" style="background: #db7093; color: white; font-weight: 500; letter-spacing: 0.5px;">
                        Nazwatul Ma'wa
                    </span>
                </div>
                
                <div class="opacity-50 mt-3">
                    <i class="fas fa-star text-warning small mx-1 animate__animated animate__flash animate__infinite animate__slow"></i>
                    <i class="fas fa-sparkles text-pink-gelap small mx-1"></i>
                    <i class="fas fa-star text-warning small mx-1 animate__animated animate__flash animate__infinite animate__slower"></i>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Inisialisasi tooltip atau efek kecil jika diperlukan
        document.addEventListener('DOMContentLoaded', function() {
            console.log("PinkyPromise Booth Ready! 📸");
        });

        // Efek smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>
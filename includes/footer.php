<footer class="text-center mt-5 pb-5 animate__animated animate__fadeInUp">
        <div class="container">
            <hr class="mx-auto mb-4" style="width: 50px; border: 2px solid #ff69b4; border-radius: 10px; opacity: 0.5;">
            
            <p class="text-muted fw-light">
                &copy; 2026 <span class="fw-bold text-pink">PinkyPromise Booth</span>. 
                Created with 
                <i class="fas fa-heart animate__animated animate__heartBeat animate__infinite text-danger mx-1"></i> 
                for <span class="text-pink fw-600">UBSI Karawang</span>
            </p>
            
            <small class="text-muted d-block mt-2 animate__animated animate__fadeIn animate__delay-1s">
                Developed by <span class="badge rounded-pill bg-pink-light text-pink px-3">Nazwatul Ma'wa</span>
            </small>
            
            <div class="mt-3 opacity-50">
                <i class="fas fa-star text-warning small mx-1 animate__animated animate__flash animate__infinite animate__slow"></i>
                <i class="fas fa-camera-retro text-pink small mx-1"></i>
                <i class="fas fa-star text-warning small mx-1 animate__animated animate__flash animate__infinite animate__slower"></i>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Efek smooth scroll jika ada link anchor
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
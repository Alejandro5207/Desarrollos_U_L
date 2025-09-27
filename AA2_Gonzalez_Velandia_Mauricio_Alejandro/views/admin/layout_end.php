            </div>
        </main>
    </div>

    <script>
        function confirmDelete(message = '¿Estás seguro de que quieres eliminar este elemento?') {
            return confirm(message);
        }

        function showAlert(message, type = 'success') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i> ${message}`;
            
            const contentWrapper = document.querySelector('.content-wrapper');
            contentWrapper.insertBefore(alertDiv, contentWrapper.firstChild);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach((item, index) => {
                item.style.setProperty('--i', index + 1);
            });
        });
    </script>
</body>
</html>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Update cart count on every page
function updateCartCount() {
    fetch('get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            const cartCountElem = document.getElementById('cartCount');
            if(cartCountElem) {
                cartCountElem.textContent = data.count || 0;
            }
        })
        .catch(error => console.error('Error:', error));
}

// Call on page load
document.addEventListener('DOMContentLoaded', updateCartCount);
</script>
</body>
</html>
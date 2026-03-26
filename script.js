document.addEventListener('DOMContentLoaded', () => {
    let cart = [];
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotalValue = document.getElementById('cart-total-value');
    const checkoutBtn = document.getElementById('checkout-btn');
    const clearCartBtn = document.getElementById('clear-cart-btn');

    // Add to cart event
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', (e) => {
            const card = e.target.closest('.product-card');
            const product = {
                id: card.dataset.id,
                nombre: card.dataset.nombre,
                precio: parseFloat(card.dataset.precio),
                stock: parseInt(card.dataset.stock)
            };

            addToCart(product);
        });
    });

    function addToCart(product) {
        const existingItem = cart.find(item => item.id === product.id);
        
        if (existingItem) {
            if (existingItem.cantidad < product.stock) {
                existingItem.cantidad++;
            } else {
                alert('No hay más stock disponible para este producto.');
                return;
            }
        } else {
            cart.push({ ...product, cantidad: 1 });
        }
        
        updateCartUI();
    }

    function updateCartUI() {
        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<p>El carrito está vacío.</p>';
            checkoutBtn.disabled = true;
        } else {
            cartItemsContainer.innerHTML = '';
            let total = 0;

            cart.forEach(item => {
                const itemTotal = item.precio * item.cantidad;
                total += itemTotal;

                const itemDiv = document.createElement('div');
                itemDiv.className = 'cart-item';
                itemDiv.innerHTML = `
                    <div>
                        <strong>${item.nombre}</strong><br>
                        ${item.cantidad} x $${item.precio.toFixed(2)}
                    </div>
                    <div>
                        $${itemTotal.toFixed(2)}
                    </div>
                `;
                cartItemsContainer.appendChild(itemDiv);
            });

            cartTotalValue.innerText = total.toFixed(2);
            checkoutBtn.disabled = false;
        }
    }

    // Checkout process
    checkoutBtn.addEventListener('click', () => {
        if (cart.length === 0) return;

        if (confirm('¿Deseas procesar la venta?')) {
            fetch('procesar_venta.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(cart)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('¡Venta realizada con éxito!');
                    if (confirm('¿Deseas descargar la factura en PDF?')) {
                        window.open('factura.php?id=' + data.id, '_blank');
                    }
                    cart = [];
                    updateCartUI();
                    location.reload(); // Refresh to update stock in UI
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al procesar la venta.');
            });
        }
    });

    clearCartBtn.addEventListener('click', () => {
        cart = [];
        updateCartUI();
    });
});

// Cart functionality - usando sesión PHP en lugar de localStorage
function getCart(){
    // El carrito se maneja en el servidor ahora
    return JSON.parse(sessionStorage.getItem('miTiendaCart') || '[]');
}

function saveCart(c){ 
    sessionStorage.setItem('miTiendaCart', JSON.stringify(c)); 
}

function addToCart(name, price){
    const cart = getCart();
    const existing = cart.find(i=>i.name===name);
    if(existing) existing.quantity++;
    else cart.push({name: name, price: Number(price), quantity:1});
    saveCart(cart);
    renderCart();
    alert(name + " agregado al carrito");
}

function renderCart(){
    let cart = getCart();
    let win = document.getElementById('cart-widget');
    if(!win){
        win = document.createElement('div');
        win.id = 'cart-widget';
        document.body.appendChild(win);
    }
    if(cart.length===0){ 
        win.innerHTML = '<div class="cart-empty">Carrito vacío</div>'; 
        return; 
    }
    let html = '<h3>Tu carrito</h3><ul>';
    let total = 0;
    cart.forEach((it,idx)=>{
        html += `<li>${it.name} x${it.quantity} - $${it.price.toLocaleString()}</li>`;
        total += it.price * it.quantity;
    });
    html += `</ul><div class="cart-total">Total: $${total.toLocaleString()}</div>`;
    html += `<div class="cart-actions"><button id="checkout-btn">Pagar</button><button id="clear-cart">Vaciar</button></div>`;
    win.innerHTML = html;
    document.getElementById('checkout-btn').onclick = checkout;
    document.getElementById('clear-cart').onclick = ()=>{
        sessionStorage.removeItem('miTiendaCart'); 
        renderCart();
    };
}

function checkout(){
    const cart = getCart();
    if(cart.length===0){ 
        alert('Tu carrito está vacío'); 
        return; 
    }
    const buyer = prompt('Nombre para el pedido:','Cliente');
    if(!buyer) return;
    
    // Calcular total
    let total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    fetch('order.php', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({
            buyer: buyer, 
            cart: cart, 
            total: total,
            date: new Date().toISOString()
        })
    })
    .then(r=>r.json())
    .then(data=>{
        if(data.success){
            alert('Pedido #' + data.id + ' creado exitosamente. ¡Gracias ' + buyer + '!');
            sessionStorage.removeItem('miTiendaCart');
            renderCart();
        } else {
            alert('Error al crear pedido: ' + (data.error || 'Desconocido'));
        }
    })
    .catch(e=>{ 
        console.error(e);
        alert('Error de red o servidor'); 
    });
}

// Al cargar, enlazar botones Comprar
document.addEventListener('DOMContentLoaded', ()=>{
    document.querySelectorAll('button.buy-btn').forEach(btn=>{
        btn.addEventListener('click', ()=>{
            const card = btn.closest('.product-card');
            const name = card?.querySelector('h3')?.innerText || 'Producto';
            const priceText = card?.querySelector('p')?.innerText || '0';
            const price = priceText.replace(/[^0-9]/g,'');
            addToCart(name, price);
        });
    });
    renderCart();
});
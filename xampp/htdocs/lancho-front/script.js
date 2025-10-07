const responseElement = document.getElementById('response');
const apiBaseUrl = 'http://localhost/lancho-api'; // <-- Endereço base da sua API

function displayResponse(data) {
    responseElement.textContent = JSON.stringify(data, null, 2);
}

// Funções de Produtos
function listarProdutos() {
    fetch(`${apiBaseUrl}/api/produto/read.php`)
        .then(response => response.json())
        .then(data => displayResponse(data))
        .catch(error => displayResponse({ error: error.message }));
}

if (document.getElementById('createProdutoForm')) {
    document.getElementById('createProdutoForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch(`${apiBaseUrl}/api/produto/create.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => displayResponse(data))
            .catch(error => displayResponse({ error: error.message }));
    });
}

if (document.getElementById('updateProdutoForm')) {
    document.getElementById('updateProdutoForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch(`${apiBaseUrl}/api/produto/update.php`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => displayResponse(data))
            .catch(error => displayResponse({ error: error.message }));
    });
}

if (document.getElementById('deleteProdutoForm')) {
    document.getElementById('deleteProdutoForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch(`${apiBaseUrl}/api/produto/delete.php`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => displayResponse(data))
            .catch(error => displayResponse({ error: error.message }));
    });
}

// Funções de Clientes
function listarClientes() {
    fetch(`${apiBaseUrl}/api/cliente/read.php`)
        .then(response => response.json())
        .then(data => displayResponse(data))
        .catch(error => displayResponse({ error: error.message }));
}

if(document.getElementById('readOneClienteForm')) {
    document.getElementById('readOneClienteForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const id = document.getElementById('read_one_id').value;
        fetch(`${apiBaseUrl}/api/cliente/read_one.php?id=${id}`)
            .then(response => response.json())
            .then(data => displayResponse(data))
            .catch(error => displayResponse({ error: error.message }));
    });
}


if (document.getElementById('createClienteForm')) {
    document.getElementById('createClienteForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch(`${apiBaseUrl}/api/cliente/create.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => displayResponse(data))
            .catch(error => displayResponse({ error: error.message }));
    });
}


if (document.getElementById('updateClienteForm')) {
    document.getElementById('updateClienteForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch(`${apiBaseUrl}/api/cliente/update.php`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => displayResponse(data))
            .catch(error => displayResponse({ error: error.message }));
    });
}


if (document.getElementById('deleteClienteForm')) {
    document.getElementById('deleteClienteForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch(`${apiBaseUrl}/api/cliente/delete.php`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => displayResponse(data))
            .catch(error => displayResponse({ error: error.message }));
    });
}


// Funções de Pedidos
function listarPedidos() {
    fetch(`${apiBaseUrl}/api/pedido/read.php`)
        .then(response => response.json())
        .then(data => displayResponse(data))
        .catch(error => displayResponse({ error: error.message }));
}

if (document.getElementById('readByClienteForm')) {
    document.getElementById('readByClienteForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const id_cliente = document.getElementById('id_cliente_read').value;
        fetch(`${apiBaseUrl}/api/pedido/read_by_cliente.php?id_cliente=${id_cliente}`)
            .then(response => response.json())
            .then(data => displayResponse(data))
            .catch(error => displayResponse({ error: error.message }));
    });
}

if (document.getElementById('createPedidoForm')) {
    document.getElementById('createPedidoForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch(`${apiBaseUrl}/api/pedido/create.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => displayResponse(data))
            .catch(error => displayResponse({ error: error.message }));
    });
}


if (document.getElementById('updateStatusPedidoForm')) {
    document.getElementById('updateStatusPedidoForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch(`${apiBaseUrl}/api/pedido/update_status.php`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => displayResponse(data))
            .catch(error => displayResponse({ error: error.message }));
    });
}

if (document.getElementById('deletePedidoForm')) {
    document.getElementById('deletePedidoForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch(`${apiBaseUrl}/api/pedido/delete.php`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => displayResponse(data))
            .catch(error => displayResponse({ error: error.message }));
    });
}

// Funções de Ingredientes
function listarIngredientes() {
    fetch(`${apiBaseUrl}/api/ingredientes/read.php`)
        .then(response => response.json())
        .then(data => displayResponse(data))
        .catch(error => displayResponse({ error: error.message }));
}

if (document.getElementById('createIngredienteForm')) {
    document.getElementById('createIngredienteForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch(`${apiBaseUrl}/api/ingredientes/create.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => displayResponse(data))
            .catch(error => displayResponse({ error: error.message }));
    });
}

if (document.getElementById('deleteIngredienteForm')) {
    document.getElementById('deleteIngredienteForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch(`${apiBaseUrl}/api/ingredientes/delete.php`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => displayResponse(data))
            .catch(error => displayResponse({ error: error.message }));
    });
}

// Funções de Estoque
function listarEstoque() {
    fetch(`${apiBaseUrl}/api/estoque/read.php`)
        .then(response => response.json())
        .then(data => displayResponse(data))
        .catch(error => displayResponse({ error: error.message }));
}

if (document.getElementById('updateEstoqueForm')) {
    document.getElementById('updateEstoqueForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch(`${apiBaseUrl}/api/estoque/update.php`, {
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => displayResponse(data))
            .catch(error => displayResponse({ error: error.message }));
    });
}


// Funções de Avaliações
if (document.getElementById('readAvaliacoesByProdutoForm')) {
    document.getElementById('readAvaliacoesByProdutoForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const produto_id = document.getElementById('produto_id_read').value;
        fetch(`${apiBaseUrl}/api/avaliacoes/read_by_produto.php?produto_id=${produto_id}`)
            .then(response => response.json())
            .then(data => displayResponse(data))
            .catch(error => displayResponse({ error: error.message }));
    });
}


if (document.getElementById('createAvaliacaoForm')) {
    document.getElementById('createAvaliacaoForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch(`${apiBaseUrl}/api/avaliacoes/create.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => displayResponse(data))
            .catch(error => displayResponse({ error: error.message }));
    });
}
document.querySelectorAll('form').forEach(function(form) {
  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    var resp = document.getElementById('response');
    // Se não achar o elemento de resposta, tenta achar por classe em outra div (OPCIONAL)
    if (!resp) resp = form.querySelector('.response');
    if (!resp) return;

    const formData = new FormData(form);
    const data = {};
    formData.forEach((value, key) => data[key] = value);

    let url = '';
    let method = 'POST';

    // PRODUTOS
    if (form.id === 'criarProdutoForm') url = '/php/api/produto/create.php';
    if (form.id === 'atualizarProdutoForm') url = '/php/api/produto/update.php';
    if (form.id === 'deletarProdutoForm') url = '/php/api/produto/delete.php';
    if (form.id === 'listarProdutosForm' || form.id === 'listarProdutosBtn') {
      url = '/php/api/produto/read.php';
      method = 'GET';
    }

    // CLIENTES
    if (form.id === 'criarClienteForm') url = '/php/api/cliente/create.php';
    if (form.id === 'atualizarClienteForm') url = '/php/api/cliente/update.php';
    if (form.id === 'deletarClienteForm') url = '/php/api/cliente/delete.php';
    if (form.id === 'listarClientesForm' || form.id === 'listarClientesBtn') {
      url = '/php/api/cliente/read.php';
      method = 'GET';
    }

    // PEDIDOS
    if (form.id === 'criarPedidoForm') url = '/php/api/pedido/create.php';
    if (form.id === 'atualizarPedidoForm') url = '/php/api/pedido/update.php';
        if (form.id === 'atualizarStatusPedidoForm') url = '/php/api/pedido/update.php';
    if (form.id === 'deletarPedidoForm') url = '/php/api/pedido/delete.php';
    if (form.id === 'listarPedidosForm' || form.id === 'listarPedidosBtn') {
      url = '/php/api/pedido/read.php';
      method = 'GET';
    }

    // ESTOQUE
    if (form.id === 'atualizarEstoqueForm') url = '/php/api/estoque/update.php';
    if (form.id === 'listarEstoqueForm' || form.id === 'listarEstoqueBtn') {
      url = '/php/api/estoque/read.php';
      method = 'GET';
    }

    try {
      const options = { method: method, headers: { 'Content-Type': 'application/json' } };
      if (method === 'POST') options.body = JSON.stringify(data);
      const response = await fetch(url, options);
      const json = await response.json();
      resp.textContent = JSON.stringify(json, null, 2);
    } catch (err) {
      resp.textContent = 'Erro ao chamar a API: ' + err;
    }
  });
});

// Funções para listar dados
async function listarProdutos() {
  const resp = document.getElementById('response');
  if (!resp) return;
  try {
    const response = await fetch('/php/api/produto/read.php', { method: 'GET' });
    const json = await response.json();
    resp.textContent = JSON.stringify(json, null, 2);
  } catch (err) {
    resp.textContent = 'Erro ao chamar a API: ' + err;
  }
}

async function listarClientes() {
  const resp = document.getElementById('response');
  if (!resp) return;
  try {
    const response = await fetch('/php/api/cliente/read.php', { method: 'GET' });
    const json = await response.json();
    resp.textContent = JSON.stringify(json, null, 2);
  } catch (err) {
    resp.textContent = 'Erro ao chamar a API: ' + err;
  }
}

async function listarPedidos() {
  const resp = document.getElementById('response');
  if (!resp) return;
  try {
    const response = await fetch('/php/api/pedido/read.php', { method: 'GET' });
    const json = await response.json();
    resp.textContent = JSON.stringify(json, null, 2);
  } catch (err) {
    resp.textContent = 'Erro ao chamar a API: ' + err;
  }
}

async function listarEstoque() {
  const resp = document.getElementById('response');
  if (!resp) return;
  try {
    const response = await fetch('/php/api/estoque/read.php', { method: 'GET' });
    const json = await response.json();
    resp.textContent = JSON.stringify(json, null, 2);
  } catch (err) {
    resp.textContent = 'Erro ao chamar a API: ' + err;
  }
}

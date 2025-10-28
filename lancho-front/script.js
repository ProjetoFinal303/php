document.querySelectorAll('form').forEach(function(form) {
  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    var resp = document.getElementById('response');
    if (!resp) resp = form.querySelector('.response');
    if (!resp) return;

    const formData = new FormData(form);
    const data = {};
    formData.forEach((value, key) => data[key] = value);
    
    let url = '';
    let method = 'POST'; // Padrão é POST

    // --- PRODUTOS ---
    if (form.id === 'criarProdutoForm') url = '/php/api/produto/create.php';
    if (form.id === 'atualizarProdutoForm') {
        url = '/php/api/produto/update.php';
        method = 'POST'; // CORREÇÃO: Usar POST em vez de PUT
    }
    if (form.id === 'deletarProdutoForm') {
        url = `/php/api/produto/delete.php?id=${data.id}`; // CORREÇÃO: Enviar ID pela URL
        method = 'DELETE';
    }
    if (form.id === 'listarProdutosForm' || form.id === 'listarProdutosBtn') {
      url = '/php/api/produto/read.php';
      method = 'GET';
    }
    
    // --- CLIENTES ---
    if (form.id === 'criarClienteForm') url = '/php/api/cliente/create.php';
    if (form.id === 'atualizarClienteForm') {
        url = '/php/api/cliente/update.php';
        method = 'POST'; // CORREÇÃO: Usar POST em vez de PUT
    }
    if (form.id === 'deletarClienteForm') {
        url = `/php/api/cliente/delete.php?id=${data.id}`; // CORREÇÃO: Enviar ID pela URL
        method = 'DELETE';
    }
    if (form.id === 'listarClientesForm' || form.id === 'listarClientesBtn') {
      url = '/php/api/cliente/read.php';
      method = 'GET';
    }
    
    // --- PEDIDOS ---
    if (form.id === 'criarPedidoForm') url = '/php/api/pedido/create.php';
    if (form.id === 'atualizarPedidoForm') {
        url = '/php/api/pedido/update.php';
        method = 'POST'; // CORREÇÃO: Usar POST em vez de PUT
    }
    if (form.id === 'atualizarStatusPedidoForm') {
        url = '/php/api/pedido/update_status.php';
        method = 'POST'; // CORREÇÃO: Usar POST em vez de PUT
    }
    if (form.id === 'deletarPedidoForm') {
        url = `/php/api/pedido/delete.php?id=${data.id}`; // CORREÇÃO: Enviar ID pela URL
        method = 'DELETE';
    }
    if (form.id === 'listarPedidosForm' || form.id === 'listarPedidosBtn') {
      url = '/php/api/pedido/read.php';
      method = 'GET';
    }
    
    // --- ESTOQUE ---
    if (form.id === 'criarEstoqueForm') url = '/php/api/estoque/create.php';
    if (form.id === 'deletarEstoqueForm') {
        url = `/php/api/estoque/delete.php?id=${data.id}`; // CORREÇÃO: Enviar ID pela URL
        method = 'DELETE';
    }
    if (form.id === 'atualizarEstoqueForm') {
        url = '/php/api/estoque/update.php';
        method = 'POST'; // CORREÇÃO: Usar POST em vez de PUT
    }
    if (form.id === 'listarEstoqueForm' || form.id === 'listarEstoqueBtn') {
      url = '/php/api/estoque/read.php';
      method = 'GET';
    }
    
    // --- AVALIAÇÕES ---
    if (form.id === 'criarAvaliacaoForm') {
        url = '/php/api/avaliacoes/create.php';
        method = 'POST';
    }
    if (form.id === 'listarAvaliacoesForm') {
        url = `/php/api/avaliacoes/read_by_produto.php?produto_id=${data.produto_id}`;
        method = 'GET';
    }
    
    try {
      // CORREÇÃO: Lógica de opções do Fetch
      const options = { method: method };
      
      // Adicionar body e headers APENAS para POST (que agora inclui os updates)
      if (method === 'POST') {
          options.headers = { 'Content-Type': 'application/json' };
          options.body = JSON.stringify(data);
      }
      
      // GET e DELETE não precisam de body.
      // O 'listarAvaliacoesForm' é GET e já tem a URL formatada.
      // Os 'deletar*' são DELETE e já têm a URL formatada.

      const response = await fetch(url, options);
      const json = await response.json();
      resp.textContent = JSON.stringify(json, null, 2);
    } catch (err) {
      resp.textContent = 'Erro ao chamar a API: ' + err;
    }
  });
});

// Funções para listar dados (permanecem iguais)
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

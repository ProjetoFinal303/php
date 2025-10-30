document.querySelectorAll('form').forEach(function(form) {
  form.addEventListener('submit', async function(e) {
  console.log("FORM SUBMITTED! ID:", form.id);
    e.preventDefault();
    var resp = document.getElementById('response');
    if (!resp) resp = form.querySelector('.response');
    if (!resp) return;
    const formData = new FormData(form);
    const data = {};
    formData.forEach((value, key) => data[key] = value);
    let url = '';
    let method = 'POST';
    
    // PRODUTO
    if (form.id === 'criarProdutoForm') {
        url = '/php/api/produto/create.php';
    }
    if (form.id === 'atualizarProdutoForm') {
        url = '/php/api/produto/update.php';
        method = 'POST';
        data.action = 'update';
    }
    if (form.id === 'deletarProdutoForm') {
        url = '/php/api/produto/delete.php';
        method = 'POST';
        data.action = 'delete';
    }
    if (form.id === 'listarProdutosForm' || form.id === 'listarProdutosBtn') {
      url = '/php/api/produto/read.php';
      method = 'GET';
    }
    
    // CLIENTE
    if (form.id === 'criarClienteForm') {
        url = '/php/api/cliente/create.php';
    }
    if (form.id === 'atualizarClienteForm') {
        url = '/php/api/cliente/update.php';
        method = 'POST';
        data.action = 'update';
    }
    if (form.id === 'deletarClienteForm') {
        url = '/php/api/cliente/delete.php';
        method = 'POST';
        data.action = 'delete';
    }
    if (form.id === 'listarClientesForm' || form.id === 'listarClientesBtn') {
      url = '/php/api/cliente/read.php';
      method = 'GET';
    }
    // NOVO: Buscar cliente por ID
    if (form.id === 'buscarClienteForm') {
      url = '/php/api/cliente/read_one.php';
      method = 'GET';
      if (data.id) {
        url += '?id=' + encodeURIComponent(data.id);
      } else {
        resp.textContent = 'ID do Cliente é obrigatório.';
        return;
      }
    }
    
    // PEDIDO
    if (form.id === 'criarPedidoForm') {
        url = '/php/api/pedido/create.php';
    }
    if (form.id === 'atualizarPedidoForm') {
        url = '/php/api/pedido/update.php';
        method = 'POST';
        data.action = 'update';
    }
    if (form.id === 'atualizarStatusPedidoForm') {
        url = '/php/api/pedido/update_status.php';
        method = 'POST';
        data.action = 'update';
    }
    if (form.id === 'deletarPedidoForm') {
        url = '/php/api/pedido/delete.php';
        method = 'POST';
        data.action = 'delete';
    }
    if (form.id === 'listarPedidosForm' || form.id === 'listarPedidosBtn') {
      url = '/php/api/pedido/read.php';
      method = 'GET';
    }
    // NOVO: Listar pedidos por cliente
    if (form.id === 'listarPedidosClienteForm') {
      url = '/php/api/pedido/read.php';
      method = 'GET';
      if (data.cliente_id) {
        url += '?cliente_id=' + encodeURIComponent(data.cliente_id);
      }
    }
    
    // ESTOQUE
    if (form.id === 'criarEstoqueForm') {
        url = '/php/api/estoque/create.php';
    }
    if (form.id === 'atualizarEstoqueForm') {
        url = '/php/api/estoque/update.php';
        method = 'POST';
        data.action = 'update';
    }
    if (form.id === 'deletarEstoqueForm') {
        url = '/php/api/estoque/delete.php';
        method = 'POST';
        data.action = 'delete';
    }
    if (form.id === 'listarEstoqueForm') {
      url = '/php/api/estoque/read.php';
      method = 'GET';
    }
    
    // AVALIAÇÃO
    if (form.id === 'criarAvaliacaoForm') {
        url = '/php/api/avaliacao/create.php';
    }
    if (form.id === 'atualizarAvaliacaoForm') {
        url = '/php/api/avaliacao/update.php';
        method = 'POST';
        data.action = 'update';
    }
    if (form.id === 'deletarAvaliacaoForm') {
        url = '/php/api/avaliacao/delete.php';
        method = 'POST';
        data.action = 'delete';
    }
    // CORRIGIDO: Listar avaliações por produto usando o endpoint correto
    if (form.id === 'listarAvaliacoesForm') {
      url = '/php/api/avaliacoes/read_by_produto.php';
      method = 'GET';
      if (data.produto_id) {
        url += '?produto_id=' + encodeURIComponent(data.produto_id);
      } else {
        resp.textContent = 'ID do Produto é obrigatório.';
        return;
      }
    }
    
    if (!url) return;
    
    try {
      const options = { method: method, headers: { 'Content-Type': 'application/json' } };
      // NãO enviar 'body' em requisições GET
      if (method !== 'GET') {
        options.body = JSON.stringify(data);
      }
      const response = await fetch(url, options);
      
      // MELHORADO: Verifica se a resposta é OK antes de tentar parsear JSON
      if (!response.ok) {
        throw new Error('Erro HTTP: ' + response.status + ' ' + response.statusText);
      }
      
      const json = await response.json();
      resp.textContent = JSON.stringify(json, null, 2);
    } catch (err) {
      resp.textContent = 'Erro ao chamar a API: ' + err.message;
      console.error('Erro completo:', err);
    }
  });
});

// FUNÇÕES AUXILIARES (mantidas para compatibilidade com botões)
async function listarProdutos() {
  const resp = document.getElementById('response');
  if (!resp) return;
  try {
    const response = await fetch('/php/api/produto/read.php', { method: 'GET' });
    if (!response.ok) {
      throw new Error('Erro HTTP: ' + response.status);
    }
    const json = await response.json();
    resp.textContent = JSON.stringify(json, null, 2);
  } catch (err) {
    resp.textContent = 'Erro ao chamar a API: ' + err.message;
    console.error('Erro completo:', err);
  }
}

async function listarClientes() {
  const resp = document.getElementById('response');
  if (!resp) return;
  try {
    const response = await fetch('/php/api/cliente/read.php', { method: 'GET' });
    if (!response.ok) {
      throw new Error('Erro HTTP: ' + response.status);
    }
    const json = await response.json();
    resp.textContent = JSON.stringify(json, null, 2);
  } catch (err) {
    resp.textContent = 'Erro ao chamar a API: ' + err.message;
    console.error('Erro completo:', err);
  }
}

async function listarPedidos() {
  const resp = document.getElementById('response');
  if (!resp) return;
  try {
    const response = await fetch('/php/api/pedido/read.php', { method: 'GET' });
    if (!response.ok) {
      throw new Error('Erro HTTP: ' + response.status);
    }
    const json = await response.json();
    resp.textContent = JSON.stringify(json, null, 2);
  } catch (err) {
    resp.textContent = 'Erro ao chamar a API: ' + err.message;
    console.error('Erro completo:', err);
  }
}

async function listarEstoque() {
  const resp = document.getElementById('response');
  if (!resp) return;
  try {
    const response = await fetch('/php/api/estoque/read.php', { method: 'GET' });
    if (!response.ok) {
      throw new Error('Erro HTTP: ' + response.status);
    }
    const json = await response.json();
    resp.textContent = JSON.stringify(json, null, 2);
  } catch (err) {
    resp.textContent = 'Erro ao chamar a API: ' + err.message;
    console.error('Erro completo:', err);
  }
}

// CORREÇÃO: Usando form.getAttribute('id') para evitar conflito com input name="id"
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
    let method = 'POST';
    
    const formId = form.getAttribute('id'); 
    
    // PRODUTO
    if (formId === 'criarProdutoForm') {
        url = '../api/produto/create.php'; // CAMINHO CORRIGIDO
    }
    if (formId === 'atualizarProdutoForm') {
        url = '../api/produto/update.php'; // CAMINHO CORRIGIDO
        method = 'POST';
        data.action = 'update';
    }
    if (formId === 'deletarProdutoForm') {
        url = '../api/produto/delete.php'; // CAMINHO CORRIGIDO
        method = 'POST';
        data.action = 'delete';
    }
    if (formId === 'listarProdutosForm' || formId === 'listarProdutosBtn') {
      url = '../api/produto/read.php'; // CAMINHO CORRIGIDO
      method = 'GET';
    }
    
    // CLIENTE
    if (formId === 'criarClienteForm') {
        url = '../api/cliente/create.php'; // CAMINHO CORRIGIDO
    }
    if (formId === 'atualizarClienteForm') {
        url = '../api/cliente/update.php'; // CAMINHO CORRIGIDO
        method = 'POST';
        data.action = 'update';
    }
    if (formId === 'deletarClienteForm') {
        url = '../api/cliente/delete.php'; // CAMINHO CORRIGIDO
        method = 'POST';
        data.action = 'delete';
    }
    if (formId === 'listarClientesForm' || formId === 'listarClientesBtn') {
      url = '../api/cliente/read.php'; // CAMINHO CORRIGIDO
      method = 'GET';
    }
    if (formId === 'buscarClienteForm') {
      url = '../api/cliente/read_one.php'; // CAMINHO CORRIGIDO
      method = 'GET';
      if (data.id) {
        url += '?id=' + encodeURIComponent(data.id);
      } else {
        resp.textContent = 'ID do Cliente é obrigatório.';
        return;
      }
    }
    
    // PEDIDO
    if (formId === 'criarPedidoForm') {
        url = '../api/pedido/create.php'; // CAMINHO CORRIGIDO
    }
    if (formId === 'atualizarPedidoForm') {
        url = '../api/pedido/update.php'; // CAMINHO CORRIGIDO
        method = 'POST';
        data.action = 'update';
    }
    if (formId === 'atualizarStatusPedidoForm') {
        url = '../api/pedido/update_status.php'; // CAMINHO CORRIGIDO
        method = 'POST';
        data.action = 'update';
    }
    if (formId === 'deletarPedidoForm') {
        url = '../api/pedido/delete.php'; // CAMINHO CORRIGIDO
        method = 'POST';
        data.action = 'delete';
    }
    if (formId === 'listarPedidosForm' || formId === 'listarPedidosBtn') {
      url = '../api/pedido/read.php'; // CAMINHO CORRIGIDO
      method = 'GET';
    }
    if (formId === 'listarPedidosClienteForm') {
      url = '../api/pedido/read_by_cliente.php'; // CAMINHO CORRIGIDO
      method = 'GET';
      if (data.cliente_id) {
        url += '?cliente_id=' + encodeURIComponent(data.cliente_id);
      }
    }
    
    // ESTOQUE
    if (formId === 'criarEstoqueForm') {
        url = '../api/estoque/create.php'; // CAMINHO CORRIGIDO
    }
    if (formId === 'atualizarEstoqueForm') {
        url = '../api/estoque/update.php'; // CAMINHO CORRIGIDO
        method = 'POST';
        data.action = 'update';
    }
    if (formId === 'deletarEstoqueForm') {
        url = '../api/estoque/delete.php'; // CAMINHO CORRIGIDO
        method = 'POST';
        data.action = 'delete';
    }
    if (formId === 'listarEstoqueForm') {
      url = '../api/estoque/read.php'; // CAMINHO CORRIGIDO
      method = 'GET';
    }
    
    // AVALIAÇÃO
    if (formId === 'criarAvaliacaoForm') {
        url = '../api/avaliacoes/create.php'; // CAMINHO CORRIGIDO
    }
    if (formId === 'atualizarAvaliacaoForm') {
        url = '../api/avaliacao/update.php'; // CAMINHO CORRIGIDO
        method = 'POST';
        data.action = 'update';
    }
    if (formId === 'deletarAvaliacaoForm') {
        url = '../api/avaliacao/delete.php'; // CAMINHO CORRIGIDO
        method = 'POST';
        data.action = 'delete';
    }
    if (formId === 'listarAvaliacoesForm') {
      url = '../api/avaliacoes/read_by_produto.php'; // CAMINHO CORRIGIDO
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
      if (method !== 'GET') {
        options.body = JSON.stringify(data);
      }
      const response = await fetch(url, options);
      
      if (!response.ok) {
        // Lançar erro para o catch
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

// FUNÇÕES AUXILIARES (também corrigidas)
async function listarProdutos() {
  const resp = document.getElementById('response');
  if (!resp) return;
  try {
    const response = await fetch('../api/produto/read.php', { method: 'GET' }); // CAMINHO CORRIGIDO
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
    const response = await fetch('../api/cliente/read.php', { method: 'GET' }); // CAMINHO CORRIGIDO
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
    const response = await fetch('../api/pedido/read.php', { method: 'GET' }); // CAMINHO CORRIGIDO
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
    const response = await fetch('../api/estoque/read.php', { method: 'GET' }); // CAMINHO CORRIGIDO
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

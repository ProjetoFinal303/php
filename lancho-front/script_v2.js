// Remove o console.log de depuração da linha 5
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
    
    // CORRIGIDO: Usando form.getAttribute('id') para evitar conflito com input name="id"
    const formId = form.getAttribute('id'); 
    
    // PRODUTO
    if (formId === 'criarProdutoForm') { // CORRIGIDO
        url = '/php/api/produto/create.php';
    }
    if (formId === 'atualizarProdutoForm') { // CORRIGIDO
        url = '/php/api/produto/update.php';
        method = 'POST';
        data.action = 'update';
    }
    if (formId === 'deletarProdutoForm') { // CORRIGIDO
        url = '/php/api/produto/delete.php';
        method = 'POST';
        data.action = 'delete';
    }
    if (formId === 'listarProdutosForm' || formId === 'listarProdutosBtn') { // CORRIGIDO
      url = '/php/api/produto/read.php';
      method = 'GET';
    }
    
    // CLIENTE
    if (formId === 'criarClienteForm') { // CORRIGIDO
        url = '/php/api/cliente/create.php';
    }
    if (formId === 'atualizarClienteForm') { // CORRIGIDO
        url = '/php/api/cliente/update.php';
        method = 'POST';
        data.action = 'update';
    }
    if (formId === 'deletarClienteForm') { // CORRIGIDO
        url = '/php/api/cliente/delete.php';
        method = 'POST';
        data.action = 'delete';
    }
    if (formId === 'listarClientesForm' || formId === 'listarClientesBtn') { // CORRIGIDO
      url = '/php/api/cliente/read.php';
      method = 'GET';
    }
    if (formId === 'buscarClienteForm') { // CORRIGIDO
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
    if (formId === 'criarPedidoForm') { // CORRIGIDO
        url = '/php/api/pedido/create.php';
    }
    if (formId === 'atualizarPedidoForm') { // CORRIGIDO
        url = '/php/api/pedido/update.php';
        method = 'POST';
        data.action = 'update';
    }
    if (formId === 'atualizarStatusPedidoForm') { // CORRIGIDO
        url = '/php/api/pedido/update_status.php';
        method = 'POST';
        data.action = 'update';
    }
    if (formId === 'deletarPedidoForm') { // CORRIGIDO
        url = '/php/api/pedido/delete.php';
        method = 'POST';
        data.action = 'delete';
    }
    if (formId === 'listarPedidosForm' || formId === 'listarPedidosBtn') { // CORRIGIDO
      url = '/php/api/pedido/read.php';
      method = 'GET';
    }
    if (formId === 'listarPedidosClienteForm') { // CORRIGIDO
      url = '/php/api/pedido/read_by_cliente.php'; // API CORRIGIDA (estava read.php)
      method = 'GET';
      if (data.cliente_id) {
        url += '?cliente_id=' + encodeURIComponent(data.cliente_id);
      }
    }
    
    // ESTOQUE
    if (formId === 'criarEstoqueForm') { // CORRIGIDO
        url = '/php/api/estoque/create.php';
    }
    if (formId === 'atualizarEstoqueForm') { // CORRIGIDO
        url = '/php/api/estoque/update.php';
        method = 'POST';
        data.action = 'update';
    }
    if (formId === 'deletarEstoqueForm') { // CORRIGIDO
        url = '/php/api/estoque/delete.php';
        method = 'POST';
        data.action = 'delete';
    }
    if (formId === 'listarEstoqueForm') { // CORRIGIDO
      url = '/php/api/estoque/read.php';
      method = 'GET';
    }
    
    // AVALIAÇÃO
    if (formId === 'criarAvaliacaoForm') { // CORRIGIDO
        url = '/php/api/avaliacoes/create.php'; // API CORRIGIDA (faltava 's')
    }
    if (formId === 'atualizarAvaliacaoForm') { // CORRIGIDO
        url = '/php/api/avaliacao/update.php';
        method = 'POST';
        data.action = 'update';
    }
    if (formId === 'deletarAvaliacaoForm') { // CORRIGIDO
        url = '/php/api/avaliacao/delete.php';
        method = 'POST';
        data.action = 'delete';
    }
    if (formId === 'listarAvaliacoesForm') { // CORRIGIDO
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
      if (method !== 'GET') {
        options.body = JSON.stringify(data);
      }
      const response = await fetch(url, options);
      
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

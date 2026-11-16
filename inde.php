
<!doctype html>
<html lang="pt-BR">
<head><?php

require_once __DIR__ . '/config/database.php';

$sql = "SELECT
            p.id,
            p.nome,
            p.descricao,
            p.preco,
            p.estoque,
            p.imagem,
            c.nome AS categoria_nome
        FROM produtos p
        LEFT JOIN categorias c ON c.id = p.categoria_id
        WHERE p.ativo = 1
        ORDER BY p.id ASC";

$stmt = $pdo->query($sql);

$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

$products = [];

foreach ($registros as $produto) {

    $categoriaNome = trim($produto['categoria_nome'] ?? '');

    $categoriaSlug = strtolower($categoriaNome);

    $categoriaSlug = strtr($categoriaSlug, [
        'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a',
        'é' => 'e', 'ê' => 'e',
        'í' => 'i',
        'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
        'ú' => 'u', 'ü' => 'u',
        'ç' => 'c'
    ]);

    $categoriaSlug = preg_replace('/[^a-z0-9]+/', '', $categoriaSlug);

    $products[] = [
        'id' => (int) $produto['id'],
        'name' => $produto['nome'],
        'category' => $categoriaSlug,
        'categoryLabel' => $categoriaNome,
        'price' => (float) $produto['preco'],
        'old' => 0,
        'stock' => (int) $produto['estoque'],
        'image' => !empty($produto['imagem'])
            ? $produto['imagem']
            : 'https://placehold.co/600x400?text=TechStore'
    ];
}

?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TechStore | Tecnologia para o seu dia</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --ink: #101828;
      --muted: #667085;
      --brand: #6759e8;
      --brand-dark: #5144c7;
    }

    * { box-sizing: border-box; }

    body {
      font-family: 'DM Sans', sans-serif;
      color: var(--ink);
      background: #fff;
    }

    .navbar {
      background: #fff;
      border-bottom: 1px solid #edf0f5;
    }

    .brand-mark {
      width: 36px;
      height: 36px;
      border-radius: 11px;
      background: var(--brand);
      display: inline-grid;
      place-items: center;
      color: #fff;
      font-weight: 800;
    }

    .navbar-brand {
      font-weight: 800;
      letter-spacing: -.5px;
    }

    .nav-link {
      font-weight: 600;
      color: #475467 !important;
    }

    .nav-link:hover,
    .nav-link.active {
      color: var(--brand) !important;
    }

    .btn-brand {
      background: var(--brand);
      border-color: var(--brand);
      color: #fff;
      font-weight: 700;
    }

    .btn-brand:hover {
      background: var(--brand-dark);
      border-color: var(--brand-dark);
      color: #fff;
    }

    .hero {
      background: linear-gradient(115deg, #17163b 0%, #29266b 58%, #5144c7 100%);
      color: #fff;
      border-radius: 26px;
      overflow: hidden;
      position: relative;
    }

    .hero:after {
      content: "";
      position: absolute;
      width: 390px;
      height: 390px;
      border: 1px solid #ffffff24;
      border-radius: 50%;
      right: 5%;
      top: -120px;
      box-shadow: 0 0 0 45px #ffffff0b, 0 0 0 90px #ffffff08;
    }

    .hero-content {
      position: relative;
      z-index: 1;
    }

    .hero-kicker {
      color: #c8c4ff;
      font-size: .78rem;
      font-weight: 800;
      letter-spacing: 1.7px;
      text-transform: uppercase;
    }

    .hero h1 {
      font-size: clamp(2.2rem, 5vw, 4rem);
      font-weight: 800;
      letter-spacing: -2px;
      line-height: 1.05;
    }

    .hero p {
      color: #e1e0ff;
      max-width: 490px;
    }

    .hero-img {
      width: 100%;
      max-height: 330px;
      object-fit: contain;
      filter: drop-shadow(0 25px 25px #0005);
      position: relative;
      z-index: 1;
    }

    .section-label {
      color: var(--brand);
      font-weight: 800;
      text-transform: uppercase;
      font-size: .75rem;
      letter-spacing: 1.5px;
    }

    .section-title {
      font-weight: 800;
      letter-spacing: -1px;
    }

    .category-card {
      border: 1px solid #eaecf0;
      border-radius: 16px;
      padding: 19px 12px;
      text-align: center;
      color: var(--ink);
      text-decoration: none;
      transition: .2s;
      display: block;
      height: 100%;
    }

    .category-card:hover {
      border-color: #c7c2ff;
      transform: translateY(-3px);
      box-shadow: 0 10px 25px #27205c0d;
      color: var(--brand);
    }

    .category-icon {
      width: 48px;
      height: 48px;
      border-radius: 15px;
      background: #f0efff;
      color: var(--brand);
      display: grid;
      place-items: center;
      margin: 0 auto 10px;
      font-size: 1.35rem;
    }

    .product-card {
      height: 100%;
      border: 1px solid #eaecf0;
      border-radius: 18px;
      overflow: hidden;
      transition: .2s;
    }

    .product-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 14px 32px #10182812;
    }

    .product-image {
      height: 205px;
      background: #f7f8fc;
      display: grid;
      place-items: center;
      padding: 18px;
    }

    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      mix-blend-mode: multiply;
    }

    .product-info {
      padding: 17px;
    }

    .product-category {
      font-size: .72rem;
      color: var(--muted);
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .7px;
    }

    .product-name {
      font-weight: 700;
      min-height: 48px;
    }

    .price {
      font-size: 1.25rem;
      font-weight: 800;
    }

    .old-price {
      font-size: .8rem;
      color: #98a2b3;
      text-decoration: line-through;
    }

    .stock {
      font-size: .78rem;
      color: #079455;
    }

    .filter-btn {
      border: 1px solid #e4e7ec;
      background: #fff;
      border-radius: 30px;
      padding: 8px 15px;
      font-size: .86rem;
      font-weight: 700;
      color: #475467;
    }

    .filter-btn.active,
    .filter-btn:hover {
      background: #f0efff;
      border-color: #d7d2ff;
      color: var(--brand);
    }

    .promo {
      background: #f2f1ff;
      border-radius: 22px;
    }

    .promo-icon { font-size: 2.4rem; }

    footer {
      background: #101828;
      color: #d0d5dd;
    }

    footer a {
      color: #d0d5dd;
      text-decoration: none;
    }

    footer a:hover { color: #fff; }

    .cart-count {
      font-size: .68rem;
      min-width: 19px;
      height: 19px;
      display: inline-grid;
      place-items: center;
      border-radius: 50%;
      background: #ef4444;
      color: white;
      vertical-align: top;
      margin-left: -5px;
    }

    .empty-state {
      background: #f8f9fc;
      border-radius: 16px;
      padding: 35px;
      text-align: center;
      color: var(--muted);
    }

    @media(max-width: 767px) {
      .hero { border-radius: 18px; }
      .hero-img { max-height: 210px; }
      .product-image { height: 175px; }
    }
  </style>
</head>

<body>

<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container py-2">

    <a class="navbar-brand d-flex align-items-center gap-2" href="#">
      <span class="brand-mark">T</span> TechStore
    </a>

    <button class="navbar-toggler" type="button"
      data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menu">

      <ul class="navbar-nav mx-auto gap-lg-3">
        <li class="nav-item"><a class="nav-link active" href="#inicio">Início</a></li>
        <li class="nav-item"><a class="nav-link" href="#categorias">Categorias</a></li>
        <li class="nav-item"><a class="nav-link" href="#produtos">Produtos</a></li>
        <li class="nav-item"><a class="nav-link" href="#sobre">Sobre</a></li>
      </ul>

      <div class="d-flex gap-2 mt-3 mt-lg-0">
        <button class="btn btn-light fw-semibold"
          data-bs-toggle="modal" data-bs-target="#loginModal">
          Entrar
        </button>

        <button class="btn btn-brand"
          data-bs-toggle="offcanvas" data-bs-target="#cartPanel">
          Carrinho <span class="cart-count" id="cartCount">0</span>
        </button>
      </div>

    </div>
  </div>
</nav>

<main class="container py-4 py-lg-5">

 
  <section class="hero p-4 p-md-5 mb-5" id="inicio">
    <div class="row align-items-center g-4">

      <div class="col-lg-6 hero-content">
        <div class="hero-kicker mb-3">
          Tecnologia que acompanha você
        </div>

        <h1 class="mb-3">
          O próximo nível começa aqui.
        </h1>

        <p class="mb-4">
          Encontre os produtos que facilitam sua rotina,
          potencializam seu trabalho e levam seu entretenimento mais longe.
        </p>

        <a href="#produtos" class="btn btn-light fw-bold px-4 py-3">
          Explorar produtos →
        </a>
      </div>

      <div class="col-lg-6 text-center">
        <img class="hero-img"
          src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1000&q=85"
          alt="Equipamentos de tecnologia sobre uma mesa de trabalho">
      </div>

    </div>
  </section>

  
  <section id="categorias" class="mb-5">

    <div class="mb-4">
      <div class="section-label mb-2">Navegue por interesse</div>
      <h2 class="section-title h2 mb-0">Categorias</h2>
    </div>

    <div class="row g-3">

      <div class="col-6 col-md-3">
        <a class="category-card" href="#produtos" data-category-link="smartphones">
          <div class="category-icon">📱</div>
          <strong>Smartphones</strong>
        </a>
      </div>

      <div class="col-6 col-md-3">
        <a class="category-card" href="#produtos" data-category-link="informatica">
          <div class="category-icon">💻</div>
          <strong>Informática</strong>
        </a>
      </div>

      <div class="col-6 col-md-3">
        <a class="category-card" href="#produtos" data-category-link="acessorios">
          <div class="category-icon">🎧</div>
          <strong>Acessórios</strong>
        </a>
      </div>

      <div class="col-6 col-md-3">
        <a class="category-card" href="#produtos" data-category-link="games">
          <div class="category-icon">🎮</div>
          <strong>Games</strong>
        </a>
      </div>

    </div>
  </section>

  
  <section id="produtos" class="mb-5">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">

      <div>
        <div class="section-label mb-2">Seleção da loja</div>
        <h2 class="section-title h2 mb-0">Produtos em destaque</h2>
      </div>

      <div class="input-group" style="max-width:330px">
        <span class="input-group-text bg-white">⌕</span>
        <input id="searchInput" class="form-control"
          type="search" placeholder="Buscar produto..."
          aria-label="Buscar produto">
      </div>

    </div>

    <div class="d-flex flex-wrap gap-2 mb-4" id="filters">
      <button class="filter-btn active" data-filter="todos">Todos</button>
      <button class="filter-btn" data-filter="smartphones">Smartphones</button>
      <button class="filter-btn" data-filter="informatica">Informática</button>
      <button class="filter-btn" data-filter="acessorios">Acessórios</button>
      <button class="filter-btn" data-filter="games">Games</button>
    </div>

    <div class="row g-3 g-lg-4" id="productGrid"></div>

    <div id="noResults" class="empty-state mt-3 d-none">
      Nenhum produto encontrado. Tente outra busca ou categoria.
    </div>

  </section>

  
  <section class="promo p-4 p-md-5 mb-5" id="sobre">

    <div class="row align-items-center g-3">

      <div class="col-md-8">
        <div class="section-label mb-2">Compra simples e segura</div>

        <h2 class="section-title h3">
          Tecnologia boa é tecnologia ao seu alcance.
        </h2>

        <p class="text-secondary mb-0">
          Na TechStore, você encontra variedade, informações claras
          e uma experiência de compra prática.
        </p>
      </div>

      <div class="col-md-4 text-md-end">
        <span class="promo-icon">🚚</span>
        <div class="fw-bold mt-2">Praticidade do início ao fim</div>
      </div>

    </div>
  </section>

</main>


<footer class="pt-5 pb-4">
  <div class="container">

    <div class="row g-4">

      <div class="col-md-6">
        <div class="d-flex align-items-center gap-2 text-white fw-bold fs-5">
          <span class="brand-mark">T</span> TechStore
        </div>
        <p class="mt-3 mb-0">Sua loja de tecnologia e inovação.</p>
      </div>

      <div class="col-md-3">
        <strong class="text-white">Navegação</strong>
        <div class="d-flex flex-column gap-2 mt-3">
          <a href="#inicio">Início</a>
          <a href="#categorias">Categorias</a>
          <a href="#produtos">Produtos</a>
        </div>
      </div>

      <div class="col-md-3">
        <strong class="text-white">Atendimento</strong>
        <p class="mt-3 mb-1">contato@techstore.com</p>
        <small>Projeto acadêmico de e-commerce</small>
      </div>

    </div>

    <hr class="border-secondary my-4">

    <div class="small text-center">
      © 2026 TechStore — Projeto acadêmico.
    </div>

  </div>
</footer>


<div class="offcanvas offcanvas-end" tabindex="-1"
  id="cartPanel" aria-labelledby="cartTitle">

  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title fw-bold" id="cartTitle">
      Seu carrinho
    </h5>

    <button type="button" class="btn-close"
      data-bs-dismiss="offcanvas" aria-label="Fechar">
    </button>
  </div>

  <div class="offcanvas-body">

    <div id="cartItems"></div>

    <div class="border-top pt-3 mt-3">

      <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
        <span>Total</span>
        <span id="cartTotal">R$ 0,00</span>
      </div>

      <button class="btn btn-brand w-100" id="checkoutButton">
        Continuar para checkout
      </button>

      <p class="small text-secondary mt-2 mb-0">
        Demonstração visual: login, pagamento e gravação do pedido
        precisam ser implementados no back-end PHP.
      </p>

    </div>
  </div>
</div>

<!-- MODAL DE LOGIN -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4">

      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Acesse sua conta</h5>

        <button type="button" class="btn-close"
          data-bs-dismiss="modal" aria-label="Fechar">
        </button>
      </div>

      <div class="modal-body">

        <form id="loginDemo">

          <div class="mb-3">
            <label class="form-label fw-semibold" for="loginEmail">
              E-mail
            </label>
            <input class="form-control" id="loginEmail"
              type="email" required placeholder="voce@email.com">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="loginPassword">
              Senha
            </label>
            <input class="form-control" id="loginPassword"
              type="password" required placeholder="Sua senha">
          </div>

          <button class="btn btn-brand w-100" type="submit">
            Entrar
          </button>

        </form>

        <p class="small text-secondary mt-3 mb-0">
          Tela ilustrativa. A autenticação real deve validar a sessão
          no servidor e usar password_hash() / password_verify().
        </p>

      </div>
    </div>
  </div>
</div>

<!-- BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- JAVASCRIPT -->
<script>

  // Produtos demonstrativos
// Produtos carregados do banco de dados pelo PHP
const products = <?php
echo json_encode(
    $products,
    JSON_UNESCAPED_UNICODE |
    JSON_UNESCAPED_SLASHES |
    JSON_HEX_TAG |
    JSON_HEX_APOS |
    JSON_HEX_QUOT |
    JSON_HEX_AMP
);
?>;

  let activeCategory = "todos";

  let cart = JSON.parse(
    localStorage.getItem("techstore_cart") || "[]"
  );

  const money = value => value.toLocaleString("pt-BR", {
    style: "currency",
    currency: "BRL"
  });


 
  function renderProducts() {

    const term = document.getElementById("searchInput")
      .value.trim().toLowerCase();

    const filtered = products.filter(p =>
      (activeCategory === "todos" || p.category === activeCategory) &&
      p.name.toLowerCase().includes(term)
    );

    document.getElementById("productGrid").innerHTML =
      filtered.map(p => `

        <div class="col-6 col-lg-3">

          <article class="product-card">

            <div class="product-image">
              <img src="${p.image}" alt="${p.name}" loading="lazy">
            </div>

            <div class="product-info">

              <div class="product-category mb-2">
                ${p.categoryLabel}
              </div>

              <div class="product-name">
                ${p.name}
              </div>

              <div class="mt-2">
                ${p.old ? `<span class="old-price">${money(p.old)}</span><br>` : ""}
                <span class="price">${money(p.price)}</span>
              </div>

              <div class="stock mt-2">
                ${p.stock > 0 ? `● ${p.stock} unidades em estoque` : "Sem estoque"}
              </div>

              <button class="btn btn-brand w-100 mt-3"
                onclick="addToCart(${p.id})"
                ${p.stock === 0 ? "disabled" : ""}>
                Adicionar ao carrinho
              </button>

            </div>
          </article>

        </div>

      `).join("");

    document.getElementById("noResults")
      .classList.toggle("d-none", filtered.length > 0);
  }


  // SALVAR CARRINHO
  function saveCart() {
    localStorage.setItem("techstore_cart", JSON.stringify(cart));
    renderCart();
  }


  // ADICIONAR PRODUTO
  function addToCart(id) {

    const product = products.find(p => p.id === id);
    const item = cart.find(i => i.id === id);

    if (item) {

      if (item.quantity >= product.stock) {
        alert("Quantidade máxima disponível em estoque.");
        return;
      }

      item.quantity++;

    } else {

      cart.push({
        id: id,
        quantity: 1
      });

    }

    saveCart();
  }


  // ALTERAR QUANTIDADE
  function changeQuantity(id, delta) {

    const item = cart.find(i => i.id === id);
    const product = products.find(p => p.id === id);

    if (!item) return;

    const next = item.quantity + delta;

    if (next < 1) {

      cart = cart.filter(i => i.id !== id);

    } else if (next > product.stock) {

      alert("Quantidade maior que o estoque disponível.");
      return;

    } else {

      item.quantity = next;

    }

    saveCart();
  }


  // REMOVER PRODUTO
  function removeItem(id) {
    cart = cart.filter(i => i.id !== id);
    saveCart();
  }


  // ATUALIZAR CARRINHO
  function renderCart() {

    const count = cart.reduce(
      (sum, i) => sum + i.quantity, 0
    );

    const total = cart.reduce((sum, i) => {
      const product = products.find(p => p.id === i.id);
      return sum + (product ? product.price * i.quantity : 0);
    }, 0);

    document.getElementById("cartCount").textContent = count;
    document.getElementById("cartTotal").textContent = money(total);

    document.getElementById("cartItems").innerHTML = cart.length
      ? cart.map(i => {

          const p = products.find(x => x.id === i.id);

          if (!p) return "";

          return `

            <div class="d-flex gap-3 mb-3 align-items-center">

              <img src="${p.image}" alt=""
                style="width:66px;height:66px;object-fit:contain;background:#f5f6fb;border-radius:12px;padding:5px">

              <div class="flex-grow-1">

                <div class="fw-bold">${p.name}</div>

                <div class="small text-secondary">
                  ${money(p.price)}
                </div>

                <div class="d-flex align-items-center gap-2 mt-1">

                  <button class="btn btn-sm btn-light"
                    onclick="changeQuantity(${p.id}, -1)">−</button>

                  <span>${i.quantity}</span>

                  <button class="btn btn-sm btn-light"
                    onclick="changeQuantity(${p.id}, 1)">+</button>

                  <button class="btn btn-sm btn-link text-danger text-decoration-none"
                    onclick="removeItem(${p.id})">
                    Remover
                  </button>

                </div>
              </div>
            </div>

          `;

        }).join("")

      : '<div class="empty-state">Seu carrinho está vazio.</div>';
  }


  
  document.querySelectorAll("[data-filter]").forEach(button => {

    button.addEventListener("click", () => {

      activeCategory = button.dataset.filter;

      document.querySelectorAll("[data-filter]").forEach(b =>
        b.classList.toggle("active", b === button)
      );

      renderProducts();

    });

  });


  // CATEGORIAS
  document.querySelectorAll("[data-category-link]").forEach(link => {

    link.addEventListener("click", () => {

      activeCategory = link.dataset.categoryLink;

      document.querySelectorAll("[data-filter]").forEach(b =>
        b.classList.toggle("active", b.dataset.filter === activeCategory)
      );

      renderProducts();

    });

  });


  // PESQUISA
  document.getElementById("searchInput")
    .addEventListener("input", renderProducts);


  // CHECKOUT DEMONSTRATIVO
  document.getElementById("checkoutButton")
    .addEventListener("click", () => {

      alert("Próxima etapa: implementar cadastro/login, checkout, pagamento simulado e registro do pedido em PHP/MySQL.");

    });


  // LOGIN DEMONSTRATIVO
  document.getElementById("loginDemo")
    .addEventListener("submit", e => {

      e.preventDefault();

      alert("Esta é apenas a interface. A autenticação precisa ser implementada no servidor PHP.");

    });


  // INICIALIZAÇÃO
  renderProducts();
  renderCart();

</script>

</body>
</html>

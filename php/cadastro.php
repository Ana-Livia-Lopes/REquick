<?php
require_once 'auth.php';
require_once '../config/conexao.php';

if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'Administrador') {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    try {
        if ($action === 'create_empresa') {
            $stmt = $pdo->prepare("INSERT INTO tb_empresa (nome_empresa, cnpj) VALUES (?, ?)");
            $stmt->execute([trim($_POST['nome_empresa']), trim($_POST['cnpj'])]);
            $_SESSION['swal_success'] = "Empresa cadastrada com sucesso!";

        } elseif ($action === 'edit_empresa') {
            $stmt = $pdo->prepare("UPDATE tb_empresa SET nome_empresa = ?, cnpj = ? WHERE id = ?");
            $stmt->execute([trim($_POST['nome_empresa']), trim($_POST['cnpj']), $_POST['id']]);
            $_SESSION['swal_success'] = "Empresa atualizada com sucesso!";

        } elseif ($action === 'delete_empresa') {
            $stmt = $pdo->prepare("DELETE FROM tb_empresa WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $_SESSION['swal_success'] = "Empresa deletada com sucesso!";

        } elseif ($action === 'create_usuario') {
            $nome       = trim($_POST['nome']);
            $email      = trim($_POST['email']);
            $tipo       = $_POST['tipo_usuario'];
            $espec      = trim($_POST['especializacao']);
            $id_empresa = ($tipo === 'Administrador') ? null : ($_POST['id_empresa'] ?? null);
            $senhaCriptografada = password_hash($_POST['senha'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO tb_usuarios (nome, email, tipo_usuario, especializacao, senha, id_empresa) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $email, $tipo, $espec, $senhaCriptografada, $id_empresa]);
            $_SESSION['swal_success'] = "Usuário cadastrado com sucesso!";

        } elseif ($action === 'edit_usuario') {
            $id         = $_POST['id'];
            $nome       = trim($_POST['nome']);
            $email      = trim($_POST['email']);
            $tipo       = $_POST['tipo_usuario'];
            $espec      = trim($_POST['especializacao']);
            $id_empresa = $_POST['id_empresa'];
            if (!empty($_POST['senha'])) {
                $senhaCriptografada = password_hash($_POST['senha'], PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE tb_usuarios SET nome=?, email=?, tipo_usuario=?, especializacao=?, senha=?, id_empresa=? WHERE id=?");
                $stmt->execute([$nome, $email, $tipo, $espec, $senhaCriptografada, $id_empresa, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE tb_usuarios SET nome=?, email=?, tipo_usuario=?, especializacao=?, id_empresa=? WHERE id=?");
                $stmt->execute([$nome, $email, $tipo, $espec, $id_empresa, $id]);
            }
            $_SESSION['swal_success'] = "Usuário atualizado com sucesso!";

        } elseif ($action === 'toggle_status_usuario') {
            $idUsuario   = (int) $_POST['id'];
            $statusAtual = $_POST['status_atual'];
            $novoStatus  = strtolower($statusAtual) === 'ativo' ? 'Inativo' : 'Ativo';
            $stmt = $pdo->prepare("UPDATE tb_usuarios SET status = ? WHERE id = ?");
            $stmt->execute([$novoStatus, $idUsuario]);
            $label = $novoStatus === 'Ativo' ? 'ativado' : 'desativado';
            $_SESSION['swal_success'] = "Usuário $label com sucesso!";

        } elseif ($action === 'delete_usuario') {
            $idUsuario = (int) $_POST['id'];
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("DELETE FROM tb_historico WHERE autor = ?");
            $stmt->execute([$idUsuario]);
            $stmt = $pdo->prepare("DELETE FROM tb_usuarios WHERE id = ?");
            $stmt->execute([$idUsuario]);
            $pdo->commit();
            $_SESSION['swal_success'] = "Usuário deletado com sucesso!";
        }

        header("Location: cadastro.php");
        exit;

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        if (str_contains((string)$e->getCode(), '23000') || (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1451)) {
            $_SESSION['swal_error'] = "Ação bloqueada! Este registro possui informações vinculadas no sistema.";
        } else {
            $_SESSION['swal_error'] = "Erro no banco de dados: " . $e->getMessage();
        }
        header("Location: cadastro.php");
        exit;
    }
}

$empresas = $pdo->query("SELECT * FROM tb_empresa ORDER BY nome_empresa")->fetchAll(PDO::FETCH_ASSOC);
$usuarios = $pdo->query("SELECT u.*, e.nome_empresa FROM tb_usuarios u LEFT JOIN tb_empresa e ON u.id_empresa = e.id ORDER BY u.nome")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Requick – Administração</title>
    <link rel="icon" type="image/x-icon" href="../img/icone-REquick.ico">
    <link rel="stylesheet" href="../css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/cadastro.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .BadgeStatus {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .3px;
        }
        .BadgeAtivo   { background: #dcfce7; color: #16a34a; }
        .BadgeInativo { background: #e5e7eb; color: #6b7280; }
        .BtnToggle {
            border: none;
            cursor: pointer;
            padding: 6px 8px;
            border-radius: 6px;
            font-size: 16px;
            transition: background .2s;
        }
        .BtnToggleAtivo   { background: #22c55e; color: #fff; }
        .BtnToggleAtivo:hover { background: #16a34a; }
        .BtnToggleInativo { background: #9ca3af; color: #fff; }
        .BtnToggleInativo:hover { background: #6b7280; }
    </style>
</head>
<body>

    <?php $paginaAtiva = 'cadastro'; include 'navbar_lateral.php'; ?>

    <div class="ConteudoPrincipal">
        <header class="CabecalhoPagina">
            <h1 class="TituloBoasVindas">Administração do Sistema</h1>
        </header>

        <div class="GradeCadastro">

            <div class="ColunaEsquerda">
                <h2 class="TituloSecao">Cadastrar Nova Empresa</h2>
                <form action="" method="post">
                    <input type="hidden" name="action" value="create_empresa">
                    <div class="GrupoFormulario">
                        <label class="LabelFormulario">Nome da Empresa</label>
                        <input type="text" name="nome_empresa" class="CampoFormulario" required placeholder="Ex: Tech Solutions">
                    </div>
                    <div class="GrupoFormulario">
                        <label class="LabelFormulario">CNPJ</label>
                        <input type="text" name="cnpj" class="CampoFormulario" required placeholder="00.000.000/0000-00">
                    </div>
                    <button type="submit" class="BotaoCriar">Salvar Empresa</button>
                </form>

                <div class="CabecalhoTabela">
                    <h3>Empresas Cadastradas</h3>
                    <input type="text" id="buscaEmpresa" class="CampoPesquisaTabela"
                           onkeyup="filtrarTabela('buscaEmpresa', 'tbEmpresas', 0)"
                           placeholder="Pesquisar empresa...">
                </div>
                <table class="TabelaCrud" id="tbEmpresas">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CNPJ</th>
                            <th width="110px">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($empresas as $emp): ?>
                            <tr>
                                <td><?= htmlspecialchars($emp['nome_empresa']) ?></td>
                                <td><?= htmlspecialchars($emp['cnpj']) ?></td>
                                <td>
                                    <button onclick="abrirModalEmpresa(<?= $emp['id'] ?>, '<?= htmlspecialchars(addslashes($emp['nome_empresa'])) ?>', '<?= htmlspecialchars(addslashes($emp['cnpj'])) ?>')"
                                            class="BtnAcao BtnEdit" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="confirmarDelecao(<?= $emp['id'] ?>, 'empresa')"
                                            class="BtnAcao BtnDelete" title="Deletar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="ColunaDireita">
                <h2 class="TituloSecao">Cadastrar Novo Usuário</h2>
                <form action="" method="post">
                    <input type="hidden" name="action" value="create_usuario">
                    <div class="GrupoFormulario">
                        <label class="LabelFormulario">Nome</label>
                        <input type="text" name="nome" class="CampoFormulario" required placeholder="Nome completo">
                    </div>
                    <div class="GrupoFormulario">
                        <label class="LabelFormulario">E-mail</label>
                        <input type="email" name="email" class="CampoFormulario" required placeholder="email@exemplo.com">
                    </div>
                    <div class="GrupoFormulario">
                        <label class="LabelFormulario">Senha</label>
                        <input type="password" name="senha" class="CampoFormulario" required placeholder="Digite a senha">
                    </div>
                    <div class="GrupoFormulario">
                        <label class="LabelFormulario">Especialização</label>
                        <input type="text" name="especializacao" class="CampoFormulario" placeholder="Ex: Desenvolvedor, RH, Analista...">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="GrupoFormulario">
                            <label class="LabelFormulario">Tipo de Usuário</label>
                            <select name="tipo_usuario" id="select_tipo_create" class="CampoFormulario" required>
                                <option value="" disabled selected>Selecione...</option>
                                <option value="Cliente">Cliente</option>
                                <option value="Funcionario">Desenvolvedor</option>
                                <option value="Administrador">Administrador</option>
                            </select>
                        </div>
                        <div class="GrupoFormulario" id="wrap_empresa_create">
                            <label class="LabelFormulario">Empresa</label>
                            <select name="id_empresa" id="select_empresa_create" class="CampoFormulario">
                                <option value="" disabled selected>Selecione...</option>
                                <?php foreach ($empresas as $emp): ?>
                                    <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['nome_empresa']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="BotaoCriar">Salvar Usuário</button>
                </form>

                <div class="CabecalhoTabela">
                    <h3>Usuários Cadastrados</h3>
                    <input type="text" id="buscaUsuario" class="CampoPesquisaTabela"
                           onkeyup="filtrarTabela('buscaUsuario', 'tbUsuarios', 0)"
                           placeholder="Pesquisar usuário...">
                </div>
                <table class="TabelaCrud" id="tbUsuarios">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Empresa</th>
                            <th>Status</th>
                            <th width="150px">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usu):
                            $statusAtual = (array_key_exists('status', $usu) && strtolower($usu['status']) === 'inativo') ? 'inativo' : 'ativo';
                            $isAtivo     = ($statusAtual === 'ativo');
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($usu['nome']) ?></td>
                                <td><?= htmlspecialchars($usu['tipo_usuario']) ?></td>
                                <td><?= htmlspecialchars($usu['nome_empresa'] ?? 'Sem Empresa') ?></td>
                                <td>
                                    <span class="BadgeStatus <?= $isAtivo ? 'BadgeAtivo' : 'BadgeInativo' ?>">
                                        <?= $isAtivo ? 'Ativo' : 'Inativo' ?>
                                    </span>
                                </td>
                                <td>
                                    <button onclick="toggleStatus(<?= $usu['id'] ?>, '<?= $statusAtual ?>')"
                                            class="BtnToggle <?= $isAtivo ? 'BtnToggleAtivo' : 'BtnToggleInativo' ?>"
                                            title="<?= $isAtivo ? 'Clique para desativar' : 'Clique para ativar' ?>">
                                        <i class="fas <?= $isAtivo ? 'fa-toggle-on' : 'fa-toggle-off' ?>"></i>
                                    </button>
                                    <button onclick="abrirModalUsuario(<?= $usu['id'] ?>, '<?= htmlspecialchars(addslashes($usu['nome'])) ?>', '<?= htmlspecialchars(addslashes($usu['email'])) ?>', '<?= htmlspecialchars(addslashes($usu['tipo_usuario'])) ?>', '<?= htmlspecialchars(addslashes($usu['especializacao'] ?? '')) ?>', <?= (int)($usu['id_empresa'] ?? 0) ?>)"
                                            class="BtnAcao BtnEdit" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="confirmarDelecao(<?= $usu['id'] ?>, 'usuario')"
                                            class="BtnAcao BtnDelete" title="Deletar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <form id="formDelete" method="POST" style="display:none;">
        <input type="hidden" name="action" id="deleteAction">
        <input type="hidden" name="id"     id="deleteId">
    </form>

    <form id="formToggleStatus" method="POST" style="display:none;">
        <input type="hidden" name="action"       value="toggle_status_usuario">
        <input type="hidden" name="id"           id="toggleId">
        <input type="hidden" name="status_atual" id="toggleStatusAtual">
    </form>

    <div id="modalEditEmpresa" class="ModalCustomOverlay">
        <div class="ModalCustomBox">
            <div class="ModalCustomHeader">
                <h2>Editar Empresa</h2>
                <i class="fas fa-times CloseModal" onclick="fecharModal('modalEditEmpresa')"></i>
            </div>
            <form action="" method="post" onsubmit="confirmarEdicao(event, this)">
                <input type="hidden" name="action" value="edit_empresa">
                <input type="hidden" name="id"     id="edit_emp_id">
                <div class="GrupoFormulario">
                    <label>Nome da Empresa</label>
                    <input type="text" name="nome_empresa" id="edit_emp_nome" class="CampoFormulario" required>
                </div>
                <div class="GrupoFormulario">
                    <label>CNPJ</label>
                    <input type="text" name="cnpj" id="edit_emp_cnpj" class="CampoFormulario" required>
                </div>
                <button type="submit" class="BotaoCriar">Salvar Alterações</button>
            </form>
        </div>
    </div>

    <div id="modalEditUsuario" class="ModalCustomOverlay">
        <div class="ModalCustomBox">
            <div class="ModalCustomHeader">
                <h2>Editar Usuário</h2>
                <i class="fas fa-times CloseModal" onclick="fecharModal('modalEditUsuario')"></i>
            </div>
            <form action="" method="post" onsubmit="confirmarEdicao(event, this)">
                <input type="hidden" name="action" value="edit_usuario">
                <input type="hidden" name="id"     id="edit_usu_id">
                <div class="GrupoFormulario">
                    <label>Nome</label>
                    <input type="text" name="nome" id="edit_usu_nome" class="CampoFormulario" required>
                </div>
                <div class="GrupoFormulario">
                    <label>E-mail</label>
                    <input type="email" name="email" id="edit_usu_email" class="CampoFormulario" required>
                </div>
                <div class="GrupoFormulario">
                    <label>Nova Senha <small>(Deixe em branco para não alterar)</small></label>
                    <input type="password" name="senha" class="CampoFormulario" placeholder="Apenas se quiser trocar a senha">
                </div>
                <div class="GrupoFormulario">
                    <label>Especialização</label>
                    <input type="text" name="especializacao" id="edit_usu_espec" class="CampoFormulario">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="GrupoFormulario">
                        <label>Tipo</label>
                        <select name="tipo_usuario" id="edit_usu_tipo" class="CampoFormulario" required>
                            <option value="Cliente">Cliente</option>
                            <option value="Funcionario">Funcionário</option>
                            <option value="Administrador">Administrador</option>
                        </select>
                    </div>
                    <div class="GrupoFormulario">
                        <label>Empresa</label>
                        <select name="id_empresa" id="edit_usu_empresa" class="CampoFormulario" required>
                            <option value="" disabled>Selecione...</option>
                            <?php foreach ($empresas as $emp): ?>
                                <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['nome_empresa']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="BotaoCriar">Salvar Alterações</button>
            </form>
        </div>
    </div>

    <script>
        // Oculta/exibe campo empresa conforme tipo selecionado no cadastro
        (function () {
            const selectTipo    = document.getElementById('select_tipo_create');
            const wrapEmpresa   = document.getElementById('wrap_empresa_create');
            const selectEmpresa = document.getElementById('select_empresa_create');

            function atualizarEmpresa() {
                const isAdmin = selectTipo.value === 'Administrador';
                wrapEmpresa.style.display = isAdmin ? 'none' : '';
                selectEmpresa.required    = !isAdmin;
                if (isAdmin) selectEmpresa.value = '';
            }

            selectTipo.addEventListener('change', atualizarEmpresa);
            atualizarEmpresa();
        })();

        function filtrarTabela(inputId, tableId, colunaIndex) {
            let filter = document.getElementById(inputId).value.toUpperCase();
            let trs    = document.getElementById(tableId).getElementsByTagName("tr");
            for (let i = 1; i < trs.length; i++) {
                let td = trs[i].getElementsByTagName("td")[colunaIndex];
                if (td) {
                    let txtValue = td.textContent || td.innerText;
                    trs[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
                }
            }
        }

        function confirmarDelecao(id, tipo) {
            Swal.fire({
                title: 'Você tem certeza?',
                text: "Isso não poderá ser desfeito!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#888',
                confirmButtonText: 'Sim, deletar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteAction').value = 'delete_' + tipo;
                    document.getElementById('deleteId').value     = id;
                    document.getElementById('formDelete').submit();
                }
            });
        }

        function confirmarEdicao(event, form) {
            event.preventDefault();
            Swal.fire({
                title: 'Salvar alterações?',
                text: "As informações serão atualizadas no sistema.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#888',
                confirmButtonText: 'Sim, salvar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        function toggleStatus(id, statusAtual) {
            const acao  = statusAtual === 'ativo' ? 'desativar' : 'ativar';
            const icone = statusAtual === 'ativo' ? 'warning'   : 'question';
            const cor   = statusAtual === 'ativo' ? '#9ca3af'   : '#22c55e';
            const texto = statusAtual === 'ativo'
                ? 'O usuário não conseguirá mais fazer login.'
                : 'O usuário poderá voltar a acessar o sistema.';

            Swal.fire({
                title: `Deseja ${acao} este usuário?`,
                text: texto,
                icon: icone,
                showCancelButton: true,
                confirmButtonColor: cor,
                cancelButtonColor: '#888',
                confirmButtonText: `Sim, ${acao}!`,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('toggleId').value          = id;
                    document.getElementById('toggleStatusAtual').value = statusAtual;
                    document.getElementById('formToggleStatus').submit();
                }
            });
        }

        function fecharModal(idModal) {
            document.getElementById(idModal).style.display = 'none';
        }

        function abrirModalEmpresa(id, nome, cnpj) {
            document.getElementById('edit_emp_id').value   = id;
            document.getElementById('edit_emp_nome').value = nome;
            document.getElementById('edit_emp_cnpj').value = cnpj;
            document.getElementById('modalEditEmpresa').style.display = 'flex';
        }

        function abrirModalUsuario(id, nome, email, tipo, espec, id_emp) {
            document.getElementById('edit_usu_id').value      = id;
            document.getElementById('edit_usu_nome').value    = nome;
            document.getElementById('edit_usu_email').value   = email;
            document.getElementById('edit_usu_tipo').value    = tipo;
            document.getElementById('edit_usu_espec').value   = espec;
            document.getElementById('edit_usu_empresa').value = id_emp;
            document.getElementById('modalEditUsuario').style.display = 'flex';
        }
    </script>

    <?php if (isset($_SESSION['swal_success'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Tudo certo!',
                text: '<?= addslashes($_SESSION['swal_success']) ?>',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
        <?php unset($_SESSION['swal_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['swal_error'])): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?= addslashes($_SESSION['swal_error']) ?>'
            });
        </script>
        <?php unset($_SESSION['swal_error']); ?>
    <?php endif; ?>

</body>
</html>
<?php
require_once 'auth.php';
$pdo = require_once '../config/conexao.php';

$idUsuario      = $_SESSION['usuario_id']       ?? null;
$nomeUsuario    = $_SESSION['usuario_nome']      ?? 'Usuário';
$tipoUsuario    = $_SESSION['usuario_tipo']      ?? 'Visitante';
$emailUsuario   = $_SESSION['usuario_email']     ?? 'Email';

/* busca especialização atualizada do banco */
$especializacao = '';
if ($idUsuario) {
    $stmtEsp = $pdo->prepare("SELECT especializacao FROM tb_usuarios WHERE id = :id");
    $stmtEsp->execute([':id' => $idUsuario]);
    $row = $stmtEsp->fetch();
    $especializacao = $row['especializacao'] ?? '';
}

$isAdmin = (strtolower($tipoUsuario) === 'administrador');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Requick - Perfil</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/perfil.css" />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <?php $paginaAtiva = 'perfil'; include 'navbar_lateral.php'; ?>

    <div class="ConteudoPrincipal">
        <header class="CabecalhoPagina">
            <h1 class="TituloBoasVindas">Meu Perfil</h1>
        </header>

        <section class="CardPerfil">
            <div class="CabecalhoPerfil">
                <div class="AvatarPerfilGrande"><?= strtoupper(substr($nomeUsuario, 0, 1)) ?></div>
                <div class="InfoPerfilGrande">
                    <h2><?= htmlspecialchars($nomeUsuario) ?></h2>
                    <p><?= htmlspecialchars($tipoUsuario) ?></p>
                    <span><i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($emailUsuario) ?></span>
                </div>
                <button class="BotaoCadastrar" id="btnAbrirModal">Editar perfil</button>
            </div>

            <div class="BlocoDadosPerfil">
                <h3>Dados da conta</h3>
                <ul>
                    <li><span>Nome completo</span><strong id="exibirNome"><?= htmlspecialchars($nomeUsuario) ?></strong></li>
                    <li><span>E-mail</span><strong id="exibirEmail"><?= htmlspecialchars($emailUsuario) ?></strong></li>
                    <li><span>Cargo</span><strong><?= htmlspecialchars($tipoUsuario) ?></strong></li>
                    <li><span>Equipe</span><strong>Núcleo Requick</strong></li>
                    <li><span>Conta criada em</span><strong>10/04/2026</strong></li>
                </ul>
            </div>

            <?php if ($isAdmin): ?>
            <section class="CardConfig">
                <h3>Segurança</h3>
                <div id="feedbackSenha" class="MensagemFeedback"></div>
                <form id="formSenha" class="FormConfig">
                    <label>Senha atual
                        <input type="password" name="senha_atual" placeholder="••••••••" required>
                    </label>
                    <label>Nova senha
                        <input type="password" name="nova_senha" placeholder="••••••••" required>
                    </label>
                    <label>Confirmar nova senha
                        <input type="password" name="confirmar_senha" placeholder="••••••••" required>
                    </label>
                    <button type="submit" class="BotaoSalvarConfig">Salvar alterações</button>
                </form>
            </section>
            <?php endif; ?>
        </section>
    </div>

    <div class="ModalOverlay" id="modalEditarPerfil" role="dialog" aria-modal="true" aria-labelledby="tituloModal">
        <div class="ModalBox">
            <button class="ModalFechar" id="btnFecharModal" aria-label="Fechar modal">&times;</button>
            <h2 id="tituloModal">Editar perfil</h2>

            <div id="feedbackModal" class="MensagemFeedback"></div>

            <form id="formEditarPerfil" class="FormModal" novalidate>
                <label>Nome completo
                    <input type="text" name="nome" id="inputNome"
                           value="<?= htmlspecialchars($nomeUsuario) ?>" required>
                </label>
                <label>E-mail
                    <input type="email" name="email" id="inputEmail"
                           value="<?= htmlspecialchars($emailUsuario) ?>">
                    <span class="AvisoLogout" id="avisoLogout">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Alterar o e-mail encerrará sua sessão.
                    </span>
                </label>
                <label>Especialização
                    <input type="text" name="especializacao" id="inputEspecializacao"
                           value="<?= htmlspecialchars($especializacao) ?>">
                </label>
                <button type="submit" class="BotaoSalvarModal">Salvar alterações</button>
            </form>
        </div>
    </div>

    <script>
    (function () {
        const overlay       = document.getElementById('modalEditarPerfil');
        const btnAbrir      = document.getElementById('btnAbrirModal');
        const btnFechar     = document.getElementById('btnFecharModal');
        const formModal     = document.getElementById('formEditarPerfil');
        const feedbackModal = document.getElementById('feedbackModal');
        const inputEmail    = document.getElementById('inputEmail');
        const avisoLogout   = document.getElementById('avisoLogout');
        const emailOriginal = inputEmail ? inputEmail.value : '';

        function abrirModal() {
            overlay.classList.add('aberto');
            document.getElementById('inputNome').focus();
        }

        function fecharModal() {
            overlay.classList.remove('aberto');
            feedbackModal.className = 'MensagemFeedback';
            feedbackModal.textContent = '';
        }

        btnAbrir.addEventListener('click', abrirModal);
        btnFechar.addEventListener('click', fecharModal);
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) fecharModal();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') fecharModal();
        });

        /* aviso de logout ao alterar e-mail */
        if (inputEmail) {
            inputEmail.addEventListener('input', function () {
                if (inputEmail.value.trim() !== emailOriginal) {
                    avisoLogout.classList.add('visivel');
                } else {
                    avisoLogout.classList.remove('visivel');
                }
            });
        }

        if (formModal) {
            formModal.addEventListener('submit', function (e) {
                e.preventDefault();
                const dados = new FormData(formModal);

                fetch('atualizar_perfil.php', { method: 'POST', body: dados })
                    .then(r => r.json())
                    .then(function (res) {
                        feedbackModal.className = 'MensagemFeedback ' + (res.sucesso ? 'sucesso' : 'erro');
                        feedbackModal.textContent = res.mensagem;

                        if (res.sucesso) {
                            const novoNome  = dados.get('nome');
                            const novoEmail = dados.get('email');
                            document.getElementById('exibirNome').textContent  = novoNome;
                            document.getElementById('exibirEmail').textContent = novoEmail;

                            if (res.emailAlterado) {

                                setTimeout(function () { window.location.href = 'logout.php'; }, 1500);
                            } else {
                                setTimeout(fecharModal, 1500);
                            }
                        }
                    })
                    .catch(function () {
                        feedbackModal.className = 'MensagemFeedback erro';
                        feedbackModal.textContent = 'Erro de comunicação. Tente novamente.';
                    });
            });
        }

        const formSenha     = document.getElementById('formSenha');
        const feedbackSenha = document.getElementById('feedbackSenha');

        if (formSenha) {
            formSenha.addEventListener('submit', function (e) {
                e.preventDefault();
                const dados = new FormData(formSenha);

                fetch('alterar_senha.php', { method: 'POST', body: dados })
                    .then(r => r.json())
                    .then(function (res) {
                        feedbackSenha.className = 'MensagemFeedback ' + (res.sucesso ? 'sucesso' : 'erro');
                        feedbackSenha.textContent = res.mensagem;

                        if (res.sucesso) {
                            formSenha.reset();
                            setTimeout(function () { window.location.href = 'logout.php'; }, 2000);
                        }
                    })
                    .catch(function () {
                        feedbackSenha.className = 'MensagemFeedback erro';
                        feedbackSenha.textContent = 'Erro de comunicação. Tente novamente.';
                    });
            });
        }
    })();
    </script>
</body>
</html>
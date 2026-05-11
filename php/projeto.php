<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Requick – E-commerce Alpha</title>
    <link rel="stylesheet" href="../css/projeto.css" />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <?php $paginaAtiva = 'projetos'; include 'navbar_lateral.php'; ?>

    <div class="AreaRolavel">
        <main class="ConteudoPrincipal">
            <header class="CabecalhoPagina">
                <a href="projetos.php" class="LinkVoltar">
                    <i class="fa-solid fa-arrow-left"></i>
                    Voltar para projetos
                </a>

                <div class="AcoesCabecalho">
                    <a href="exportar_projeto.php" class="btn-header btn-dark">
                        Exportar Projeto
                        <i class="fa-solid fa-file-export"></i>
                    </a>
                    <a href="novo_escopo.php" class="btn-header btn-dark">
                        Visualizar escopo inicial
                        <i class="fa-solid fa-file-lines"></i>
                    </a>
                    <label for="CheckboxConvidar" class="btn-header btn-light">
                        Convidar Usuários
                        <i class="fa-solid fa-user-plus"></i>
                    </label>
                </div>
            </header>

            <h1 class="TituloProjeto">E-commerce Alpha</h1>

            <section class="bloco-comentarios">
                <div class="titulo-sessao">
                    Comentários e/ou solicitações de mudanças
                    <span class="badge-contador">+3</span>
                </div>

                <div class="linha-comentario">
                    <div class="avatar-comentario">AN</div>
                    <div class="conteudo-comentario">
                        <p><strong>Solicitação de mudança:</strong> Preciso que revise o requisito RF02 e altere para o que foi pedido no escopo inicial</p>
                        <span>André - Cliente</span>
                    </div>
                </div>

                <div class="linha-comentario">
                    <div class="avatar-comentario">JO</div>
                    <div class="conteudo-comentario">
                        <p><strong>Comentário:</strong> É necessário a finalização do requisito RF01, ler o escopo inicial e fazer a alteração do que foi pedido</p>
                        <span>João - Responsável Projeto</span>
                    </div>
                </div>

                <div class="linha-comentario">
                    <div class="avatar-comentario">JO</div>
                    <div class="conteudo-comentario">
                        <p><strong>Comentário:</strong> É necessário a finalização do requisito RF01, ler o escopo inicial e fazer a alteração do que foi pedido</p>
                        <span>João - Responsável Projeto</span>
                    </div>
                </div>

                <div class="seta-container">
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
            </section>

            <div id="busca-e-add-requisito">
                <div class="ContainerBusca">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" class="CampoBusca" placeholder="Buscar requisitos, tags ou responsáveis nesse projeto" />
                </div>
                <a href="adicionar_requisito.php" class="BotaoCadastrar">
                    Adicionar requisito <i class="fa-solid fa-plus"></i>
                </a>
            </div>

            <section class="container-requisitos">
                <div class="tabela-header">
                    <div class="col-titulo">Título</div>
                    <div class="col-status">Status</div>
                    <div class="col-prioridade">Prioridade</div>
                    <div class="col-responsavel">Responsável</div>
                    <div class="col-acoes">Ações</div>
                </div>

                <div class="tabela-linha">
                    <div class="col-titulo">
                        <div class="icone-req"><i class="fa-solid fa-file-lines"></i></div>
                        <div>
                            <strong>RF01 - Disponibilidade</strong>
                            <span>última vez alterado(a) por Victor Koba</span>
                        </div>
                    </div>
                    <div class="col-status"><span class="badge yellow">Em Andamento</span></div>
                    <div class="col-prioridade">Média</div>
                    <div class="col-responsavel">Victor Koba</div>
                    <div class="col-acoes">
                        <a href="excluir_requisito.php?id=1" class="btn-excluir"><i class="fa-regular fa-trash-can"></i> Excluir</a>
                        <a href="editar_requisito.php?id=1" class="btn-editar"><i class="fa-regular fa-pen-to-square"></i> Editar</a>
                    </div>
                </div>

                <div class="tabela-linha">
                    <div class="col-titulo">
                        <div class="icone-req"><i class="fa-solid fa-file-lines"></i></div>
                        <div>
                            <strong>RF02 - Gestão de usuários</strong>
                            <span>última vez alterado(a) por Victor Koba</span>
                        </div>
                    </div>
                    <div class="col-status"><span class="badge green">Validado</span></div>
                    <div class="col-prioridade">--</div>
                    <div class="col-responsavel">Jacquys</div>
                    <div class="col-acoes">
                        <a href="excluir_requisito.php?id=2" class="btn-excluir"><i class="fa-regular fa-trash-can"></i> Excluir</a>
                        <a href="editar_requisito.php?id=2" class="btn-editar"><i class="fa-regular fa-pen-to-square"></i> Editar</a>
                    </div>
                </div>

                <div class="tabela-linha">
                    <div class="col-titulo">
                        <div class="icone-req"><i class="fa-solid fa-file-lines"></i></div>
                        <div>
                            <strong>RF03 - Edição de perfil</strong>
                            <span>última vez alterado(a) por Ana Lívia</span>
                        </div>
                    </div>
                    <div class="col-status"><span class="badge green">Validado</span></div>
                    <div class="col-prioridade">--</div>
                    <div class="col-responsavel">João Pedro</div>
                    <div class="col-acoes">
                        <a href="excluir_requisito.php?id=3" class="btn-excluir"><i class="fa-regular fa-trash-can"></i> Excluir</a>
                        <a href="editar_requisito.php?id=3" class="btn-editar"><i class="fa-regular fa-pen-to-square"></i> Editar</a>
                    </div>
                </div>

                <div class="tabela-footer">
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
            </section>
        </main>

        <aside class="ColunaDireita">
            <div class="card-info-projeto">
                <h3>Informações do projeto</h3>

                <div class="bloco-info">
                    <h4>Progresso:</h4>
                    <div class="barra"><div class="progresso" style="width:52%"></div></div>
                    <p class="percentual">52% concluído</p>
                </div>

                <div class="bloco-info linha-info">
                    <span class="rotulo">Data de início:</span>
                    <span class="valor">10/04/2026</span>
                </div>
                <div class="bloco-info linha-info">
                    <span class="rotulo">Data de entrega:</span>
                    <span class="valor">30/06/2026</span>
                </div>

                <div class="bloco-info">
                    <span class="rotulo">Equipe:</span>
                    <p class="valor-bloco">6 membros</p>
                </div>
            </div>

            <div class="FeedAtividades">
                <h2 class="TituloFeed">Feed de Atividades</h2>
                <ul class="ListaAtividades">
                    <li class="ItemAtividade">
                        <div class="AvatarAtividade">VK</div>
                        <div class="ConteudoAtividade">
                            <div class="LinhaAtividade">
                                <p class="NomeAtividade">Victor Koba</p>
                                <p class="TempoAtividade">2 min atrás</p>
                            </div>
                            <p class="TextoAtividade">editou Requisito RF01 no E-commerce Alpha</p>
                        </div>
                    </li>
                    <li class="ItemAtividade">
                        <div class="AvatarAtividade">JP</div>
                        <div class="ConteudoAtividade">
                            <div class="LinhaAtividade">
                                <p class="NomeAtividade">João Pedro</p>
                                <p class="TempoAtividade">3 horas atrás</p>
                            </div>
                            <p class="TextoAtividade">editou Requisito RF02 no Projeto Beta</p>
                        </div>
                    </li>
                    <li class="ItemAtividade">
                        <div class="AvatarAtividade">JA</div>
                        <div class="ConteudoAtividade">
                            <div class="LinhaAtividade">
                                <p class="NomeAtividade">Jacquys</p>
                                <p class="TempoAtividade">5 horas atrás</p>
                            </div>
                            <p class="TextoAtividade">editou Requisito RNF15 no Projeto Abençoado</p>
                        </div>
                    </li>
                </ul>
            </div>
        </aside>
    </div>
    <input type="checkbox" id="CheckboxConvidar" class="CheckboxModal" />

<div class="FundoModal">
    <label for="CheckboxConvidar" class="SombreaModal"></label>

    <div class="ContainerModal">
        <label for="CheckboxConvidar" class="BotaoVoltar">
            <i class="fa-solid fa-angle-left"></i> Voltar
        </label>

        <div class="LogoModal">
            <img src="../img/logo-requick.png" alt="Requick" class="ImagemLogoModal">
        </div>

        <h2 class="TituloModal">Convidar Pessoas</h2>

        <div class="GrupoFormulario">
            <label class="LabelFormulario">E-mail do usuário</label>
            <input
                type="email"
                class="CampoFormulario"
                placeholder="Digite o e-mail"
            >
        </div>

        <div class="GrupoFormulario">
            <label class="LabelFormulario">Função no projeto</label>
            <select class="CampoFormulario">
                <option>Responsável</option>
                <option>Cliente</option>
                <option>Desenvolvedor</option>
                <option>Analista</option>
                <option>Visualizador</option>
            </select>
        </div>

        <div class="GrupoFormulario">
            <label class="LabelFormulario">Mensagem (opcional)</label>
            <textarea
                class="CampoFormulario CampoTextarea"
                placeholder="Digite uma mensagem..."
            ></textarea>
        </div>

        <div class="CentralizarDiv2">
            <a href="./projeto.php"><button class="BotaoCriar">
                Convidar
            </button></a>
        </div>
    </div>
</div>
</body>
</html>

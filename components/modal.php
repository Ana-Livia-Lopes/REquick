<?php
function renderModal($id, $titulo, $conteudo) {
?>
    <div class="modal-overlay" id="<?= $id ?>">
        <div class="modal-box">

            <div class="modal-header">
                <h2><?= $titulo ?></h2>

                <button class="close-btn"
                        onclick="fecharModal('<?= $id ?>')">
                    ×
                </button>
            </div>

            <div class="modal-body">
                <?= $conteudo ?>
            </div>

        </div>
    </div>
<?php
}
?>
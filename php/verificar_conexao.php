<?php
require_once 'conexao.php';

echo "<h2>Status da Conexão</h2>";
echo "<p style='color: green;'>Conexão estabelecida com sucesso ao banco <strong>$banco</strong>!</p>";

echo "<ul>";
echo "<li>Servidor: " . $conn->host_info . "</li>";
echo "<li>Protocolo: " . $conn->protocol_version . "</li>";
echo "</ul>";
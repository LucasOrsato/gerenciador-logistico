<?php
require_once "config/conexao.php";
$data=$_GET["data"]??date("Y-m-d");
$st=$conn->prepare("SELECT f.id,f.data_frete,t.nome transportador,o.identificacao origem,d.identificacao destino,(SELECT GROUP_CONCAT(CONCAT(p.nome,' (',fp.quantidade,')') SEPARATOR '; ') FROM frete_produtos fp JOIN produtos p ON p.id=fp.produto_id WHERE fp.frete_id=f.id) produtos FROM fretes f JOIN transportadores t ON t.id=f.transportador_id JOIN enderecos o ON o.id=f.origem_id JOIN enderecos d ON d.id=f.destino_id WHERE f.data_frete=? ORDER BY f.id");
$st->bind_param("s",$data);$st->execute();$resultado=$st->get_result();
header("Content-Type: text/csv; charset=utf-8");header("Content-Disposition: attachment; filename=relatorio_fretes_".$data.".csv");
echo "\xEF\xBB\xBF";$saida=fopen("php://output","w");fputcsv($saida,["Número","Data","Transportador","Origem","Destino","Produtos"],";");
while($linha=$resultado->fetch_assoc()){fputcsv($saida,$linha,";");}fclose($saida);
?>
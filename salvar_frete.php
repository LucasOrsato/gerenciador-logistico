<?php
session_start();require_once "config/conexao.php";
$transportador=(int)($_POST["transportador_id"]??0);$origem=(int)($_POST["origem_id"]??0);$destino=(int)($_POST["destino_id"]??0);$data=$_POST["data_frete"]??"";$ids=$_POST["produto_id"]??[];$quantidades=$_POST["quantidade"]??[];
if(!$transportador||!$origem||!$destino||$origem===$destino||!$data||count($ids)==0){$_SESSION["mensagem"]="Confira os dados do frete. Origem e destino devem ser diferentes.";header("Location: frete_novo.php");exit;}
$conn->begin_transaction();
try{
$st=$conn->prepare("INSERT INTO fretes(data_frete,transportador_id,origem_id,destino_id) VALUES(?,?,?,?)");$st->bind_param("siii",$data,$transportador,$origem,$destino);$st->execute();$freteId=$conn->insert_id;
$item=$conn->prepare("INSERT INTO frete_produtos(frete_id,produto_id,quantidade) VALUES(?,?,?)");
foreach($ids as $i=>$produto){$produto=(int)$produto;$q=(int)($quantidades[$i]??0);if($produto<=0||$q<=0)throw new Exception("Produto ou quantidade inválida.");$item->bind_param("iii",$freteId,$produto,$q);$item->execute();}
$conn->commit();$_SESSION["mensagem"]="Frete #".$freteId." gerado com sucesso.";
}catch(Exception $e){$conn->rollback();$_SESSION["mensagem"]="Não foi possível gerar o frete: ".$e->getMessage();}
header("Location: fretes.php");exit;
?>
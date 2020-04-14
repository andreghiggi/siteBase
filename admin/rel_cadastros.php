<?php 
include('topo.inc.php'); 
include('menu.inc.php'); 
?>
<script>
function valida()
{
    document.form.cmd.value = "filtrar";
}
</script>
<section id="content">
<div class="g12">
    <h1>Cadastros</h1>
    <p></p>

    <?=$msg?>
    
    <!-- area do form -->
    <form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
        <fieldset>            
            <section>
                <label for="date">Data de cadastro:<br><span>Data de início e término dos registros</span></label>
                <div><input id="data_inicio" name="data_inicio" type="text" class="date" value="<?=($_POST['data_inicio'] == TRUE) ? ConverteData($_POST['data_inicio']) : "" ?>"><span> Ex: dd/mm/aaaa</span></div>
                <div><input id="data_termino" name="data_termino" type="text" class="date" value="<?=($_POST['data_termino'] == TRUE) ? ConverteData($_POST['data_termino']) : "" ?>"><span> Ex: dd/mm/aaaa</span></div>
            </section>
            <section>
                <div><button class="i_tick icon" onClick="javascript: valida();" >Buscar</button></div>
            </section>
        </fieldset>
    </form>
    
    <?
    if($_POST['cmd'] == 'filtrar' || $idevento > 0)
    {
        $strWhere = ""; 

        $data_inicio = anti_injection($_POST['data_inicio']);
        $data_termino = anti_injection($_POST['data_termino']);

        if($_POST['data_inicio'] == TRUE)
                $strWhere .= " AND data_cadastro >= '$data_inicio'";

        if($_POST['data_termino'] == TRUE)
                $strWhere .= " AND data_cadastro <= '$data_termino'";

        $str = "SELECT DISTINCT *, CASE WHEN sobrenome IS NOT NULL THEN CONCAT(nome,' ',sobrenome) ELSE nome END AS str_nome 
                FROM cadastros
                WHERE 1 = 1 
                $strWhere 
                ORDER BY data_cadastro ASC";
        $rs  = mysql_query($str) or die(mysql_error());
        $num = mysql_num_rows($rs);

        if($num > 0)
        {
    ?>
    <h1>Retorno da busca</h1>
    <p><b><?=$num?> registros encontrados</b></p>
    
    <fieldset>
    <table >
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone(s)</th>
                <th>Data de cadastro</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?
            while($vet = mysql_fetch_array($rs))
            {
                //Montando string com telefones
                $telefone = $vet['telefone'];

                if($vet['telefone_02'])
                    $telefone .= '<br>'.$vet['telefone'];
        ?>
            <tr>
                <td><?=stripslashes($vet['str_nome'])?></td>
                <td><?=$vet['email']?></td>
                <td><?=$telefone?></td>
                <td><?=ConverteData($vet['data_cadastro'])?></td>
                <td>&nbsp;</td>
            </tr>
            <?
            }
            ?>
        </tbody>
    </table>
    </fieldset>
    
    <?
        }
        else
        {
            echo 'Nenhum registro encontrado';
        }
    }
    ?>
</div>
</section>
<?php include('rodape.inc.php'); ?>  
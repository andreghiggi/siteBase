<?php 
include('topo.inc.php');

if($_GET['codigo'] == TRUE) 
    $codigo = anti_injection($_GET['codigo']); 
else 
    $codigo = anti_injection($_POST['codigo']);

$nome = addslashes($_POST['nome']);
$login = $_POST['login'];
$senha = $_POST['senha'];
$status = $_POST['status'];

if($_POST['cmd'] == "add")
{
    $senha_aux = @md5($senha);

    $str = "INSERT INTO usuarios (nome, login, senha, status) VALUES ('$nome', '$login', '$senha_aux', '$status')";
    $rs  = mysql_query($str) or die(mysql_error());
    
    redireciona("usuarios.php?ind_msg=1");
}

if($_POST['cmd'] == "edit")
{
    $senha_aux = @md5($senha);
    
    $str = "UPDATE usuarios SET nome = '$nome', login = '$login', status = '$status' WHERE codigo = '$codigo'";
    $rs  = mysql_query($str) or die(mysql_error());
    
    if($senha)
    {
        $str = "UPDATE usuarios SET senha = '$senha_aux' WHERE codigo = '$codigo'";
        $rs  = mysql_query($str) or die(mysql_error());
    }
    
    redireciona("usuarios.php?ind_msg=2");
}

if($_GET['ind_msg'] == 1)
    $msg = '<div class="alert success">Usuário cadastrado com sucesso!</div>';
elseif($_GET['ind_msg'] == 2)
    $msg = '<div class="alert success">Usuário editado com sucesso!</div>';

$str = "SELECT * FROM usuarios WHERE codigo = '$codigo'";
$rs  = mysql_query($str) or die(mysql_error());
$vet = mysql_fetch_array($rs);

include('menu.inc.php'); 
?>
<script language="javascript">
function valida(ind)
{   
    if(ind == 1)
        document.form.cmd.value = "add";
    else
        document.form.cmd.value = "edit";
}
</script>

<section id="content">

<div class="g12">
    <h1>Usuários</h1>
    <p></p>

    <?=$msg?>
    
    <!-- area do form -->
    <form name="form" id="form" method="post" autocomplete="off">
    <input type="hidden" name="cmd">
    <input type="hidden" name="codigo" value="<?=$vet['codigo']?>">
    <fieldset>
        <section>
            <label for="text_field">Nome:</label>
            <div><input type="text" id="nome" name="nome" value="<?=stripslashes($vet['nome'])?>" required></div>
        </section>
        <section>
            <label for="login">Login:</label>
            <div> 
                <input id="login" name="login" type="login" value="<?=$vet['login']?>" required style="width:100px;" maxlength="10" onKeyPress="javascript: return somente_letras_minusculas_numeros(event);">
                <br><br><span><i>- Permitido penas letras minúsculas e números;<br>- Limite máximo de 10 caracteres;</i></span>
            </div>
        </section>                         
        <section>
            <label for="text_field">Senha:</label>
            <div><input type="password" id="senha" name="senha" password ></div>
        </section>
        <section>
            <label for="textarea_auto">Status:</span></label>
            <div>
                <select name="status">
                    <option value="1" <?=($vet['status'] == FALSE || $vet['status'] == 1) ? "selected" : "" ?> >Ativo</option>
                    <option value="2" <?=($vet['status'] == 2) ? "selected" : "" ?>>Inativo</option>
                </select>
            </div>
        </section>
        <section>
            <?
            if($ind == 1)
            {
            ?>
            <div><button class="i_tick icon" onClick="javascript: valida(1);">Cadastrar</button></div>
            <?
            }
            else
            {
            ?>
            <div><button class="i_refresh_3 icon" onClick="javascript: valida(2);">Alterar</button></div>
            <?
            }
            ?>
        </section>
    </fieldset>
    </form>
    <!-- end form -->
    
    <?
    $str = "SELECT * FROM usuarios ORDER BY nome";
    $rs  = mysql_query($str) or die(mysql_error());
    $num = mysql_num_rows($rs);
      
    if($num > 0)
    {
    ?>
    <h1>Lista de usuários</h1>
    <p>Todos os usuários cadastrados no sistema</p>
    <table >
        <thead>
            <tr>
                <th colspan="3">LEGENDA - Status do usuário</th>
            </tr>
        </thead>
        <tbody>
            <tr >
                <td style="background-color:#f0a8a8; width:50%;">Inativo</td>
                <td style="background-color:#BFDFFF">Ativo</td>
            </tr>
        </tbody>
    </table>
    
    <table class="datatable">
        <thead>
            <tr>
                <th>Usuário</th>
                <th>Login</th>                
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?
        while($vet = mysql_fetch_array($rs))
        {
            if($vet['status'] != 1)
                $class = 'class="gradeA"';
            else
                $class = 'class="gradeU"';
        ?>
            <tr <?=$class?>>
                <td><?=stripslashes($vet['nome'])?></td>
                <td><?=$vet['login']?></td>
                <td class="c"><a class="btn i_pencil icon small" title="editar" href="usuarios.php?ind=2&codigo=<?=$vet['codigo']?>" >editar</a></td> 
            </tr>
        <?
        }
        ?>
        </tbody>
    </table>
    <!-- end form -->
    <?
    }
    ?>
</div>
</section>
<!-- end div #content -->

<?php include('rodape.inc.php'); ?>    

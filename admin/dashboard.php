<?php
include('topo.inc.php');
include('menu.inc.php');
?>  
<section id="content">

<div class="g12 nodrop">
    <h1>Início</h1>
    <p>Visão geral de algumas funcionalidades</p>
</div>

<div class="g12 nodrop">
    <div class="widget number-widget" id="number_widget">
        <h3 class="handle">Números do site</h3>
        <div>
            <ul>
                <li><a href="pedidos.php"><span><?=total_pedidos();?></span> Total de pedidos&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<span>R$ <?=total_pedidos_valor();?></span> Valor total de pedidos</a></li>
                <li><a href="pedidos.php?status=4"><span><?=total_vendas(0);?></span> Total de vendas pendentes&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<span>R$ <?=total_vendas_valor(0);?></span> Valor total de vendas pendentes</a></li>
                <li><a href="pedidos.php?status=1"><span><?=total_vendas(1);?></span> Total de vendas confirmadas / pagas&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<span>R$ <?=total_vendas_valor(1);?></span> Valor total de vendas confirmadas / pagas</a></li>
                <li><a href="pedidos.php?status=2"><span><?=total_vendas(2);?></span> Total de vendas enviadas / entregues&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<span>R$ <?=total_vendas_valor(2);?></span> Valor total de enviadas / entregues</a></li>
                <li><a href="pedidos.php?status=3"><span><?=total_vendas(3);?></span> Total de vendas canceladas&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<span>R$ <?=total_vendas_valor(3);?></span> Valor total de canceladas</a></li>
                <li><a href="produtos.php"><span><?=total_produtos_ativos();?></span> Total de produtos cadastrados (ATIVOS)</a></li>
                <li><a href="clientes.php"><span><?=total_clientes();?></span> Total de cadastros no site</a></li>
            </ul>            
        </div>
    </div>
</div>

<div class="g12 widgets">
    <div class="widget" id="widget_tabs">
        <h3 class="handle">Instruções Gerais</h3>
        <div class="tab">
            <div id="tabs-1" style="padding:10px;">
                <h6>
                    Seja bem-vindo(a) <?=$_SESSION['adm_nome']?> ao gerenciador de conteúdo - <?=$n_empresa?>.
                    <br><br>
                    No menu ao lado você tem acesso a todas as áreas dinâmicas do website e terá a possibilidade de adicionar, editar e excluir conteúdo delas.
                </h6>

                <br><br>
                
                <h6>
                    Abaixo as dimensões padrão que devem ser utilizadas.
                    <br>
                    Logotipo: 262x61<br>
                    Banner Principal: 1140x350<br>
                    Publicidade Horizontal (abaixo do banner principal): 870x150<br>
                    Publicidade Vertical (na barra lateral esquerda): 270x400
                </h6>
            </div>
        </div>
    </div>
</div>

<div class="g12 widgets">
    <div class="widget" id="widget_tabs">
        <h3 class="handle">Últimos acessos ao gerenciador</h3>
        <div class="tab">
            <div id="tabs-1" style="padding:10px;">
                <?
                $str = "SELECT A.*, DATE_FORMAT(A.data, '%d/%m/%Y %H:%i') AS data_acesso, B.nome AS usuario 
                    FROM acessos A
                    INNER JOIN usuarios B ON A.idusuario = B.codigo
                    WHERE B.status = '1'
                    ORDER BY A.data DESC LIMIT 10";
                $rs  = mysql_query($str) or die(mysql_error());
                $num = mysql_num_rows($rs);
                  
                if($num > 0)
                {
                ?>      
                <table>
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>IP</th>                
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?
                    while($vet = mysql_fetch_array($rs))
                    {
                    ?>
                        <tr>
                            <td><?=stripslashes($vet['usuario'])?></td>
                            <td><?=$vet['ip']?></td>
                            <td><?=$vet['data_acesso']?></td>
                        </tr>
                    <?
                    }
                    ?>
                    </tbody>
                </table>
                </fieldset>
                
                <!-- end form -->
                <?
                }
                ?>
            </div>
        </div>
    </div>
</div>

</section>
        
<?php include('rodape.inc.php'); ?>    

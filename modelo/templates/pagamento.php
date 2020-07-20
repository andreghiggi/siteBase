<?php include 'modelo/templates/modal.php'; ?>

<div class="col-md-12">
  <div class="groupData" id="paymentMethods">
    
	<!--<div class="col-md-12">
      <div id="paymentMethodsOptions">
        <?php //<div class="field radio col-md-6" >?>
        <div class="col-md-6" style="text-align:center">
          <input id="creditCardRadio" type="radio" name="changePaymentMethod" value="creditCard"  style="margin-right:6px;">Cartão de Crédito</input>
        </div>

        <div class="col-md-6" style="text-align:center">
          <input id="boletoRadio" type="radio" name="changePaymentMethod" value="boleto" style="margin-right:6px;">Boleto</input>
        </div>
        <br clear="all"/>
      </div>
    </div>-->

  <div id="creditCardData" class="paymentMethodGroup" dataMethod="creditCard" >

    <div id="cardData" style="text-align: center;">

    <h2>Dados do Cartão </h2>

    <div class="row" id="cardBrand">
    	<div class="col-6 text-right">
    		<label for="cardNumber">Número <font color="red">*</font></label>
    	</div>
    	<div class="col">
    		<input type="text" name="cardNumber" id="cardNumber" class="form-control w-50" onblur="brandCard();" />
    	</div>
    	<div class="col-1">
	      <img class="bandeiraCartao" id="bandeiraCartao" />
	    </div>
    </div>

    <div class="row mt-3" id="expiraCartao">
    	<div class="col text-right">
    		<label for="cardExpirationMonth">Data de Vencimento (99/9999) <font color="red">*</font></label>
    	</div>
    	<div class="col">
		  	<div class="input-group w-50">
		  		<input type="text" name="cardExpirationMonth" id="cardExpirationMonth" class="form-control" maxlength="2" style="max-width:20% !important" /> <span class="mr-2 ml-2">/</span>
		    	<input type="text" name="cardExpirationYear" id="cardExpirationYear" class="form-control" maxlength="4" />
		    </div>
    	</div>
    </div>

    <div class="row mt-3" id="cvvCartao">
    	<div class="col text-right">
    		<label for="cardCvv">Código de Segurança <font color="red">*</font></label>
    	</div>
    	<div class="col">
    		<input type="text" name="cardCvv" id="cardCvv" maxlength="5" class="form-control w-25" />
    	</div>
    </div>

    <div class="row mt-3 mb-3" id="installmentsWrapper">
    	<div class="col text-right">
    		<label for="installmentQuantity">Parcelamento</label>
    	</div>
    	<div class="col">
    		<select name="installmentQuantity" id="installmentQuantity" class="form-control w-50"></select>
      	<input type="hidden" name="installmentValue" id="installmentValue" />
    	</div>
    </div>

    <h2 style="mt-3">Dados do Titular do Cartão</h2>

    <div id="holderDataChoice" class="row mt-3">
    	<div class="col text-right">
    		<div class="field radio" style="margin:4px 0;">
		      <input type="radio" name="holderType" id="sameHolder" value="sameHolder">mesmo que o comprador</input>
		    </div>
    	</div>
    	<div class="col text-left">
				<div class="field radio" style="margin:4px 0;">
		      <input type="radio" name="holderType" id="otherHolder" value="otherHolder">outro</input>
		    </div>
			</div>
    </div>

    

    <div class="row mt-3">
    	<div class="col text-right">
    		<label for="creditCardHolderBirthDate">Data de Nascimento do Titular do Cartão <font color="red">*</font></label>
    	</div>
    	<div class="col">
    		<input type="text" name="creditCardHolderBirthDate" id="creditCardHolderBirthDate" class="form-control w-50" maxlength="10" />
    	</div>
    </div>

    <div id="dadosOtherPagador" class="dadosOtherPagador">

        <div id="holderData">

          <div class="row mt-3">
          	<div class="col text-right">
          		<label for="creditCardHolderName">Nome (Como está impresso no cartão) <font color="red">*</font></label>
          	</div>
          	<div class="col">
          		<input type="text" name="creditCardHolderName" id="creditCardHolderName" class="form-control w-50"/>
          	</div>
          </div>

          <div class="row mt-3" id="CPFP">
          	<div class="col text-right">
          		<label for="creditCardHolderCPF">CPF (somente n&uacute;meros) <font color="red">*</font></label>
          	</div>
          	<div class="col">
          		<input type="text" name="creditCardHolderCPF" id="creditCardHolderCPF" maxlength="14" class="form-control w-50"/>
          	</div>
          </div>

          <div class="row mt-3" id="TelP">
          	<div class="col text-right">
          		<label for="creditCardHolderAreaCode">Telefone <font color="red">*</font></label>
          	</div>
          	<div class="col">
          		<div class="input-group w-50">
		        		<input type="text" name="creditCardHolderAreaCode" id="creditCardHolderAreaCode" class="form-control w-25" maxlength="2" style="max-width:20%" />
		          	<input type="text" name="creditCardHolderPhone" id="creditCardHolderPhone" class="form-control w-50" maxlength="9" />
		          </div>
          	</div>
          </div>

          <h2>Endereço de Cobrança</h2>

          <div class="row mt-3" id="CEPP">
          	<div class="col text-right">
          		<label for="billingAddressPostalCode">CEP <font color="red">*</font></label>
          	</div>
          	<div class="col">
          		<input type="text" name="billingAddressPostalCode" id="billingAddressPostalCode" maxlength="9" class="form-control w-50"/>
          	</div>
          </div>


          <div class="row mt-3" id="EndP">
          	<div class="col text-right">
          		<label for="billingAddressStreet">Endereço <font color="red">*</font></label>
          	</div>
          	<div class="col">
          		<input type="text" name="billingAddressStreet" id="billingAddressStreet" class="form-control w-50"/>
          	</div>
          </div>

          <div class="row mt-3" id="NumP">
          	<div class="col text-right">
          		<label for="billingAddressNumber">Número <font color="red">*</font></label>
          	</div>
          	<div class="col">
          		<input type="text" name="billingAddressNumber" id="billingAddressNumber" size="5" class="form-control w-25"/>
          	</div>
          </div>

          <div class="row mt-3" id="ComP">
          	<div class="col text-right">
          		<label for="billingAddressComplement">Complemento</label>
          	</div>
          	<div class="col">
          		<input type="text" name="billingAddressComplement" id="billingAddressComplement" class="form-control w-50"/>
          	</div>
          </div>

          <div class="row mt-3" id="BairP">
          	<div class="col text-right">
          		<label for="billingAddressDistrict">Bairro <font color="red">*</font></label>
          	</div>
          	<div class="col">
          		<input type="text" name="billingAddressDistrict" id="billingAddressDistrict" class="form-control w-50"/>
          	</div>
          </div>

          <div class="row mt-3" id="CidP">
          	<div class="col text-right">
          		<label for="billingAddressCity">Cidade <font color="red">*</font></label>
          	</div>
          	<div class="col">
          		<input type="text" name="billingAddressCity" id="billingAddressCity" class="form-control w-50"/>
          	</div>
          </div>

          <div class="row mt-3" id="EstP">
          	<div class="col text-right">
          		<label for="billingAddressState">Estado <font color="red">*</font></label>
          	</div>
          	<div class="col">
          		<input type="text" name="billingAddressState" id="billingAddressState" class="form-control w-25" maxlength="2" onBlur="this.value=this.value.toUpperCase()"/>
          	</div>
          </div>

          <div class="row mt-3 mb-3">
          	<div class="col text-right">
          		<label for="billingAddressCountry">País</label>
          	</div>
          	<div class="col">
          		<input type="text" name="billingAddressCountry" id="billingAddressCountry" value="Brasil" readonly="readonly" class="form-control w-50"/>
          	</div>
          </div>

        </div>
    </div>


      <input type="hidden" name="creditCardToken" id="creditCardToken"  />
      <input type="hidden" name="creditCardBrand" id="creditCardBrand"  />
      <center><?php //btn btn-default btn-block ?>
        <input type="button" id="creditCardPaymentButton" class="btn btn-primary mt-3" onclick="pagarCartao(PagSeguroDirectPayment.getSenderHash());" value="Finalizar compra" />
      </center>

    </div>
  </div>

  <center>
    <div id="boletoData" name="boletoData" class="paymentMethodGroup" dataMethod="boleto"  style="display:none;">
        <?php //btn btn-default btn-block  ?>
      <input type="button" id="boletoButton" value="Gerar Boleto" class="btn btn-primary" onclick="pagarBoleto(PagSeguroDirectPayment.getSenderHash());" />
    </div>
    
    <br />
    
  </center>

  </div>
</div>

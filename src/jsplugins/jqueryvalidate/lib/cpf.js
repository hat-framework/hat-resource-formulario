$.validator.addMethod(
  "cpf",
  function (value, element) { 
    var strCPF = value.replace(/[\.-]/g, "");
    if (strCPF == "00000000000"){return this.optional(element) || false;} 
    var Soma = 0;
    for (i=1; i<=9; i++) {Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);}
    var Resto = (Soma * 10) % 11; 
    if ((Resto == 10) || (Resto == 11)) {Resto = 0;} 
    if (Resto != parseInt(strCPF.substring(9, 10))){return this.optional(element) || false;} 
    Soma = 0; 
    for (i = 1; i <= 10; i++){Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);}
    Resto = (Soma * 10) % 11; 
    if ((Resto == 10) || (Resto == 11)) {Resto = 0;} 
    if (Resto != parseInt(strCPF.substring(10, 11))){return this.optional(element) || false;} 
    return true;
  }, 
  "CPF Inválido!"
);
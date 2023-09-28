
function VerNoVer(prod,tipo) {
    // Muestra y oculta ventanas
    var sale='sale_'+prod+tipo;
    var x = document.getElementById(sale);
    if (x.style.display === "none") {
        x.style.display = "block";
    } else   {
        x.style.display = "none";
    }
}

function VerNoVerPass(idPasswd,idIcono) {
    // Muestra y oculta password y cambia ícono de <i class="far fa-eye-slash">
    var passwd=document.getElementById(idPasswd);
    var icono=document.getElementById(idIcono);
    if (passwd.type == 'password') {
        passwd.type = 'text';
        icono.className = 'far fa-eye'
    } else   {
        passwd.type = 'password';
        icono.className = 'far fa-eye-slash'
    }
}

Livewire.on('alerta',function(titulo, texto){
    //Muestra ventanita de alerta con SweetAlert en Livewire
    Swal.fire({
        title: titulo,
        text: texto,
        icon: 'success',
        confirmButtonText: 'Continuar'
    })
})   


function subtotal(id,entrega,precio) {
    //Calcula subtotales de cada pedido y calcula el total con suma de subtotales
    var n1 = document.getElementById('com1_'+id).value
     //------------------------ Duplica en caso de comid:
     if(entrega=="comid"){
        document.getElementById('com2_'+id).value = n1
    }
    var n2 = document.getElementById('com2_'+id).value
    if(n1 == null  || n1 == undefined  || n1 == "")  {n1="0";}
    if(n2 == null  || n2 == undefined  || n2 == "")  {n2="0";}
    var cantidad = parseFloat(n1) + parseFloat(n2);
    //if(entrega == 'comid'){      var subtotal = cantidad * precio * 2
    //}else{               
        var subtotal = cantidad * precio * 1      
    //}
    document.getElementById('subtot_'+id).innerHTML = subtotal
    //----------------------- Calcula gran total
    cadaUno=document.getElementsByClassName('CalculadoraSubtotal');
    var x=0; var tot=0;
    for (i=0, max=cadaUno.length; i<max; i++) {
        x = parseFloat(cadaUno[i].innerHTML);
        if(isNaN(x) || x=="") {x=0;}
        tot += parseFloat(x);
    }
    document.getElementById('CalculadoraTotal').innerHTML = tot
    //------------------------ Genera resumen de variables para enviar a back
    var sab = document.getElementById('sab_'+id).value
    document.getElementById('fin_'+id).value = "|id_"+id+"|na_"+n1+"|nb_"+n2+"|sab_"+sab
    //------------------------ Muestra ícono de carrito si hay algo
    var car = document.getElementById('carrito_'+id);
    var tra = document.getElementById('basura_'+id);
    if(n1+n2 > 0){
        car.style.display = "inline-block";
        
    }else{
        car.style.display = "none";
        
    }

}


// cuando selecciona sabor, borra todos los valores
function borra(id) {
    document.getElementById('com1_'+id).value =''
    document.getElementById('com2_'+id).value =''
    document.getElementById('fin_'+id).value = ''
}
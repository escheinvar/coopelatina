
function VerNoVer(prod,tipo) {
    // Muestra y oculta ventanas
    var x = document.getElementById('sale_'+prod+tipo);
    if (x.style.display == "none") {
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

//script para activas SELECT2
$(document).ready(function() {
    $('.select2').select2();
});

//Inhabilitar tecla de espacio
//function NoEspacio(e, campo){
//		key = e.keyCode ? e.keyCode : e.which;
//		if (key == 32) {return false;}
//}		
//onkeypress="javascript: return ValidarNumero(event,this)" 

Livewire.on('alerta',function(titulo, texto){
    //Muestra ventanita de alerta con SweetAlert en Livewire
    Swal.fire({
        title: titulo,
        text: texto,
        icon: 'success',
        confirmButtonText: 'Continuar'
    })
})
   

Livewire.on('alertaConfirma',function(titulo1, texto1){
    Swal.fire({
        title: titulo1,
        text: texto1,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Continuar'
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire(
            'Se confirma',
            'hecho',
            'success'
          )
        }
      })
})



// cuando selecciona sabor, borra todos los valores
/*
function borra(id) {
    document.getElementById('com1_'+id).value =''
    document.getElementById('com2_'+id).value =''
    document.getElementById('fin_'+id).value = ''
}
*/
<?php

namespace App\Http\Livewire\Admon;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class VerUsuariosLiveComponent extends Component {
    public $nombre, $ap_1, $ap_2, $usr, $tel, $mail, $dir, $password, $password_confirmation, $estatus, $privs, $activo;
    public $rules, $buscar, $MyId, $usuarioEdita, $CambiaPass, $VerOcultos='1';
    public $orden='nombre', $text1='Agregar';
    public $sentido ='asc';
    public $tipo='nvo';
    public $InputType="password", $IconoType="far fa-eye-slash";
    

    ############################################################ Ordena la tabla por el campo
    public function orden($campo){  
        $this->orden = $campo;
        if($this->sentido == 'asc'){
            $this->sentido = 'desc';
        }else{
            $this->sentido = 'asc';
        }       
    }


    ############################################################ Borra el campo de búsqueda
    public function borrabuscar(){
        $this->buscar="";
    }


    ############################################################ Muestra password
    public function MuestraUoculta(){
        if($this->InputType == "password"){  
            $this->InputType="text";
            $this->IconoType="far fa-eye";
        }else{
            $this->InputType="password";
            $this->IconoType="far fa-eye-slash";
        }
       
    }


    ############################################################ Carga la tabla
    public function render()  { 
        if($this->buscar != "" ){
            $usuarios = User::where('activo',$this->VerOcultos)
                ->where(
                    function($q){
                        return $q
                        ->where('nombre','like','%'.$this->buscar.'%') 
                        ->orWhere('ap_pat','like','%'.$this->buscar.'%')
                        ->orWhere('usr','like','%'.$this->buscar.'%')
                        ->orWhere('tel','like','%'.$this->buscar.'%')
                        ->orWhere('mail','like','%'.$this->buscar.'%');
                    })
                ->orderBy($this->orden, $this->sentido)->get();
        }else{
            $usuarios = User::where('activo',$this->VerOcultos)
                ->orderBy($this->orden, $this->sentido)->get();
        }
        return view('livewire.admon.ver-usuarios-live-component',compact('usuarios'));
    }
    

    ############################################################ Diferencía entre nuevo usr y editar usr
    public function  defineType($tipo,$MyId){
        $this->reset('nombre','ap_1','ap_2','usr','estatus','privs','tel','mail','dir','password','password_confirmation');
        if($tipo == 'nuevo'){
            #$this->nombre="";
            $this->text1= 'Agregar';
           
        }else if($tipo == 'edita'){
            $this->text1= 'Editar';
            $usuarioEdita = User::where('id',$MyId)->first();
            $this->nombre = $usuarioEdita->nombre;
            $this->ap_1 = $usuarioEdita->ap_pat;
            $this->ap_2 = $usuarioEdita->ap_mat;
            $this->usr = $usuarioEdita->usr;
            $this->tel = $usuarioEdita->tel;
            $this->mail = $usuarioEdita->mail;
            $this->dir = $usuarioEdita->direccion;
            $this->estatus = $usuarioEdita->estatus;
            $this->privs = $usuarioEdita->priv;
            $this->activo= $usuarioEdita->activo;
        }

    }
    
    ############################################################ Emergente: Cancela emertente
    public function CancelSubmit(request $request){
        $this->reset('nombre','ap_1','ap_2','usr','estatus','privs','tel','mail','dir','password','password_confirmation');
    }

    ############################################################ Emergente: Guardar nuevo usuario
    public function GuardaElNuevo(request $request){

        $this->rules=[
            'nombre' => 'required',
            'ap_1' => 'required', 
            'ap_2' => 'required', 
            'usr' => 'required|unique:users,usr',
            'tel' => 'required', 
            'mail' => 'required', 
            'dir' => 'required', 
            'password' => 'required|confirmed', 
            'password_confirmation'=>'required',
            'estatus' => 'required', 
            'privs' => 'required',
        ];
        $this->validate();

        $fechaHoy=date('Y-m-d H:i:s');

        User::create([
            'nombre' => $this->nombre,
            'ap_pat' => $this->ap_1, 
            'ap_mat' => $this->ap_2, 
            'usr' => strtolower($this->usr),
            'activo' => '1', 
            'estatus' => $this->estatus, 
            'priv' => $this->privs,
            'tel' => $this->tel,
            'mail' => $this->mail, 
            'direccion' => $this->dir, 
            'dateregistro' => $fechaHoy,
            'password' =>  Hash::make($this->password)
        ]); 

        $this->emit('alerta','Guardado',"$this->nombre fué integrado a la base!!");
        
        #$this->reset(['nombre','ap_1','ap_2','usr','estatus','privs','tel','mail','dir','password','password_confirmation']);
    }


    ############################################################ Emergente: Editar  usuario existenta
    public function GuardaEdita(request $request){

        $this->rules=[
            'nombre' => 'required',
            'ap_1' => 'required', 
            'tel' => 'required', 
            'mail' => 'required', 
            'dir' => 'required', 
            'estatus' => 'required', 
            'privs' => 'required',
        ];
        $this->validate();

        if($this->CambiaPass=='1'){
            $this->rules=[
                'password' => 'required|confirmed', 
                'password_confirmation'=>'required',
            ];
        }
        $this->validate();

        $fechaHoy=date('Y-m-d H:i:s');

        User::where('usr',$this->usr)->update([
            'nombre' => $this->nombre,
            'ap_pat' => $this->ap_1, 
            'ap_mat' => $this->ap_2, 
            'usr' => $this->usr,
            'activo'=>$this->activo,
            'estatus' => $this->estatus, 
            'priv' => $this->privs,
            'tel' => $this->tel,
            'mail' => $this->mail, 
            'direccion' => $this->dir, 
            'dateregistro' => $fechaHoy,
            
        ]); 

        if($this->CambiaPass=='1'){
            User::where('usr',$this->usr)->update([
                'password' =>  Hash::make($this->password),
            ]);
        }

        $this->emit('alerta','Editado',"$this->nombre fué editado correctamente!!");
        
        #$this->reset(['nombre','ap_1','ap_2','usr','estatus','privs','tel','mail','dir','password','password_confirmation']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class LoginController extends Controller {
    public function index(Request $request){
        $erro = '';
        if($request->get('erro') == 1){
            $erro = 'Email e/ou senha inválido.';
        }
        if($request->get('erro') == 2){
            $erro = 'Necessário realizar login para ter acesso a página.';
        }
        return view('site.login', ['titulo' => 'Login', 'erro' => $erro]);
    }

    public function autenticar(Request $request){
        //regras de validação
        $regras = [
            'usuario' => 'email',
            'senha' => 'required'
        ];

        //Mensagem de feedback de validadção
        $feedback = [
            'usuario.email' => 'O campo usuário (email) é obrigatório',
            'senha.required' => 'O campo senha é obrigatório'
        ];

        //caso nao passe pelo validade, ele retorna para a rota anterior
        $request->validate($regras, $feedback);

        // Recuperando os parametros do formulário
        $email = $request->get('usuario');
        $password = $request->get('senha');

        $usuario = User::where('email', $email)->where('password', $password)->first();

        if(isset($usuario->name)){
            session_start();
            $_SESSION['nome'] = $usuario->nome;
            $_SESSION['email'] = $usuario->email;
            return redirect()->route('app.home');
        } else {
            return redirect()->route('site.login', ['erro' => 1]);
        }
    }

    public function sair(){
        session_destroy();
        return redirect()->route('site.index');
    }
}

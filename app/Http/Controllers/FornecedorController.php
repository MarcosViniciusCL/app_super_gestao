<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fornecedor;

class FornecedorController extends Controller
{
    public function index(){
        return view('app.fornecedor.index');
    }

    public function listar(Request $request){
        $fornecedores = Fornecedor::where('nome', 'like', '%'.$request->input('nome').'%')
            ->where('site', 'like', '%'.$request->input('site').'%')
            ->where('uf', 'like', '%'.$request->input('uf').'%')
            ->where('email', 'like', '%'.$request->input('email').'%')
            ->paginate(2);

        return view('app.fornecedor.listar', ['fornecedores' => $fornecedores, 'request' => $request->all()]);
    }

    public function adicionar(Request $request){
        // Foi utilizado a mesma rota para acesssa a pagina e enviar os dados. Porem com metodo POST e GET;
        // Caso não exista um token o metodo foi acessado via GET, caso contrario, via POST com os dados. 
        $msg = '';
        if($request->input('_token') != '' && $request->input('id') == ''){
            
            $regras = [
                'nome' => 'required|min:3|max:40',
                'site' => 'required',
                'uf' => 'required|min:2|max:2',
                'email' => 'email',
            ];

            $feedback = [
                'required' => 'O campo :attribute deve ser preenchido',
                'nome.min' => 'O nome deve ter no minimo 3 caracterers',
                'nome.max' => 'O nome deve ter no maximo 40 caracterers',
                'uf.min' => 'O campo UF deve ter no minimo 3 caracterers',
                'uf.max' => 'O campo UF deve ter no maximo 3 caracterers',
                'email' => 'O campo Email nao foi preenchido corretamente',
            ];

            $request->validate($regras, $feedback);

            $forn = new Fornecedor();
            $forn->create($request->all());

            echo 'For cadastrado';
        }

        if($request->input('_token') != '' && $request->input('id') != ''){
            $fornecedor = Fornecedor::find($request->input('id'));
            $update = $fornecedor->update($request->all());
            
            if($update){
                $msg = 'Atualização realizado com sucesso.';
            } else {
                $msg = 'Atualização apresentou problema.';
            }
            return redirect()->route('app.fornecedor.editar', ['id' => $request->input('id'), 'msg' => $msg]);
        }

        return view('app.fornecedor.adicionar', ['msg' => $msg]);
    }

    public function editar($id, $msg = ''){
        echo $id;

        $fornecedor = Fornecedor::find($id);

        return view('app.fornecedor.adicionar', ['fornecedor' => $fornecedor, 'msg' => $msg]);
    }

    public function excluir($id){
        Fornecedor::find($id)->delete();

        return redirect()->route('app.fornecedor');
    }
}

<?php

namespace App\Http\Controllers;
use App\Models\Aluno;
use App\Models\Curso;
use App\Models\Matricula;

use Illuminate\Http\Request;

class AlunoController extends Controller
{
 
    public function index()
    {
        $data = Aluno::with(['curso'])->get();

        return view('alunos.index', compact(['data']));
    }


    public function create()
    {
        $curso = Curso::orderBy('nome')->get();
        return view('alunos.create', compact(['curso']));
    }

   
    public function store(Request $request)
    {
        $rules = [
            'nome' => 'required|max:100|min:10',
            'curso' => 'required',
        ];

        $msgs = [
            "required" => "O preenchimento do campo [:attribute] é obrigatório!",
            "max" => "O campo [:attribute] possui tamanho máximo de [:max] caracteres!",
            "min" => "O campo [:attribute] possui tamanho mínimo de [:min] caracteres!",
        ];

        $request->validate($rules, $msgs);

        $curso = Curso::find($request->curso);

        if (isset($curso)) {

            $obj = new Aluno();
            $obj->nome = mb_strtoupper($request->nome, 'UTF-8');
            $obj->curso()->associate($curso);

            $obj->save();

            return redirect()->route('alunos.index');
        }
    }
    public function show($id)
    {
        $mat = Matricula::with(['disciplina'])
            ->where('aluno_id', '=', $id)->distinct()->get(['disciplina_id']);

        return view('alunos.show', compact(['mat']));
    }

    
    public function edit($id)
    {

        $curso = Curso::orderBy('nome')->get();
        $data = Aluno::find($id);


        if (isset($data)) {
            return view('alunos.edit', compact(['data', 'curso']));
        }
    }


    
    public function update(Request $request, $id)
    {
        $rules = [
            'nome' => 'required|max:100|min:10',
            'eixo' => 'required',

        ];
        $msgs = [
            "required" => "O preenchimento do campo [:attribute] é obrigatório!",
            "max" => "O campo [:attribute] possui tamanho máximo de [:max] caracteres!",
            "min" => "O campo [:attribute] possui tamanho mínimo de [:min] caracteres!",
        ];

        $request->validate($rules, $msgs);

        $curso = Curso::find($request->curso);
        $obj_aluno = Aluno::find($id);

        if (isset($curso) && isset($obj_aluno)) {

            $obj_aluno->nome = mb_strtoupper($request->nome, 'UTF-8');
            $obj_aluno->curso()->associate($curso);
            $obj_aluno->save();


            return redirect()->route('alunos.index');
        }
    }

   
    public function destroy($id)
    {
        $obj = Aluno::find($id);

        if (isset($obj)) {
            $obj->delete();
        } 

        return redirect()->route('alunos.index');
    }
}

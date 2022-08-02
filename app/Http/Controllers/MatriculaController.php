<?php

namespace App\Http\Controllers;
use App\Models\Disciplina;
use App\Models\Matricula;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $disciplinas = Disciplina::with(['curso'])
            ->orderBy('curso_id')->orderBy('id')->get();


        return view('matriculas.index', compact(['disciplinas']));
    }

   
    public function store(Request $request)
    {
        $rules = [
            'DISCIPLINA' => 'required',
        ];
        $msgs = [
            "required" => "O preenchimento do campo [:attribute] é obrigatório!",
            "max" => "O campo [:attribute] possui tamanho máximo de [:max] caracteres!",
            "min" => "O campo [:attribute] possui tamanho mínimo de [:min] caracteres!",
        ];

        $request->validate($rules, $msgs);

        $disciplina = $request->DISCIPLINA;


        for ($i = 0; $i < count($request->DISCIPLINA); $i++) {
            $doc = new Matricula();
            $doc->disciplina_id = $disciplina[$i];
            $doc->save();
        }

        return redirect()->route('alunos.index');
    }


}

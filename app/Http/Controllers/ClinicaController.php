<?php

namespace App\Http\Controllers;

use App\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClinicaController extends Controller
{
    public function getAllClinics(){
        $clinicas = Clinic::get();
        return response()->json(['clinicas' => $clinicas]);
    }

    public function store(Request $request)
    {
        $rules = [
            'razao_social'       => 'required|string|max:255',
            'nome_fantasia'      => 'required|string|max:255',
            'cnpj'               => 'required|digits:14|unique:clinicas,cnpj',
            'regional'           => 'required|integer',
            'data_inauguracao'   => 'required|date',
            'ativa'              => 'sometimes|boolean',
            'especialidades'     => 'required|array|min:5',
            'especialidades.*'   => 'integer',
        ];
        
        $messages = [
            'razao_social.required'      => 'O campo razão social é obrigatório.',
            'razao_social.max'           => 'A razão social não pode exceder 255 caracteres.',
            'nome_fantasia.required'     => 'O campo nome fantasia é obrigatório.',
            'nome_fantasia.max'          => 'O nome fantasia não pode exceder 255 caracteres.',
            'cnpj.required'              => 'O campo CNPJ é obrigatório.',
            'cnpj.digits'                => 'O CNPJ deve conter exatamente 14 dígitos.',
            'cnpj.unique'                => 'Este CNPJ já está cadastrado.',
            'regional.required'          => 'O campo regional é obrigatório.',
            'regional.integer'           => 'O campo regional deve ser um número inteiro.',
            'data_inauguracao.required'  => 'O campo data de inauguração é obrigatório.',
            'data_inauguracao.date'      => 'A data de inauguração deve ser uma data válida.',
            'ativa.boolean'              => 'O campo ativa deve ser verdadeiro ou falso.',
            'especialidades.required'    => 'Informe ao menos 5 especialidades.',
            'especialidades.array'       => 'O campo especialidades deve ser um array.',
            'especialidades.min'         => 'Informe no mínimo 5 especialidades.',
            'especialidades.*.integer'   => 'Cada especialidade deve ser um número inteiro.',
        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }
        $data = $request->only(['razao_social', 'nome_fantasia', 'cnpj', 'regional', 'data_inauguracao', 'ativa', 'especialidades']);

        $data['especialidades'] = implode(', ', $data['especialidades']);

        $clinic = Clinic::create($data); 

        return response()->json([
            'message' => 'Clínica criada com sucesso',
            'clinic'  => $clinic
        ], 201);
    }

    public function delete(Request $request){
        $rules = [
            'id'       => 'required',
        ];
        
        $messages = [
            'id.required'      => 'Precisa ser informado a clinica para deletar.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }
        
        $data = $request->only(['id']);

        if(Clinic::where('id', $data['id'])->delete()){
            return response()->json(['message' => 'Clinica deletada com sucesso']);
        }
        else{
            return response()->json(['message' => 'Erro ao deletar clinica']);
        }
    }

    public function edit($id){
        if($clinica = Clinic::where('id', $id)->first()){
            return response()->json(['message' => 'success', 'clinica' => $clinica]);
        }
        else{
            return response()->json(['message' => 'error']);
        }
    }

    public function update(Request $request, $id){
        $rules = [
            'razao_social'       => 'required|string|max:255',
            'nome_fantasia'      => 'required|string|max:255',
            'cnpj'               => ['required','digits:14',Rule::unique('clinicas', 'cnpj')->ignore($id)],
            'regional'           => 'required|integer',
            'data_inauguracao'   => 'required|date',
            'ativa'              => 'sometimes|boolean',
            'especialidades'     => 'required|array|min:5',
            'especialidades.*'   => 'integer',
        ];
        
        $messages = [
            'razao_social.required'      => 'O campo razão social é obrigatório.',
            'razao_social.max'           => 'A razão social não pode exceder 255 caracteres.',
            'nome_fantasia.required'     => 'O campo nome fantasia é obrigatório.',
            'nome_fantasia.max'          => 'O nome fantasia não pode exceder 255 caracteres.',
            'cnpj.required'              => 'O campo CNPJ é obrigatório.',
            'cnpj.digits'                => 'O CNPJ deve conter exatamente 14 dígitos.',
            'cnpj.unique'                => 'Este CNPJ já está cadastrado.',
            'regional.required'          => 'O campo regional é obrigatório.',
            'regional.integer'           => 'O campo regional deve ser um número inteiro.',
            'data_inauguracao.required'  => 'O campo data de inauguração é obrigatório.',
            'data_inauguracao.date'      => 'A data de inauguração deve ser uma data válida.',
            'ativa.boolean'              => 'O campo ativa deve ser verdadeiro ou falso.',
            'especialidades.required'    => 'Informe ao menos 5 especialidades.',
            'especialidades.array'       => 'O campo especialidades deve ser um array.',
            'especialidades.min'         => 'Informe no mínimo 5 especialidades.',
            'especialidades.*.integer'   => 'Cada especialidade deve ser um número inteiro.',
        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }
        $data = $request->only(['razao_social', 'nome_fantasia', 'cnpj', 'regional', 'data_inauguracao', 'ativa', 'especialidades']);

        $data['especialidades'] = implode(', ', $data['especialidades']);

        $clinic = Clinic::findOrFail($id);
        
        $clinic->update($data);

        return response()->json([
            'message' => 'Clínica atualizada com sucesso',
            'clinic'  => $clinic
        ], 200);
    }
}
